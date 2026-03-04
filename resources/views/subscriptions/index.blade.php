@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Suscripciones</span>
                </div>

                <div class="card-body">
                    <div class="card mb-4 bg-white">
                        <div class="card-header">Crear Nueva Suscripción</div>
                        <div class="card-body">
                            <form action="{{ route('subscriptions.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="vehicle_id" class="form-label">Vehículo</label>
                                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Seleccione un vehículo (debe tener dueño)</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} ({{ $vehicle->owner->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="months" class="form-label">Meses</label>
                                        <input type="number" class="form-control" id="months" name="months" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Suscribirse</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mb-3">
                        <form action="{{ route('subscriptions.index') }}" method="GET">
                            <input type="text" name="search" class="form-control real-time-search" placeholder="Buscar por placa o dueño..." value="{{ $search ?? '' }}">
                        </form>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
{{--                                <th>Type</th>--}}
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
                            @foreach ($subscriptions as $subscription)
                                <tr>
{{--                                    <td>{{ $subscriptions->vehicle->type }}</td>--}}
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
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
