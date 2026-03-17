@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Roles</span>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('roles.index') }}" method="GET" class="me-2">
                            <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar..." value="{{ $search ?? '' }}">
                        </form>
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            Nuevo Rol</a>
                    </div>
                </div>

                <div class="card-body">
                        <table class="table table-responsive table-sm table-hover ">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuarios</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ ucfirst($role->name) }}</td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>
                                            <span class="badge {{ $role->is_active ? 'bg-success-subtle' : 'bg-danger-subtle' }} text-dark">
                                                {{ $role->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                                </svg>
                                                {{ __('Edit') }}</a>
                                            <form action="{{ route('roles.toggle', $role) }}" method="POST" class="d-inline" data-confirm="¿Deseas cambiar el estado de este rol?">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $role->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    @php
                                                     if ($role->is_active) {
                                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                                                              <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                                            </svg>' . ' Desactivar' ;
                                                     } else {
                                                         echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                                                              <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                                            </svg>' . ' Activar' ;
                                                     }
                                                    @endphp
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron roles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
