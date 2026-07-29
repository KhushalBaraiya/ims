@extends('layouts.admin')
@section('title', __('messages.edit_supplier') . ' — ' . $supplier->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_supplier') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('suppliers.index') }}">{{ __('messages.menu_suppliers') }}</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('suppliers.index') }}">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form action="{{ route('suppliers.update', $supplier->id) }}" data-validate="true" id="supplierForm" method="POST">
        @csrf @method('PUT')
        <div class="row g-4">

            {{-- Left: Supplier Details --}}
            <div class="col-lg-8 col-md-8">

                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-store text-primary me-2"></i>{{ __('messages.supplier_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.supplier_name') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name" required
                                    type="text" value="{{ old('name', $supplier->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.company_name') }}</label>
                                <input class="form-control @error('company_name') is-invalid @enderror" name="company_name"
                                    type="text" value="{{ old('company_name', $supplier->company_name) }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.contact_person') }}</label>
                                <input class="form-control @error('contact_person') is-invalid @enderror"
                                    name="contact_person" type="text"
                                    value="{{ old('contact_person', $supplier->contact_person) }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.ph_phone') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('phone') is-invalid @enderror" name="phone" required
                                    type="text" value="{{ old('phone', $supplier->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.alt_phone') }}</label>
                                <input class="form-control @error('alt_phone') is-invalid @enderror" name="alt_phone"
                                    type="text" value="{{ old('alt_phone', $supplier->alt_phone) }}">
                                @error('alt_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.email_address') }}</label>
                                <input class="form-control @error('email') is-invalid @enderror" name="email"
                                    type="email" value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.gst_number') }}</label>
                                <input class="form-control @error('gst_number') is-invalid @enderror" name="gst_number"
                                    type="text" value="{{ old('gst_number', $supplier->gst_number) }}">
                                @error('gst_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.pan_number') }}</label>
                                <input class="form-control @error('pan_number') is-invalid @enderror" name="pan_number"
                                    type="text" value="{{ old('pan_number', $supplier->pan_number) }}">
                                @error('pan_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-map text-info me-2"></i>{{ __('messages.address_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.city') }}</label>
                                <input class="form-control @error('city') is-invalid @enderror" name="city"
                                    type="text" value="{{ old('city', $supplier->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.state') }}</label>
                                <input class="form-control @error('state') is-invalid @enderror" name="state"
                                    type="text" value="{{ old('state', $supplier->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.pincode') }}</label>
                                <input class="form-control @error('pincode') is-invalid @enderror" name="pincode"
                                    type="text" value="{{ old('pincode', $supplier->pincode) }}">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.country') }}</label>
                                <input class="form-control @error('country') is-invalid @enderror" id="countryInput"
                                    name="country" type="text" value="{{ old('country', $supplier->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-coin me-1 text-warning"></i>Supplier Currency
                                </label>
                                <select class="form-select @error('currency_id') is-invalid @enderror" name="currency_id"
                                    id="supplierCurrency">
                                    <option value="">— Same as system default —</option>
                                    @foreach ($currencies as $cur)
                                        <option value="{{ $cur->id }}" data-code="{{ $cur->code }}"
                                            {{ old('currency_id', $supplier->currency_id) == $cur->id ? 'selected' : '' }}>
                                            {{ $cur->symbol }} {{ $cur->name }} ({{ $cur->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text" id="currencyAutoNote">
                                    Used to auto-switch currency on Purchase Order form.
                                </div>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note text-secondary me-2"></i>{{ __('messages.notes') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Right: Publish + Info --}}
            <div class="col-lg-4 col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-send text-primary me-2"></i>{{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">{{ __('messages.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input name="status" type="hidden" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input {{ old('status', $supplier->status) === 'active' ? 'checked' : '' }}
                                        class="form-check-input" id="statusToggle" name="status" role="switch"
                                        type="checkbox" value="active">
                                </div>
                                <span
                                    class="fw-semibold {{ old('status', $supplier->status) === 'active' ? 'text-success' : 'text-danger' }}"
                                    id="statusLabel">
                                    {{ old('status', $supplier->status) === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i> {{ __('messages.update') }}
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('suppliers.show', $supplier->id) }}">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-secondary me-2"></i>{{ __('messages.information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">ID</span>
                                <span class="fw-bold">#{{ $supplier->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                                <span>{{ $supplier->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                                <span>{{ $supplier->updated_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                lbl.textContent = this.checked ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}';
                lbl.className = 'fw-semibold ' + (this.checked ? 'text-success' : 'text-danger');
            });
        }

        // ── Country → Currency auto-suggest ───────────────────────────────
        const countryCurrencyMap = {
            // Asia
            'india': 'INR',
            'bharat': 'INR',
            'china': 'CNY',
            'peoples republic of china': 'CNY',
            'japan': 'JPY',
            'south korea': 'KRW',
            'korea': 'KRW',
            'singapore': 'SGD',
            'hong kong': 'HKD',
            'pakistan': 'PKR',
            'bangladesh': 'BDT',
            'sri lanka': 'LKR',
            'ceylon': 'LKR',
            'nepal': 'NPR',
            'malaysia': 'MYR',
            'thailand': 'THB',
            'indonesia': 'IDR',
            'philippines': 'PHP',
            'vietnam': 'VND',
            'viet nam': 'VND',
            'uae': 'AED',
            'united arab emirates': 'AED',
            'dubai': 'AED',
            'abu dhabi': 'AED',
            'saudi arabia': 'SAR',
            'ksa': 'SAR',
            'qatar': 'QAR',
            'kuwait': 'KWD',
            'bahrain': 'BHD',
            'oman': 'OMR',
            'israel': 'ILS',
            'turkey': 'TRY',
            'turkiye': 'TRY',
            'iran': 'IRR',
            // Europe
            'germany': 'EUR',
            'france': 'EUR',
            'italy': 'EUR',
            'spain': 'EUR',
            'netherlands': 'EUR',
            'belgium': 'EUR',
            'austria': 'EUR',
            'portugal': 'EUR',
            'greece': 'EUR',
            'finland': 'EUR',
            'ireland': 'EUR',
            'luxembourg': 'EUR',
            'slovakia': 'EUR',
            'slovenia': 'EUR',
            'estonia': 'EUR',
            'latvia': 'EUR',
            'lithuania': 'EUR',
            'malta': 'EUR',
            'cyprus': 'EUR',
            'croatia': 'EUR',
            'united kingdom': 'GBP',
            'uk': 'GBP',
            'britain': 'GBP',
            'england': 'GBP',
            'great britain': 'GBP',
            'scotland': 'GBP',
            'wales': 'GBP',
            'switzerland': 'CHF',
            'norway': 'NOK',
            'sweden': 'SEK',
            'denmark': 'DKK',
            'poland': 'PLN',
            'czech republic': 'CZK',
            'czechia': 'CZK',
            'hungary': 'HUF',
            'romania': 'RON',
            'russia': 'RUB',
            'russian federation': 'RUB',
            'ukraine': 'UAH',
            // Americas
            'united states': 'USD',
            'usa': 'USD',
            'us': 'USD',
            'america': 'USD',
            'united states of america': 'USD',
            'canada': 'CAD',
            'mexico': 'MXN',
            'brazil': 'BRL',
            'argentina': 'ARS',
            'chile': 'CLP',
            'colombia': 'COP',
            // Oceania
            'australia': 'AUD',
            'new zealand': 'NZD',
            // Africa
            'south africa': 'ZAR',
            'nigeria': 'NGN',
            'kenya': 'KES',
            'egypt': 'EGP',
            'morocco': 'MAD',
            'ghana': 'GHS',
            'tanzania': 'TZS',
        };

        function autoSelectCurrency() {
            const country = (document.getElementById('countryInput').value || '').trim().toLowerCase();
            const sel = document.getElementById('supplierCurrency');
            const note = document.getElementById('currencyAutoNote');

            if (!country) {
                note.textContent = 'Used to auto-switch currency on Purchase Order form.';
                note.className = 'form-text';
                return;
            }

            let matchedCode = null;
            for (const [key, code] of Object.entries(countryCurrencyMap)) {
                if (country === key || country.includes(key) || key.includes(country)) {
                    matchedCode = code;
                    break;
                }
            }

            if (!matchedCode) {
                note.textContent = 'No auto-match found — please select currency manually.';
                note.className = 'form-text text-warning';
                return;
            }

            let matched = false;
            for (const opt of sel.options) {
                if (opt.dataset.code === matchedCode) {
                    sel.value = opt.value;
                    matched = true;
                    break;
                }
            }

            if (matched) {
                note.innerHTML =
                    '<span class="text-success fw-semibold"><i class="bx bx-check-circle me-1"></i>Auto-matched: <strong>' +
                    matchedCode + '</strong> based on country.</span>';
                sel.classList.add('border-success');
                setTimeout(() => sel.classList.remove('border-success'), 2000);
            } else {
                note.innerHTML = '<span class="text-warning">Currency code <strong>' + matchedCode +
                    '</strong> not found in DB — add it first.</span>';
            }
        }

        const countryInput = document.getElementById('countryInput');
        if (countryInput) {
            let _cTimer;
            countryInput.addEventListener('input', function() {
                clearTimeout(_cTimer);
                _cTimer = setTimeout(autoSelectCurrency, 400);
            });
            // Run on load only if currency not already set (don't override existing choice)
            const currencyAlreadySet = document.getElementById('supplierCurrency').value !== '';
            if (countryInput.value.trim() && !currencyAlreadySet) {
                autoSelectCurrency();
            }
        }
    </script>
@endpush
