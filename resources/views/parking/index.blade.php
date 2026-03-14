@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Panel de Parqueo</span>
                </div>

                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-sm-12 col-md-4 ">
                            <div class="card bg-white">
                                <div class="card-header">Ingreso de Vehículo</div>
                                <div class="card-body">
                                    <form action="{{ route('parking.check-in') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="plate" class="form-label">Número de Placa</label>
                                            <input type="text" class="form-control" id="plate" name="plate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Tipo de Vehículo</label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="car">Carro</option>
                                                <option value="motorcycle">Motocicleta</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">Registrar Ingreso</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="mb-0">Sesiones Activas</h4>
                                <form action="{{ route('parking.index') }}" method="GET" class="ms-3">
                                    <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar sesiones activas..." value="{{ $search ?? '' }}">
                                </form>
                            </div>
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Tipo</th>
                                    <th>Dueño</th>
                                    <th>Hora Entrada</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($activeSessions as $session)
                                    <tr>
                                        <td>{{ $session->vehicle->plate }}</td>
                                        <td>{{ $session->vehicle->type == 'car' ? 'Carro' : 'Moto' }}</td>
                                        <td>{{ $session->vehicle->owner->name ?? 'Invitado' }}</td>
                                        <td>{{ $session->entry_time->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-check-out"
                                                    data-session-id="{{ $session->id }}"
                                                    data-plate="{{ $session->vehicle->plate }}">
                                                Registrar Salida
                                            </button>
                                            <form id="checkout-form-{{ $session->id }}" action="{{ route('parking.check-out', $session) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay sesiones activas</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $activeSessions->links() }}
                        </div>
                    </div>

                    <hr>

                    <h4>Sesiones Recientes Completadas</h4>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Precio Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td>{{ $session->vehicle->plate }}</td>
                                    <td>{{ $session->entry_time->format('H:i') }}</td>
                                    <td>{{ $session->exit_time->format('H:i') }}</td>
                                    <td>${{ number_format($session->total_price, 2) }}</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-check-out');

        buttons.forEach(button => {
            button.addEventListener('click', async function () {
                const sessionId = this.getAttribute('data-session-id');
                const plate = this.getAttribute('data-plate');

                Swal.fire({
                    title: 'Calculando precio...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(`/parking/calculate-price/${sessionId}`);
                    const data = await response.json();

                    Swal.fire({
                        title: `Confirmar Salida - ${plate}`,
                        html: `El valor a pagar es: <strong>$${data.formatted_price}</strong>`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Confirmar Salida',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`checkout-form-${sessionId}`).submit();
                        }
                    });
                } catch (error) {
                    Swal.fire('Error', 'No se pudo calcular el precio', 'error');
                }
            });
        });
    });
</script>
@endpush
