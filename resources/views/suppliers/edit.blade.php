@extends('layouts.admin')
@section('title', __('messages.edit_supplier') . ' — ' . $supplier->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_supplier') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('suppliers.index') }}">{{ __('messages.menu_suppliers') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.edit_label') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-group me-2 text-primary"></i>{{ __('messages.supplier_details') }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                @method('PUT')@include('suppliers.form')
            </form>
        </div>
    </div>
@endsection
