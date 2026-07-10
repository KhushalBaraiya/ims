@extends('layouts.admin')
@section('title', __('messages.menu_settings'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.settings') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_settings') }}</li>
                </ol>
            </nav>
        </div>
    </div>




    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Company Information --}}
            <div class="col-lg-8 col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-building text-primary me-2"></i>{{ __('messages.company_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.company_name') }} <span
                                    class="text-danger">*</span></label>
                            <input class="form-control @error('company_name') is-invalid @enderror" name="company_name"
                                placeholder="e.g. My Company Ltd." required type="text"
                                value="{{ old('company_name', $settings->get('company_name')) }}">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.email_address') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('company_email') is-invalid @enderror"
                                    name="company_email" placeholder="admin@example.com" required type="email"
                                    value="{{ old('company_email', $settings->get('company_email')) }}">
                                @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.phone_label') }}</label>
                                <input class="form-control @error('company_phone') is-invalid @enderror"
                                    name="company_phone" placeholder="+1 234 567 8900" type="text"
                                    value="{{ old('company_phone', $settings->get('company_phone')) }}">
                                @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
                            <textarea class="form-control @error('company_address') is-invalid @enderror" name="company_address"
                                placeholder="Full company address..." rows="3">{{ old('company_address', $settings->get('company_address')) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Invoice & Tax Settings --}}
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-receipt text-warning me-2"></i>{{ __('messages.invoice_tax_settings') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.invoice_prefix') }}</label>
                                <input class="form-control @error('invoice_prefix') is-invalid @enderror"
                                    name="invoice_prefix" placeholder="e.g. INV" type="text"
                                    value="{{ old('invoice_prefix', $settings->get('invoice_prefix')) }}">
                                <div class="form-text">{{ __('messages.invoice_prefix_hint') }}</div>
                                @error('invoice_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.purchase_prefix') }}</label>
                                <input class="form-control @error('purchase_prefix') is-invalid @enderror"
                                    name="purchase_prefix" placeholder="e.g. PO" type="text"
                                    value="{{ old('purchase_prefix', $settings->get('purchase_prefix')) }}">
                                <div class="form-text">{{ __('messages.purchase_prefix_hint') }}</div>
                                @error('purchase_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.tax_name') }}</label>
                                <input class="form-control @error('tax_name') is-invalid @enderror" name="tax_name"
                                    placeholder="e.g. GST, VAT" type="text"
                                    value="{{ old('tax_name', $settings->get('tax_name')) }}">
                                @error('tax_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.tax_percentage') }} (%)</label>
                                <input class="form-control @error('tax_percentage') is-invalid @enderror" max="100"
                                    min="0" name="tax_percentage" placeholder="e.g. 18" step="0.01" type="number"
                                    value="{{ old('tax_percentage', $settings->get('tax_percentage')) }}">
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Show Out of Stock Products Toggle --}}
                        <div class="border-top mt-1 pt-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="form-check form-switch mt-1">
                                    <input
                                        {{ old('show_out_of_stock_products', $settings->get('show_out_of_stock_products', '0')) == '1' ? 'checked' : '' }}
                                        class="form-check-input" id="show_out_of_stock_products"
                                        name="show_out_of_stock_products" role="switch" type="checkbox" value="1">
                                </div>
                                <div>
                                    <label class="form-check-label fw-semibold mb-0" for="show_out_of_stock_products">
                                        Show Out of Stock Products
                                    </label>
                                    <div class="form-text mt-0">
                                        When enabled, out-of-stock products will appear in the sales product search — but
                                        cannot be added to a cart. When disabled, only products with available stock are
                                        shown.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Save Card --}}
            <div class="col-lg-4 col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-save text-success me-2"></i>{{ __('messages.save_settings') }}
                        </h6>
                    </div>
                    <div class="card-body d-grid gap-2 p-4">
                        @can('settings.update')
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_settings') }}
                            </button>
                        @endcan
                        <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">
                            <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </div>

                {{-- Current Values Summary --}}
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-info me-2"></i>{{ __('messages.current_values') }}
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
