@csrf

{{-- Profile Photo --}}
<div class="mb-4 d-flex align-items-center gap-3">
    <div class="flex-shrink-0">
        @if (isset($user) && $user->profile_photo)
            <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}" class="rounded-circle"
                style="width:64px;height:64px;object-fit:cover;border:2px solid #e0e0e0;">
        @else
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                style="width:64px;height:64px;">
                <i class="bx bx-user fs-4 text-primary"></i>
            </div>
        @endif
    </div>
    <div>
        <label class="form-label fw-semibold mb-1">Profile Photo</label>
        <input type="file" name="profile_photo"
            class="form-control form-control-sm @error('profile_photo') is-invalid @enderror"
            accept="image/jpeg,image/png,image/gif">
        <div class="form-text">JPG, PNG, GIF — max 2MB</div>
        @error('profile_photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name ?? '') }}" placeholder="John Doe" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}" placeholder="john@company.com" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $user->phone ?? '') }}" placeholder="+1234567890">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Select Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}"
                    {{ old('role', isset($user) ? $user->roles->first()?->name : '') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>
                Active</option>
            <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>
                Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Password
            {{ isset($user) ? '<small class="text-muted fw-normal">(leave blank to keep)</small>' : '<span class="text-danger">*</span>' }}
        </label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="••••••••" {{ !isset($user) ? 'required' : '' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Confirm Password
            {{ isset($user) ? '' : '<span class="text-danger">*</span>' }}</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
            {{ !isset($user) ? 'required' : '' }}>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($user) ? 'Update User' : 'Save User' }}
    </button>
</div>
