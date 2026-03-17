@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Gestión de Productos (Retail)</span>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('products.index') }}" method="GET" class="me-2">
                            <input type="search" name="search" class="form-control form-control-sm real-time-search" placeholder="Buscar..." value="{{ $search ?? '' }}">
                        </form>
                        <a href="{{ route('products.create') }}" class="btn btn-success btn-sm me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            Nuevo Producto</a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newSaleModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            Nueva Venta
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td>{{ $product->name }}</td>
                                    <td><small class="text-muted">{{ $product->description ?? 'N/A' }}</small></td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock > 5 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron productos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Venta -->
<div class="modal fade" id="newSaleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="retail">
                <div class="modal-header">
                    <p class="modal-title">Nueva Venta Retail</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="sale-items">
                        <!-- Items de venta se agregarán aquí -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="add-item-btn">
                        <i class="bi bi-plus-circle"></i> Agregar Producto
                    </button>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Método de Pago</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Efectivo</option>
                                <option value="card">Tarjeta</option>
                                <option value="transfer">Transferencia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control fw-bold" id="total-display" value="$0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submit-sale-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                        </svg>
                        Procesar Venta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let itemCounter = 0;
    const products = @json($products->items());

    document.getElementById('add-item-btn').addEventListener('click', function() {
        addSaleItem();
    });

    function addSaleItem() {
        itemCounter++;
        const itemHtml = `
            <div class="card mb-2 sale-item" id="item-${itemCounter}">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Producto</label>
                            <select name="products[${itemCounter}][product_id]" class="form-select product-select" required onchange="updateItemPrice(${itemCounter})">
                                <option value="">Seleccione un producto</option>
                                ${products.map(p => `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}" ${p.stock <= 0 ? 'disabled' : ''}>${p.name} - $${parseFloat(p.price).toFixed(2)} (Stock: ${p.stock})</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="products[${itemCounter}][quantity]" class="form-control quantity-input" min="1" value="1" required onchange="updateItemPrice(${itemCounter})">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control item-subtotal" id="subtotal-${itemCounter}" readonly value="$0.00">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm w-100 mt-1" onclick="removeItem(${itemCounter})">
                                <i class="bi bi-trash">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('sale-items').insertAdjacentHTML('beforeend', itemHtml);
        updateTotal();
    }

    function updateItemPrice(itemId) {
        const item = document.getElementById(`item-${itemId}`);
        const select = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const subtotalDisplay = document.getElementById(`subtotal-${itemId}`);

        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price || 0);
        const stock = parseInt(selectedOption.dataset.stock || 0);
        const quantity = parseInt(quantityInput.value || 0);

        // Validar stock
        if (quantity > stock) {
            alert(`Stock insuficiente. Disponible: ${stock}`);
            quantityInput.value = stock;
            return;
        }

        const subtotal = price * quantity;
        subtotalDisplay.value = `$${subtotal.toFixed(2)}`;

        updateTotal();
    }

    function removeItem(itemId) {
        document.getElementById(`item-${itemId}`).remove();
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-subtotal').forEach(input => {
            const value = parseFloat(input.value.replace('$', '')) || 0;
            total += value;
        });

        document.getElementById('total-display').value = `$${total.toFixed(2)}`;

        // Habilitar/deshabilitar botón de submit
        const hasItems = document.querySelectorAll('.sale-item').length > 0;
        document.getElementById('submit-sale-btn').disabled = !hasItems || total === 0;
    }

    // Agregar un item por defecto al abrir el modal
    document.getElementById('newSaleModal').addEventListener('shown.bs.modal', function() {
        if (document.querySelectorAll('.sale-item').length === 0) {
            addSaleItem();
        }
    });

    // Limpiar items al cerrar el modal
    document.getElementById('newSaleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('sale-items').innerHTML = '';
        itemCounter = 0;
        updateTotal();
    });
</script>
@endpush
