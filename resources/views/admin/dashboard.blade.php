@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- Page header --}}
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Dashboard</p>
                <p class="fs-13 text-muted mb-0">Overview of your content and media library.</p>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="row gy-4">
            @can('products.view')
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fs-13 mb-1">Products</p>
                                <p class="fw-semibold fs-20 mb-0">{{ number_format($stats['products']) }}</p>
                            </div>
                            <span class="avatar avatar-md bg-primary-transparent">
                                <i class="ri-shopping-bag-3-line fs-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endcan

            @can('categories.view')
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fs-13 mb-1">Categories</p>
                                <p class="fw-semibold fs-20 mb-0">{{ number_format($stats['categories']) }}</p>
                            </div>
                            <span class="avatar avatar-md bg-secondary-transparent">
                                <i class="ri-price-tag-3-line fs-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endcan

            @can('orders.view')
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fs-13 mb-1">Orders</p>
                                <p class="fw-semibold fs-20 mb-0">{{ number_format($stats['orders']) }}</p>
                            </div>
                            <span class="avatar avatar-md bg-warning-transparent">
                                <i class="ri-shopping-cart-2-line fs-20"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fs-13 mb-1">Revenue (completed)</p>
                                <p class="fw-semibold fs-20 mb-0">{{ number_format($stats['revenue'], 2) }}</p>
                            </div>
                            <span class="avatar avatar-md bg-success-transparent">
                                <i class="ri-money-dollar-circle-line fs-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endcan

            @can('customers.view')
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fs-13 mb-1">{{ auth()->user()->isEmployee() ? 'My Customers' : 'Customers' }}</p>
                                <p class="fw-semibold fs-20 mb-0">{{ number_format($stats['customers']) }}</p>
                            </div>
                            <span class="avatar avatar-md bg-info-transparent">
                                <i class="ri-group-line fs-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        @can('orders.view')
            <div class="row gy-4 mt-1">
                {{-- Order status breakdown --}}
                <div class="col-xl-4">
                    <div class="card custom-card h-100">
                        <div class="card-header">
                            <div class="card-title">Orders by Status</div>
                        </div>
                        <div class="card-body">
                            @forelse (['pending', 'processing', 'completed', 'cancelled'] as $status)
                                @php
                                    $badgeColor = match ($status) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-{{ $badgeColor }}-transparent text-capitalize">{{ $status }}</span>
                                    <span class="fw-medium">{{ number_format($orderStatusCounts->get($status, 0)) }}</span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No orders yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Recent orders --}}
                <div class="col-xl-8">
                    <div class="card custom-card h-100">
                        <div class="card-header">
                            <div class="card-title">Recent Orders</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name ?? '—' }}</td>
                                                <td class="text-capitalize">{{ $order->status }}</td>
                                                <td>{{ number_format($order->total_amount, 2) }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No orders yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

    </div>
@endsection
