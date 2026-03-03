@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Roles</span>
                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Crear Nuevo Rol</a>
                </div>

                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuarios</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>{{ $role->users_count }}</td>
                                    <td>
                                        <span class="badge {{ $role->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $role->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('roles.toggle', $role) }}" method="POST" class="d-inline" data-confirm="¿Deseas cambiar el estado de este rol?">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $role->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ $role->is_active ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
