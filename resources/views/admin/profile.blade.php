@extends('admin.layout.app')

@section('title', 'My Profile')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">My Profile</p>
                <p class="fs-13 text-muted mb-0">Update your name, photo and password.</p>
            </div>
        </div>

        <div class="row gy-4">

            {{-- Profile Info Card --}}
            <div class="col-xl-6">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <div class="card-title">Profile Information</div>
                    </div>
                    <div class="card-body">

                        @if (session('info_success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('info_success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.info') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Avatar --}}
                            <div class="mb-4 text-center">
                                <div class="d-inline-block position-relative" id="avatarWrapper">
                                    <img
                                        id="avatarPreview"
                                        src="{{ $user->avatar ? asset(\Illuminate\Support\Facades\Storage::url($user->avatar)) : asset('build/assets/images/faces/14.jpg') }}"
                                        alt="{{ $user->name }}"
                                        class="rounded-circle border"
                                        style="width: 110px; height: 110px; object-fit: cover;"
                                    >
                                    <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1" style="width:30px;height:30px;cursor:pointer;" title="Change photo">
                                        <i class="ri-camera-line"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/jpeg,image/png,image/webp">
                                </div>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <p class="text-muted fs-12 mt-2 mb-0">JPG, PNG or WEBP — max 2MB</p>
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email (read-only) --}}
                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                                <small class="text-muted">Email cannot be changed.</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line align-middle me-1"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Change Password Card --}}
            <div class="col-xl-6">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <div class="card-title">Change Password</div>
                    </div>
                    <div class="card-body">

                        @if (session('password_success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('password_success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" autocomplete="current-password">
                                    <button class="btn btn-light border toggle-password" type="button" data-target="current_password">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password" autocomplete="new-password">
                                    <button class="btn btn-light border toggle-password" type="button" data-target="password">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Minimum 8 characters.</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat new password" autocomplete="new-password">
                                    <button class="btn btn-light border toggle-password" type="button" data-target="password_confirmation">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-wave">
                                <i class="ri-lock-password-line align-middle me-1"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        // Avatar live preview
        document.getElementById('avatar').addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                alert('Please upload a JPG, PNG, or WEBP image.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(file);
        });

        // Password visibility toggles
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                const icon  = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
                } else {
                    input.type = 'password';
                    icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
                }
            });
        });
    </script>
@endpush
