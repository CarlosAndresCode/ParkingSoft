<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $transactions = Transaction::with(['items', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('transaction_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('items', function ($q) use ($search) {
                        $q->where('description', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->type === 'retail') {
            return $this->processRetailSale($request);
        }

        return back()->with('error', 'Tipo de transacción no soportado.');
    }

    private function processRetailSale(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $productsData = [];

            // 1. Validar stock y calcular total
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    Alert::toast("Stock insuficiente para {$product->name}. Disponible: {$product->stock}", 'error');
                    return back();
                }

                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;

                $productsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Crear la transacción
            $transaction = Transaction::create([
                'transaction_number' => 'RET-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            // 3. Crear los items de transacción y descontar stock
            foreach ($productsData as $data) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'serviceable_id' => $data['product']->id,
                    'serviceable_type' => Product::class,
                    'description' => 'Venta de Producto: ' . $data['product']->name,
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['product']->price,
                    'subtotal' => $data['subtotal'],
                ]);

                // Descontar stock
                $data['product']->decrement('stock', $data['quantity']);
            }

            DB::commit();

            Alert::toast('Venta realizada correctamente. Total: $' . number_format($totalAmount, 2), 'success');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Alert::toast('Error al procesar la venta: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['items.serviceable', 'user']);
        return response()->json($transaction);
    }
}
