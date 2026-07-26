@extends('admin.layout.app')

@section('title', $customer->name)

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">{{ $customer->name }}</p>
                <p class="fs-13 text-muted mb-0">Customer purchase history & follow-up.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row gy-4">
            {{-- Profile & stats --}}
            <div class="col-xl-4">
                <div class="card custom-card mb-4">
                    <div class="card-header"><div class="card-title">Profile</div></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
                        <p class="mb-0"><strong>Address:</strong> {{ $customer->address ?? '-' }}</p>
                    </div>
                </div>

                <div class="card custom-card mb-4">
                    <div class="card-header"><div class="card-title">Purchase Stats</div></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Purchase frequency:</strong> {{ $customer->purchase_count }} order(s)</p>
                        <p class="mb-1"><strong>Last purchase:</strong> {{ $customer->last_purchase_at?->format('d M Y, h:i A') ?? 'Never' }}</p>
                        <p class="mb-0">
                            <strong>Status:</strong>
                            @if ($customer->isLost($days))
                                <span class="badge bg-danger-transparent">Lost (inactive {{ $days }}+ days)</span>
                            @else
                                <span class="badge bg-success-transparent">Active</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">Follow-up Assignment</div></div>
                    <div class="card-body">
                        @if ($customer->assignedEmployee)
                            <p class="mb-1"><strong>Assigned to:</strong> {{ $customer->assignedEmployee->name }}</p>
                            <p class="mb-3 text-muted fs-12">Since {{ $customer->assigned_at?->format('d M Y') }}</p>
                        @else
                            <p class="mb-3 text-muted">Not currently assigned.</p>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <form action="{{ route('customers.assign', $customer) }}" method="POST" class="d-flex gap-2 mb-2">
                                @csrf
                                <select name="employee_id" class="form-control form-control-sm" required>
                                    <option value="">Select employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" @selected($customer->assigned_employee_id === $employee->id)>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary btn-wave">Assign</button>
                            </form>

                            @if ($customer->assignedEmployee)
                                <form action="{{ route('customers.unassign', $customer) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light text-danger btn-wave">Unassign</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Orders & re-engagement --}}
            <div class="col-xl-8">
                <div class="card custom-card mb-4">
                    <div class="card-header"><div class="card-title">Order History</div></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customer->orders as $order)
                                        <tr>
                                            <td>
                                                @if (auth()->user()->isAdmin())
                                                    <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                                @else
                                                    {{ $order->order_number }}
                                                @endif
                                            </td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>{{ number_format($order->total_amount, 2) }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No orders yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card custom-card mb-4">
                    <div class="card-header"><div class="card-title">Send Re-engagement Message</div></div>
                    <div class="card-body">
                        <form action="{{ route('customers.reengage', $customer) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Channel</label>
                                    <select name="channel" class="form-control" required>
                                        <option value="email">Email</option>
                                        <option value="sms">SMS</option>
                                    </select>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" rows="2" class="form-control" placeholder="e.g. We miss you! Here's 10% off your next order." required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-send-plane-line align-middle me-1"></i> Send (simulated)
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">Contact Log</div></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse ($customer->contactLogs as $log)
                                <li class="list-group-item">
                                    <span class="badge bg-light text-dark">{{ strtoupper($log->channel) }}</span>
                                    {{ $log->message }}
                                    <div class="fs-11 text-muted">by {{ $log->sender->name ?? '-' }} on {{ $log->created_at->format('d M Y, h:i A') }}</div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">No contact history yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
