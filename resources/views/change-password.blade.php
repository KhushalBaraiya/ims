@extends('layouts.admin')
@section('title', __('messages.change_password'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.change_password') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">{{ __('messages.profile') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.change_password') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            {{-- Success Alert --}}
            @if (session('password_success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class="bx bx-check-circle fs-5"></i>
                    <span>{{ session('password_success') }}</span>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius:16px;overflow:hidden;">

                {{-- Card Header --}}
                <div class="py-4 px-4 text-white" style="background:linear-gradient(135deg,#696cff 0%,#9c3fe4 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:48px;height:48px;background:rgba(255,255,255,.2);">
                            <i class="bx bx-lock-alt text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">{{ __('messages.change_password') }}</h5>
                            <p class="mb-0 small" style="opacity:.75;">Update your account password</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- Password strength tips --}}
                    <div class="rounded-3 p-3 mb-4" style="background:#f8f9ff;border:1.5px solid #e0e4ff;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bx bx-shield-check text-primary mt-1" style="font-size:1.1rem;flex-shrink:0;"></i>
                            <div class="small text-muted">
                                <strong class="text-primary">Password Tips:</strong>
                                Use at least 8 characters with a mix of uppercase, lowercase, numbers and symbols.
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf

                        {{-- Current Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('messages.current_password') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bx bx-lock text-muted"></i>
                                </span>
                                <input type="password" name="current_password"
                                    class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                                    placeholder="Enter current password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('messages.new_password') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bx bx-key text-muted"></i>
                                </span>
                                <input type="password" name="password" id="newPassword"
                                    class="form-control border-start-0 @error('password') is-invalid @enderror"
                                    placeholder="Enter new password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Strength bar --}}
                            <div class="mt-2">
                                <div class="progress" style="height:4px;border-radius:4px;">
                                    <div class="progress-bar" id="strengthBar" role="progressbar"
                                        style="width:0%;transition:width .3s,background .3s;border-radius:4px;"></div>
                                </div>
                                <small class="text-muted" id="strengthLabel" style="font-size:.75rem;"></small>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('messages.confirm_password') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bx bx-check-shield text-muted"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                    class="form-control border-start-0" placeholder="Confirm new password" required>
                            </div>
                            <small class="d-none text-danger mt-1" id="matchWarning">
                                <i class="bx bx-x-circle me-1"></i>Passwords do not match
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn py-2 fw-semibold text-white"
                                style="background:linear-gradient(135deg,#696cff,#9c3fe4);border:none;border-radius:10px;">
                                <i class="bx bx-lock-open-alt me-2"></i>
                                {{ __('messages.change_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Password strength meter
        document.getElementById('newPassword').addEventListener('input', function() {
            const val = this.value;
            const bar = document.getElementById('strengthBar');
            const lbl = document.getElementById('strengthLabel');
            let strength = 0;
            if (val.length >= 8) strength++;
            if (/[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            const levels = [{
                    w: '0%',
                    bg: '',
                    text: ''
                },
                {
                    w: '25%',
                    bg: '#ef4444',
                    text: 'Weak'
                },
                {
                    w: '50%',
                    bg: '#f97316',
                    text: 'Fair'
                },
                {
                    w: '75%',
                    bg: '#eab308',
                    text: 'Good'
                },
                {
                    w: '100%',
                    bg: '#22c55e',
                    text: 'Strong'
                },
            ];
            bar.style.width = levels[strength].w;
            bar.style.background = levels[strength].bg;
            lbl.textContent = levels[strength].text;
        });

        // Confirm password match
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const match = this.value === document.getElementById('newPassword').value;
            document.getElementById('matchWarning').classList.toggle('d-none', match || !this.value);
        });
    </script>
@endpush
