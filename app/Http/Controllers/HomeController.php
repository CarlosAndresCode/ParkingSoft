<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Subscription;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index(): Renderable
    {
        $today = Carbon::today();

        // Ingresos del día totales
        $dailyEarnings = ParkingSession::whereDate('exit_time', $today)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('total_price');

        // Ingresos del día por carros
        $carEarnings = ParkingSession::whereDate('exit_time', $today)
            ->whereIn('status', ['paid', 'completed'])
            ->whereHas('vehicle', function ($query) {
                $query->where('type', 'car');
            })
            ->sum('total_price');

        // Ingresos del día por motos
        $motorcycleEarnings = ParkingSession::whereDate('exit_time', $today)
            ->whereIn('status', ['paid', 'completed'])
            ->whereHas('vehicle', function ($query) {
                $query->where('type', 'motorcycle');
            })
            ->sum('total_price');

        // Mensualidades activas
        $activeSubscriptions = Subscription::where('status', 'active')
//            ->where('start_date', '<=', $today)
//            ->where('end_date', '>=', $today)
            ->count();

        // Vehículos en el parqueadero actualmente
        $activeSessionsCount = ParkingSession::where('status', 'active')->count();

        // Datos para el gráfico (últimos 10 días)
        $last10Days = collect();
        for ($i = 9; $i >= 0; $i--) {
            $last10Days->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        $chartData = ParkingSession::whereIn('status', ['paid', 'completed'])
            ->whereDate('exit_time', '>=', Carbon::today()->subDays(9))
            ->select(
                DB::raw('DATE(exit_time) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // Aseguramos que todos los días tengan un valor (aunque sea 0)
        $chartValues = $last10Days->map(function ($date) use ($chartData) {
            return $chartData->get($date, 0);
        });

        $chartLabels = $last10Days->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        $title = 'Dashboard';

        return view('home', compact(
            'title',
            'dailyEarnings',
            'carEarnings',
            'motorcycleEarnings',
            'activeSubscriptions',
            'activeSessionsCount',
            'chartLabels',
            'chartValues'
        ));
    }
}
