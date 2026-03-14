@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Estado de Caja</span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-secondary">Inicio</a>
                </div>
                <div class="card-body">
                    @if (session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    @if ($openSession)
                        <p>Actualmente tienes una caja abierta desde <strong>{{ $openSession->opened_at->format('Y-m-d H:i') }}</strong>.</p>

                        @if ($summary)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-primary text-white">Ingresos de Parqueo</div>
                                        <div class="card-body text-primary">
                                            <h5 class="card-title">Total: ${{ number_format($summary['parking_sum'], 2) }}</h5>
                                            <p class="card-text">Cantidad: {{ $summary['parking_count'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-success mb-3">
                                        <div class="card-header bg-success text-white">Suscripciones</div>
                                        <div class="card-body text-success">
                                            <h5 class="card-title">Total: ${{ number_format($summary['subs_sum'], 2) }}</h5>
                                            <p class="card-text">Cantidad: {{ $summary['subs_count'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 text-center">
                                <h3>Total General: ${{ number_format($summary['income_sum'], 2) }}</h3>
                            </div>
                        @endif

                        <form action="{{ route('cash-register.close') }}" method="POST" data-confirm="¿Deseas cerrar la caja? Se registrarán los totales hasta este momento.">
                            @csrf
                            <div class="mb-3">
                                <label for="actual_amount" class="form-label">Total de dinero contado</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('actual_amount') is-invalid @enderror" id="actual_amount" name="actual_amount" required>
                                @error('actual_amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-danger">Cerrar Caja</button>
                        </form>
                    @else
                        <p>No tienes una caja abierta actualmente.</p>
                        <form action="{{ route('cash-register.open') }}" method="POST" data-confirm="¿Abrir una nueva caja?">
                            @csrf
                            <button type="submit" class="btn btn-primary">Abrir Caja</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
