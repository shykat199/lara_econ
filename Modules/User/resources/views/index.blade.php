@extends('admin.layout.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Users</p>
                <p class="fs-13 text-muted mb-0">Manage admin and customer accounts.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-wave">
                <i class="ri-add-line align-middle me-1"></i> Add User
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        @if ($user->avatar)
                                            <img
                                                src="{{ asset(\Illuminate\Support\Facades\Storage::url($user->avatar)) }}"
                                                alt="{{ $user->name }}"
                                                class="rounded-circle border"
                                                style="width: 36px; height: 36px; object-fit: cover;"
                                            >
                                        @else
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center bg-light text-muted" style="width: 36px; height: 36px;">
                                                <i class="ri-user-line"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ ['admin' => 'bg-primary-transparent', 'employee' => 'bg-warning-transparent'][$user->role] ?? 'bg-secondary-transparent' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success-transparent' : 'bg-danger-transparent' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-icon btn-light" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
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
                                    <td colspan="7" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
