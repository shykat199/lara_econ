@extends('admin.layout.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Order {{ $order->order_number }}</p>
                <p class="fs-13 text-muted mb-0">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back
            </a>
        </div>

        <div class="row gy-4">
            <div class="col-xl-4">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <div class="card-title">Customer</div>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 fw-medium">{{ $order->user->name ?? '-' }}</p>
                        <p class="mb-1 text-muted">{{ $order->user->email ?? '-' }}</p>
                        <p class="mb-0 text-muted">{{ $order->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card custom-card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title">Order Summary</div>
                        <span class="badge {{ [
                            'completed' => 'bg-success-transparent',
                            'processing' => 'bg-info-transparent',
                            'pending' => 'bg-warning-transparent',
                            'cancelled' => 'bg-danger-transparent',
                        ][$order->status] ?? 'bg-secondary-transparent' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                                            <td>{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th>{{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if ($order->notes)
                            <div class="mt-3">
                                <p class="fw-medium mb-1">Notes</p>
                                <p class="text-muted mb-0">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
