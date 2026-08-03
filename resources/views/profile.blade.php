@extends('layouts.admin')
@section('title', __('messages.my_profile'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.my_profile') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.profile') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4 col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-user-circle me-2 text-primary"></i>{{ __('messages.profile_overview') }}</h6>
                </div>
                <div class="card-body p-4 text-center">
                    @if ($user->profile_photo)
                        <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}"
                            class="rounded-circle mb-3 shadow-sm"
                            style="width:80px;height:80px;object-fit:cover;border:3px solid #e0e0e0;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary mx-auto mb-3"
                            style="width:80px;height:80px;">
                            <span class="fw-bold text-primary" style="font-size:2rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <span class="badge bg-label-primary">{{ $user->roles->pluck('name')->implode(', ') ?: 'Staff' }}</span>

                    <hr class="my-3">

                    <ul class="list-unstyled text-start mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.phone_label') }}</span>
                            <span class="small fw-bold">{{ $user->phone ?: __('messages.not_set') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.member_since') }}</span>
                            <span class="small">{{ $user->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.last_login') }}</span>
                            <span
                                class="small">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</span>
                        </li>
                        <li class="pt-3">
                            <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning w-100">
                                <i class="bx bx-lock-alt me-1"></i> {{ __('messages.change_password') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-8">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-edit me-2 text-primary"></i>{{ __('messages.profile_information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.th_email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.phone_label') }}</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" placeholder="{{ __('messages.ph_profile_phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.profile_photo') }}</label>
                                <input type="file" name="profile_photo"
                                    class="form-control @error('profile_photo') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/gif">
                                <div class="form-text">{{ __('messages.image_hint') }}</div>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
