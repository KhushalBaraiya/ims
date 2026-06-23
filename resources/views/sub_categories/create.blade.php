@extends('layouts.admin')
@section('title', __('messages.add_sub_category'))
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_sub_category') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('sub-categories.index') }}">{{ __('messages.sub_categories') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-category me-2 text-primary"></i>{{ __('messages.sub_category_details') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('sub-categories.store') }}">@include('sub_categories.form')</form>
        </div>
    </div>
@endsection
