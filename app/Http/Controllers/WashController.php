<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Wash;
use App\Models\WashType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $washTypes = WashType::all();

        $recentWashes = Wash::with('washType')
            ->when($search, function ($query, $search) {
                return $query->where('plate', 'like', "%{$search}%")
                    ->orWhereHas('washType', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('washes.index', compact('washTypes', 'recentWashes', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wash_type_id' => 'required|exists:wash_types,id',
            'plate' => 'nullable|string|max:10',
        ]);

        try {
            DB::beginTransaction();

            $washType = WashType::findOrFail($request->wash_type_id);

            // 1. Crear el registro de lavado
            $wash = Wash::create([
                'wash_type_id' => $request->wash_type_id,
                'plate' => $request->plate,
                'completed_at' => now(),
            ]);

            // 2. Crear la transacción de forma independiente
            $transaction = Transaction::create([
                'transaction_number' => 'FAC-'.strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'total_amount' => $washType->price,
                'payment_method' => 'cash',
                'status' => 'completed',
            ]);

            // 3. Vincular el lavado a la transacción mediante un item polimórfico
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'serviceable_id' => $wash->id,
                'serviceable_type' => Wash::class,
                'description' => 'Servicio de Lavado: '.$washType->name.($request->plate ? ' ('.$request->plate.')' : ''),
                'quantity' => 1,
                'unit_price' => $washType->price,
                'subtotal' => $washType->price,
            ]);

            DB::commit();

            Alert::Toast('Lavado registrado y facturado correctamente.', 'success');

            return redirect()->route('washes.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Alert::Toast('Error al procesar el lavado: '.$e->getMessage(), 'error');

            return back();
        }
    }
}
