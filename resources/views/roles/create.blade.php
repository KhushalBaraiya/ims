@extends('layouts.admin')
@section('title', __('messages.add_role_title'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_role_title') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.menu_roles') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.create_label') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-shield me-2 text-primary"></i>{{ __('messages.role_details_card') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('roles.store') }}">
                @include('roles.form')
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    @include('roles.scripts')
@endpush
