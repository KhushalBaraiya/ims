@extends('layouts.admin')
@section('title', __('messages.add_brand'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_brand') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">{{ __('messages.menu_brands') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('brands.store') }}" id="brandForm">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i
                                class="bx bx-award me-2 text-primary"></i>{{ __('messages.brand_details_card') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        @include('brands.form')
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('brands.publish')
            </div>
        </div>
    </form>

@endsection
