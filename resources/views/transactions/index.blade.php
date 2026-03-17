@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Historial de Transacciones</span>
                    <form action="{{ route('transactions.index') }}" method="GET">
                        <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar..." value="{{ $search ?? '' }}">
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th># Transacción</th>
                                    <th>Fecha</th>
                                    <th>Items</th>
                                    <th>Método</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Cajero</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td><code>{{ $transaction->transaction_number }}</code></td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $transaction->items->count() }} {{ $transaction->items->count() == 1 ? 'item' : 'items' }}</span>
                                        </td>
                                        <td>{{ strtoupper($transaction->payment_method) }}</td>
                                        <td class="fw-normal text-primary">${{ number_format($transaction->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $transaction->status == 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->user->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="showTransactionDetails({{ $transaction->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                </svg>
                                                Ver
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles de Transacción -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Transacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="transaction-details-content">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTransactionDetails(transactionId) {
        const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
        const content = document.getElementById('transaction-details-content');

        // Mostrar loading
        content.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;

        modal.show();

        // Cargar datos
        fetch(`/transactions/${transactionId}`)
            .then(response => response.json())
            .then(transaction => {
                let itemsHtml = '';
                transaction.items.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td>${item.description}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">$${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td class="text-end fw-bold">$${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });

                content.innerHTML = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong># Transacción:</strong> <code>${transaction.transaction_number}</code></p>
                            <p><strong>Fecha:</strong> ${new Date(transaction.created_at).toLocaleString('es-ES')}</p>
                            <p><strong>Cajero:</strong> ${transaction.user.name}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Método de Pago:</strong> <span class="badge bg-primary">${transaction.payment_method.toUpperCase()}</span></p>
                            <p><strong>Estado:</strong> <span class="badge bg-success">${transaction.status.toUpperCase()}</span></p>
                            <p class="fs-4"><strong>Total:</strong> <span class="text-primary">$${parseFloat(transaction.total_amount).toFixed(2)}</span></p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Productos/Servicios</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                    <td class="text-end fw-bold text-primary">$${parseFloat(transaction.total_amount).toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar los detalles de la transacción. Por favor, inténtalo de nuevo.
                    </div>
                `;
            });
    }
</script>
@endpush
