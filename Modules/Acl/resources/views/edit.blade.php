@extends('admin.layout.app')

@section('title', 'Edit Role Permissions')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">{{ ucfirst($role->name) }} Permissions</p>
                <p class="fs-13 text-muted mb-0">Choose what the {{ $role->name }} role is allowed to do.</p>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back
            </a>
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row gy-3">
                        @foreach ($permissionsByModule as $module => $permissions)
                            <div class="col-md-4">
                                <p class="fw-medium mb-2">{{ $module }}</p>
                                @foreach ($permissions as $permission)
                                    <div class="form-check mb-1">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            id="perm-{{ $permission->id }}"
                                            class="form-check-input"
                                            {{ in_array($permission->name, $assigned) ? 'checked' : '' }}
                                        >
                                        <label for="perm-{{ $permission->id }}" class="form-check-label">
                                            {{ str($permission->name)->after('.')->headline() }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary btn-wave mt-4">
                        <i class="ri-save-line align-middle me-1"></i> Save Permissions
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
