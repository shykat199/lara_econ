@extends('admin.layout.app')

@section('title', 'New Order')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">New Order</p>
                <p class="fs-13 text-muted mb-0">Record a customer purchase.</p>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back
            </a>
        </div>

        <div class="row">
            <div class="col-xl-9">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">Customer</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Select a customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" @selected(old('user_id') == $customer->id)>
                                                {{ $customer->name }} ({{ $customer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        @foreach (['completed' => 'Completed', 'processing' => 'Processing', 'pending' => 'Pending', 'cancelled' => 'Cancelled'] as $value => $label)
                                            <option value="{{ $value }}" @selected(old('status', 'completed') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-3">

                            <label class="form-label">Items</label>
                            <div id="item-rows"></div>

                            <button type="button" id="add-row" class="btn btn-sm btn-light btn-wave mb-3">
                                <i class="ri-add-line align-middle me-1"></i> Add Item
                            </button>

                            @error('products')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <div class="text-end fs-16 fw-medium mb-3">
                                Total: <span id="order-total">0.00</span>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line align-middle me-1"></i> Save Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $productOptions = $products->map(fn ($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'price' => (float) $p->price,
        'stock' => $p->stock_quantity,
    ]);
@endphp

@push('js')
    <script>
        (function () {
            const products = @json($productOptions);
            const rowsContainer = document.getElementById('item-rows');
            const totalEl = document.getElementById('order-total');
            let rowIndex = 0;

            function productOptions(selected) {
                return products.map(p =>
                    `<option value="${p.id}" data-price="${p.price}" ${String(p.id) === String(selected) ? 'selected' : ''}>${p.name} (${p.price.toFixed(2)}, stock: ${p.stock})</option>`
                ).join('');
            }

            function addRow() {
                const index = rowIndex++;
                const row = document.createElement('div');
                row.className = 'row align-items-end mb-2 item-row';
                row.innerHTML = `
                    <div class="col-md-6">
                        <select name="products[]" class="form-control product-select" required>
                            <option value="">Select a product</option>
                            ${productOptions('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="quantities[]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <span class="form-control-plaintext row-subtotal">0.00</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-icon btn-light text-danger remove-row"><i class="ri-close-line"></i></button>
                    </div>
                `;
                rowsContainer.appendChild(row);
                row.querySelector('.product-select').addEventListener('change', recalculate);
                row.querySelector('.quantity-input').addEventListener('input', recalculate);
                row.querySelector('.remove-row').addEventListener('click', () => {
                    row.remove();
                    recalculate();
                });
            }

            function recalculate() {
                let total = 0;

                rowsContainer.querySelectorAll('.item-row').forEach(row => {
                    const select = row.querySelector('.product-select');
                    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
                    const option = select.options[select.selectedIndex];
                    const price = option ? parseFloat(option.dataset.price || 0) : 0;
                    const subtotal = price * qty;
                    row.querySelector('.row-subtotal').textContent = subtotal.toFixed(2);
                    total += subtotal;
                });

                totalEl.textContent = total.toFixed(2);
            }

            document.getElementById('add-row').addEventListener('click', addRow);
            addRow();
        })();
    </script>
@endpush
