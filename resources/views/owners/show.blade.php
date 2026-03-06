@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow bg-white mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalles del Dueño</span>
                    <div>
                        <a href="{{ route('owners.edit', $owner) }}" class="btn btn-sm btn-warning">Editar</a>
                        <a href="{{ route('owners.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Nombre:</strong></div>
                        <div class="col-md-8">{{ $owner->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $owner->email ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Teléfono:</strong></div>
                        <div class="col-md-8">{{ $owner->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow bg-white">
                <div class="card-header">Vehículos ({{ $owner->vehicles->count() }})</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Model</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($owner->vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->plate }}</td>
                                    <td>{{ $vehicle->model ?? 'N/A' }}</td>
                                    <td>{{ $vehicle->type == 'car' ? 'Carro' : 'Moto' }}</td>
                                    <td>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" data-confirm="¿Deseas eliminar este vehículo? Esta acción no se puede deshacer.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No se encontraron vehículos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
