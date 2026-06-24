@extends('layouts.admin')
@section('title', __('messages.menu_settings'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.settings') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_settings') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Company Information --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-building me-2 text-primary"></i>{{ __('messages.company_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.company_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="company_name"
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name', $settings->get('company_name')) }}"
                                placeholder="e.g. My Company Ltd." required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.email_address') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="company_email"
                                    class="form-control @error('company_email') is-invalid @enderror"
                                    value="{{ old('company_email', $settings->get('company_email')) }}"
                                    placeholder="admin@example.com" required>
                                @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.phone_label') }}</label>
                                <input type="text" name="company_phone"
                                    class="form-control @error('company_phone') is-invalid @enderror"
                                    value="{{ old('company_phone', $settings->get('company_phone')) }}"
                                    placeholder="+1 234 567 8900">
                                @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
                            <textarea name="company_address" rows="3" class="form-control @error('company_address') is-invalid @enderror"
                                placeholder="Full company address...">{{ old('company_address', $settings->get('company_address')) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Invoice & Tax Settings --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-receipt me-2 text-warning"></i>{{ __('messages.invoice_tax_settings') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.invoice_prefix') }}</label>
                                <input type="text" name="invoice_prefix"
                                    class="form-control @error('invoice_prefix') is-invalid @enderror"
                                    value="{{ old('invoice_prefix', $settings->get('invoice_prefix')) }}"
                                    placeholder="e.g. INV">
                                <div class="form-text">{{ __('messages.invoice_prefix_hint') }}</div>
                                @error('invoice_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.purchase_prefix') }}</label>
                                <input type="text" name="purchase_prefix"
                                    class="form-control @error('purchase_prefix') is-invalid @enderror"
                                    value="{{ old('purchase_prefix', $settings->get('purchase_prefix')) }}"
                                    placeholder="e.g. PO">
                                <div class="form-text">{{ __('messages.purchase_prefix_hint') }}</div>
                                @error('purchase_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.tax_name') }}</label>
                                <input type="text" name="tax_name"
                                    class="form-control @error('tax_name') is-invalid @enderror"
                                    value="{{ old('tax_name', $settings->get('tax_name')) }}" placeholder="e.g. GST, VAT">
                                @error('tax_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.tax_percentage') }} (%)</label>
                                <input type="number" name="tax_percentage" step="0.01" min="0" max="100"
                                    class="form-control @error('tax_percentage') is-invalid @enderror"
                                    value="{{ old('tax_percentage', $settings->get('tax_percentage')) }}"
                                    placeholder="e.g. 18">
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Save Card --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-save me-2 text-success"></i>{{ __('messages.save_settings') }}
                        </h6>
                    </div>
                    <div class="card-body p-4 d-grid gap-2">
                        @can('settings.update')
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_settings') }}
                            </button>
                        @endcan
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </div>

                {{-- Current Values Summary --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-info"></i>{{ __('messages.current_values') }}
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.currency') }}</span>
                            <span class="fw-bold">{{ $settings->get('currency_code', 'INR') }}
                                {{ $settings->get('currency_symbol', '₹') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.tax_name') }}</span>
                            <span class="fw-bold">{{ $settings->get('tax_name', 'N/A') }}
                                ({{ $settings->get('tax_percentage', '0') }}%)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.invoice_prefix') }}</span>
                            <code>{{ $settings->get('invoice_prefix', 'INV') }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.purchase_prefix') }}</span>
                            <code>{{ $settings->get('purchase_prefix', 'PO') }}</code>
                        </li>
                    </ul>
                    <div class="card-footer text-muted small text-center">
                        <i class="bx bx-info-circle me-1"></i>
                        {{ __('messages.currency_managed_separately') }}
                    </div>
                </div>
            </div>

        </div>

    </form>

@endsection
