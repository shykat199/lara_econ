@php $user = $user ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" {{ $user ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">{{ $user ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.' }}</small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
    </div>

    <div class="col-md-4 mb-3">
        <label for="role" class="form-label">Role</label>
        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
            @foreach (['admin' => 'Admin', 'employee' => 'Employee', 'customer' => 'Customer'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user?->role ?? 'customer') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $user?->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user?->phone) }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user?->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="avatar" class="form-label">Photo</label>
        <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
        @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">JPG, PNG or WEBP — max 2MB.</small>

        @if ($user?->avatar)
            <div class="mt-2">
                <img src="{{ asset(\Illuminate\Support\Facades\Storage::url($user->avatar)) }}" alt="{{ $user->name }}" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
            </div>
        @endif
    </div>
</div>
