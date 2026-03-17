@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Módulo de Lavadero</span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Registro de Lavado -->
                        <div class="col-md-4">
                            <div class="card bg-white">
                                <div class="card-header">Nuevo Servicio de Lavado</div>
                                <div class="card-body">
                                    <form action="{{ route('washes.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="wash_type_id" class="form-label">Tipo de Lavado</label>
                                            <select class="form-select" id="wash_type_id" name="wash_type_id" required>
                                                <option value="">Seleccione un tipo...</option>
                                                @foreach($washTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }} - {{ $type->vehicle_type }} (${{ number_format($type->price, 2) }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="plate" class="form-label">Placa del Vehículo (Opcional)</label>
                                            <input type="text" class="form-control" id="plate" name="plate" placeholder="ABC-123">
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-check" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                            </svg>
                                            Registrar y Cobrar</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Histórico de Lavados -->
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Servicios Recientes</h5>
                                <form action="{{ route('washes.index') }}" method="GET" class="ms-3">
                                    <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar por placa o servicio..." value="{{ $search ?? '' }}">
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Placa</th>
                                            <th>Tipo</th>
                                            <th>Fecha/Hora</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentWashes as $wash)
                                            <tr>
                                                <td><span class="badge bg-success-subtle text-black text-uppercase">{{ $wash->plate ?? 'N/A' }}</span></td>
                                                <td>{{ $wash->washType->name }}</td>
                                                <td><small>{{ $wash->completed_at->format('d/m/Y H:i') }}</small></td>
                                                <td>${{ number_format($wash->washType->price, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No hay lavados registrados recientemente.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $recentWashes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
