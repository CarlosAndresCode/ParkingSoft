<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashRegisterOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        // Solo aplica a cajeros
        if ($user->isCashier() || $user->isAdmin()) {
            $hasOpen = \App\Models\CashRegisterSession::openForUser($user->id)->exists();
            if (! $hasOpen) {
                return redirect()->route('cash-register.show')
                    ->with('warning', 'Debes abrir caja antes de continuar.');
            }
        }

        return $next($request);
    }
}
