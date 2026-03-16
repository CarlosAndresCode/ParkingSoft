@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Suscripciones</span>
                    <form action="{{ route('subscriptions.index') }}" method="GET" class="ms-3">
                        <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar..." value="{{ $search ?? '' }}">
                    </form>
                </div>

                <div class="card-body">
                    <div class="card mb-4 bg-white border-primary shadow-sm">
                        <div class="card-header bg-primary text-white py-1">Crear Nueva Suscripción</div>
                        <div class="card-body py-2">
                            <form action="{{ route('subscriptions.store') }}" method="POST">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5">
                                        <label for="vehicle_id" class="form-label small mb-0">Vehículo</label>
                                        <select class="form-select form-select-sm" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Seleccione un vehículo (debe tener dueño)</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} ({{ $vehicle->owner->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="months" class="form-label small mb-0">Meses</label>
                                        <input type="number" class="form-control form-control-sm" id="months" name="months" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Suscribirse</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Dueño</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->vehicle->plate }}</td>
                                        <td>{{ $subscription->vehicle->owner->name ?? 'Ninguno (Invitado)'}}</td>
                                        <td>{{ $subscription->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                        <td>${{ number_format($subscription->price, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $subscription->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $subscription->status == 'active' ? 'Activa' : ($subscription->status == 'expired' ? 'Expirada' : 'Cancelada') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($subscription->status == 'active')
                                                <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" data-confirm="¿Cancelar esta suscripción? Se perderá el tiempo restante.">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                        </svg>
                                                        Cancelar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encontraron suscripciones.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
