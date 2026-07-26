@extends('admin.layout.app')

@section('title', 'Access Control')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Access Control</p>
                <p class="fs-13 text-muted mb-0">Roles and permissions. Admin always has full access.</p>
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

                @if (session('error'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>
                                        @if ($role->name === 'admin')
                                            <span class="badge bg-primary-transparent">All permissions</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $role->permissions_count }} permission(s)</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($role->name === 'admin')
                                            <span class="text-muted fs-12">Locked</span>
                                        @else
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-light btn-wave">
                                                <i class="ri-pencil-line align-middle"></i> Edit Permissions
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
