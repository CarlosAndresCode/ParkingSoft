@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-1 rounded-1">
                <div class="card-header bg-white border-1 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>{{ __($title) }}</h5>
                </div>

                <div class="card-body bg-light-subtle">
                    <div class="row g-4 mb-5">
                        <div class="col-md-2">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                                <div class="card-body p-3 text-center">
                                    <div class="fs-7 opacity-75 mb-1 text-uppercase fw-bold ls-1">Ingresos Día</div>
                                    <div class="fs-4 fw-bold">${{ number_format($dailyEarnings, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-success">
                                <div class="card-body p-3 text-center">
                                    <div class="fs-7 text-muted mb-1 text-uppercase fw-bold ls-1">Carros</div>
                                    <div class="fs-4 fw-bold text-success">${{ number_format($carEarnings, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-info">
                                <div class="card-body p-3 text-center">
                                    <div class="fs-7 text-muted mb-1 text-uppercase fw-bold ls-1">Motos</div>
                                    <div class="fs-4 fw-bold text-info">${{ number_format($motorcycleEarnings, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-warning">
                                <div class="card-body p-3 text-center">
                                    <div class="fs-7 text-muted mb-1 text-uppercase fw-bold ls-1">Mensualidades</div>
                                    <div class="fs-4 fw-bold text-warning">{{ $activeSubscriptions }}</div>
                                    <div class="extra-small text-muted">Activas</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-danger">
                                <div class="card-body p-3 text-center">
                                    <div class="fs-7 text-muted mb-1 text-uppercase fw-bold ls-1">En Parqueadero</div>
                                    <div class="fs-4 fw-bold text-danger">{{ $activeSessionsCount }}</div>
                                    <div class="extra-small text-muted">Vehículos hoy</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                                <h6 class="fw-bold mb-4 text-secondary">Ingresos de los últimos 10 días</h6>
                                <div style="height: 350px;">
                                    <canvas id="earningsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('earningsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Ingresos ($)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1rem !important; }
    .fs-7 { font-size: 0.8rem; }
    .extra-small { font-size: 0.7rem; }
</style>
@endsection
