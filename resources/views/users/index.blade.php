@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Usuarios</span>
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Crear Nuevo Usuario</a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('users.index') }}" method="GET">
                            <input type="text" name="search" class="form-control real-time-search" placeholder="Buscar por nombre o email..." value="{{ $search ?? '' }}">
                        </form>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol Actual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ ucfirst($user->role?->name ?? 'Sin Rol') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Asignar Rol</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
