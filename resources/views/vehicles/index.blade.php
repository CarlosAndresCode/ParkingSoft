@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Vehículos</span>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-primary">Registrar Nuevo Vehículo</a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('vehicles.index') }}" method="GET">
                            <input type="text" name="search" class="form-control real-time-search" placeholder="Buscar por placa, modelo o dueño..." value="{{ $search ?? '' }}">
                        </form>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tipo</th>
                                <th>Dueño</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->plate }}</td>
                                    <td>{{ $vehicle->vehicle->brand->name ?? 'N/A' }}</td>
                                    <td>{{ $vehicle->model ?? 'N/A' }}</td>
                                    <td>{{ $vehicle->type == 'car' ? 'Carro' : 'Moto' }}</td>
                                    <td>{{ $vehicle->owner->name ?? 'Ninguno (Invitado)' }}</td>
                                    <td>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" data-confirm="¿Deseas eliminar este vehículo? Esta acción no se puede deshacer.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $vehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
