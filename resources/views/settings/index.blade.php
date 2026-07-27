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




    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
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

                        {{-- Company Logo Upload --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-image text-primary me-1"></i>Company Logo
                            </label>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                @if ($settings->get('company_logo'))
                                    <div class="border rounded p-2 bg-light flex-shrink-0" style="line-height:0;">
                                        <img src="{{ asset('uploads/settings/' . $settings->get('company_logo')) }}"
                                            alt="Company Logo" style="height:52px;max-width:160px;object-fit:contain;">
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <input class="form-control @error('company_logo') is-invalid @enderror"
                                        name="company_logo" type="file"
                                        accept="image/png,image/jpeg,image/svg+xml,image/webp">
                                    <div class="form-text">PNG, JPG, SVG or WebP. Max 2MB. Shown on invoices and emails.
                                    </div>
                                    @error('company_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                                    min="0" name="tax_percentage" placeholder="e.g. 18" step="0.01"
                                    type="number" value="{{ old('tax_percentage', $settings->get('tax_percentage')) }}">
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
                                        {{ __('messages.show_out_of_stock_products') }}
                                    </label>
                                    <div class="form-text mt-0">
                                        {{ __('messages.show_out_of_stock_products_hint') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Session Timeout --}}
                        <div class="border-top mt-3 pt-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-time-five text-warning me-1"></i>
                                Session Timeout (minutes)
                            </label>
                            <div class="input-group" style="max-width:260px;">
                                <input class="form-control @error('session_timeout') is-invalid @enderror"
                                    name="session_timeout" type="number" min="0" max="1440" step="1"
                                    placeholder="e.g. 30"
                                    value="{{ old('session_timeout', $settings->get('session_timeout', '0')) }}">
                                <span class="input-group-text">min</span>
                                @error('session_timeout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Set <strong>0</strong> to disable auto-logout. Users will be logged out automatically after
                                this many minutes of inactivity.
                            </div>
                        </div>

                        {{-- Login Lockout Settings --}}
                        <div class="border-top mt-3 pt-3">
                            <p class="fw-semibold mb-3">
                                <i class="bx bx-lock-alt text-danger me-1"></i>
                                Login Lockout Settings
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Max Login Attempts</label>
                                    <div class="input-group">
                                        <input class="form-control @error('max_login_attempts') is-invalid @enderror"
                                            name="max_login_attempts" type="number" min="0" max="20"
                                            step="1" placeholder="e.g. 5"
                                            value="{{ old('max_login_attempts', $settings->get('max_login_attempts', '5')) }}">
                                        <span class="input-group-text">tries</span>
                                        @error('max_login_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Set <strong>0</strong> to disable lockout.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lockout Duration</label>
                                    <div class="input-group">
                                        <input class="form-control @error('lockout_duration') is-invalid @enderror"
                                            name="lockout_duration" type="number" min="1" max="1440"
                                            step="1" placeholder="e.g. 15"
                                            value="{{ old('lockout_duration', $settings->get('lockout_duration', '15')) }}">
                                        <span class="input-group-text">min</span>
                                        @error('lockout_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">How long to lock the account after max attempts.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SMTP / Email Settings ── --}}
            <div class="col-lg-8 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-envelope text-info me-2"></i>Email / SMTP Settings
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info d-flex gap-2 align-items-start py-2 px-3 mb-4"
                            style="font-size:.82rem;">
                            <i class="bx bx-info-circle mt-1 flex-shrink-0"></i>
                            <div>
                                These settings control how invoice emails are sent. For Gmail use
                                <strong>smtp.gmail.com</strong> (Port 587, TLS) with an
                                <strong>App Password</strong>. Changes are written directly to <code>.env</code>.
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mailer Driver</label>
                                <select class="form-select @error('mail_mailer') is-invalid @enderror"
                                    name="mail_mailer">
                                    <option value="smtp"
                                        {{ old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'smtp' ? 'selected' : '' }}>
                                        SMTP</option>
                                    <option value="log"
                                        {{ old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'log' ? 'selected' : '' }}>
                                        Log (debug only)</option>
                                    <option value="array"
                                        {{ old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'array' ? 'selected' : '' }}>
                                        Array (testing)</option>
                                </select>
                                @error('mail_mailer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">SMTP Host</label>
                                <input class="form-control @error('mail_host') is-invalid @enderror" name="mail_host"
                                    type="text" placeholder="smtp.gmail.com"
                                    value="{{ old('mail_host', $settings->get('mail_host', env('MAIL_HOST', ''))) }}">
                                @error('mail_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">SMTP Port</label>
                                <input class="form-control @error('mail_port') is-invalid @enderror" name="mail_port"
                                    type="number" placeholder="587"
                                    value="{{ old('mail_port', $settings->get('mail_port', env('MAIL_PORT', '587'))) }}">
                                @error('mail_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Encryption</label>
                                <select class="form-select @error('mail_encryption') is-invalid @enderror"
                                    name="mail_encryption">
                                    <option value="tls"
                                        {{ old('mail_encryption', $settings->get('mail_encryption', 'tls')) === 'tls' ? 'selected' : '' }}>
                                        TLS (Port 587)</option>
                                    <option value="ssl"
                                        {{ old('mail_encryption', $settings->get('mail_encryption', 'tls')) === 'ssl' ? 'selected' : '' }}>
                                        SSL (Port 465)</option>
                                    <option value=""
                                        {{ old('mail_encryption', $settings->get('mail_encryption', 'tls')) === '' ? 'selected' : '' }}>
                                        None</option>
                                </select>
                                @error('mail_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">SMTP Username</label>
                                <input class="form-control @error('mail_username') is-invalid @enderror"
                                    name="mail_username" type="text" placeholder="your@gmail.com"
                                    value="{{ old('mail_username', $settings->get('mail_username', env('MAIL_USERNAME', ''))) }}">
                                @error('mail_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">SMTP Password / App Password</label>
                                <input class="form-control @error('mail_password') is-invalid @enderror"
                                    name="mail_password" type="password" placeholder="Leave blank to keep existing"
                                    autocomplete="new-password">
                                <div class="form-text">Leave blank to keep current password.</div>
                                @error('mail_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">From Email Address</label>
                                <input class="form-control @error('mail_from_address') is-invalid @enderror"
                                    name="mail_from_address" type="email" placeholder="noreply@yourcompany.com"
                                    value="{{ old('mail_from_address', $settings->get('mail_from_address', env('MAIL_FROM_ADDRESS', ''))) }}">
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">From Name</label>
                                <input class="form-control @error('mail_from_name') is-invalid @enderror"
                                    name="mail_from_name" type="text" placeholder="Your Company Name"
                                    value="{{ old('mail_from_name', $settings->get('mail_from_name', env('MAIL_FROM_NAME', ''))) }}">
                                @error('mail_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
