<?php

namespace App\Http\Controllers;

use App\Models\CashRegisterSession;
use App\Models\ParkingSession;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class CashRegisterController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $openSession = CashRegisterSession::openForUser($user->id)->first();

        $summary = null;
        if ($openSession) {
            $now = now();
            $parkingSum = ParkingSession::query()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('exit_time', [$openSession->opened_at, $now])
                ->sum('total_price');

            $parkingCount = ParkingSession::query()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('exit_time', [$openSession->opened_at, $now])
                ->count();

            $subsSum = Subscription::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$openSession->opened_at, $now])
                ->sum('price');

            $subsCount = Subscription::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$openSession->opened_at, $now])
                ->count();

            $summary = [
                'parking_sum' => $parkingSum,
                'parking_count' => $parkingCount,
                'subs_sum' => $subsSum,
                'subs_count' => $subsCount,
                'income_sum' => ($parkingSum + $subsSum),
                'income_count' => ($parkingCount + $subsCount),
            ];
        }

        return view('cash_register.show', compact('openSession', 'summary'));
    }

    public function open(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (CashRegisterSession::openForUser($user->id)->exists()) {
            Alert::toast('Ya tienes una caja abierta.', 'warning');

            return redirect()->route('cash-register.show');
        }

        CashRegisterSession::create([
            'user_id' => $user->id,
            'opened_at' => now(),
        ]);

        Alert::toast('Caja abierta con éxito.', 'success');

        return redirect()->route('cash-register.show');
    }

    public function close(Request $request): RedirectResponse
    {
        $user = $request->user();
        $openSession = CashRegisterSession::openForUser($user->id)->first();
        if (! $openSession) {
            Alert::toast('No tienes una caja abierta.', 'error');

            return redirect()->route('cash-register.show');
        }

        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
        ]);

        $now = now();
        $parkingSum = ParkingSession::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('exit_time', [$openSession->opened_at, $now])
            ->sum('total_price');

        $parkingCount = ParkingSession::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('exit_time', [$openSession->opened_at, $now])
            ->count();

        $subsSum = Subscription::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$openSession->opened_at, $now])
            ->sum('price');

        $subsCount = Subscription::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$openSession->opened_at, $now])
            ->count();

        $openSession->update([
            'closed_at' => $now,
            'income_sum' => $parkingSum,
            'income_count' => $parkingCount,
            'subscriptions_sum' => $subsSum,
            'subscriptions_count' => $subsCount,
        ]);

        $totalIncome = $parkingSum + $subsSum;
        $totalCount = $parkingCount + $subsCount;

        Alert::toast('Caja cerrada. Total: $'.number_format($totalIncome, 2).' / Movimientos: '.$totalCount, 'success');

        return redirect()->route('cash-register.show');
    }
}
