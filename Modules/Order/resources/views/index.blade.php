@extends('admin.layout.app')

@section('title', 'Orders')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Orders</p>
                <p class="fs-13 text-muted mb-0">Customer purchase records.</p>
            </div>
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-wave">
                <i class="ri-add-line align-middle me-1"></i> New Order
            </a>
        </div>

        <div class="card custom-card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ [
                                            'completed' => 'bg-success-transparent',
                                            'processing' => 'bg-info-transparent',
                                            'pending' => 'bg-warning-transparent',
                                            'cancelled' => 'bg-danger-transparent',
                                        ][$order->status] ?? 'bg-secondary-transparent' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-icon btn-light" title="View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this order? Stock will be restored.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light text-danger" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
