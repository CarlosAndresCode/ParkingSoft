@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-white shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Panel de Parqueo</span>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            <span>Historial</span>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class=" col-md-4">
                                <div class="card bg-white">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Ingreso de Vehículo</span>
                                    </div>
                                    <div class="card-body">
                                        <form id="check-in-form" action="{{ route('parking.check-in') }}" method="POST" target="_blank">
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
                                            <button type="submit" class="btn btn-primary w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                                </svg>
                                                Registrar Ingreso</button>
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
                                <table class="table table-sm table-hover">
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                                    </svg>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Historial -->
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="card bg-white shadow">
                    <div class="card-header align-items-center">
                        Ingresos Recientes Completadas
                    </div>
                    <div class="card-body">
                        <span class="fst-italic text-danger">Solo se muestran los ingresos del dia de hoy</span>
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
                                        <td>{{ $session->entry_time }}</td>
                                        <td>{{ $session->exit_time }}</td>
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
