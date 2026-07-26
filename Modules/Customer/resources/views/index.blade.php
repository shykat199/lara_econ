@extends('admin.layout.app')

@section('title', $lostOnly ? 'Lost Customers' : 'Customers')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">
                    @if (auth()->user()->isEmployee())
                        My Assigned Customers
                    @else
                        {{ $lostOnly ? 'Lost Customers' : 'Customers' }}
                    @endif
                </p>
                <p class="fs-13 text-muted mb-0">
                    @if (auth()->user()->isEmployee())
                        Follow up with customers assigned to you. Your KPI score: <strong>{{ auth()->user()->kpi_score }}</strong>
                    @else
                        Purchase history, lost-customer detection, and follow-up assignment.
                    @endif
                </p>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @unless (auth()->user()->isEmployee())
                    <form action="{{ route('customers.index') }}" method="GET" class="d-flex align-items-end gap-2 flex-wrap mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="lost" value="1" id="lost" class="form-check-input" {{ $lostOnly ? 'checked' : '' }} onchange="this.form.submit()">
                            <label for="lost" class="form-check-label">Show only lost customers</label>
                        </div>
                        <div>
                            <label for="days" class="form-label mb-0 fs-12">Inactive for (days)</label>
                            <input type="number" name="days" id="days" value="{{ $days }}" min="1" class="form-control form-control-sm" style="width: 100px;">
                        </div>
                        <button type="submit" class="btn btn-sm btn-light btn-wave">Apply</button>
                    </form>
                @endunless

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Purchases</th>
                                <th>Last Purchase</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->purchase_count }}</td>
                                    <td>{{ $customer->last_purchase_at?->format('d M Y') ?? 'Never' }}</td>
                                    <td>
                                        @if ($customer->isLost($days))
                                            <span class="badge bg-danger-transparent">Lost</span>
                                        @else
                                            <span class="badge bg-success-transparent">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $customer->assignedEmployee->name ?? '-' }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-light btn-wave">
                                            <i class="ri-eye-line align-middle"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No customers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
