@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.supplier_name') }} <span
                class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" name="name" placeholder="{{ __('messages.ph_supplier_name_eg') }}"
            required type="text" value="{{ old('name', $supplier->name ?? '') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.company_name') }}</label>
        <input class="form-control @error('company_name') is-invalid @enderror" name="company_name"
            placeholder="{{ __('messages.ph_company_name_eg') }}" type="text"
            value="{{ old('company_name', $supplier->company_name ?? '') }}">
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.phone_label') }} <span
                class="text-danger">*</span></label>
        <input class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="{{ __('messages.ph_phone_number') }}"
            required type="text" value="{{ old('phone', $supplier->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.th_email') }}</label>
        <input class="form-control @error('email') is-invalid @enderror" name="email"
            placeholder="{{ __('messages.ph_supplier_email') }}" type="email" value="{{ old('email', $supplier->email ?? '') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.gst_number') }}</label>
        <input class="form-control @error('gst_number') is-invalid @enderror" name="gst_number"
            placeholder="{{ __('messages.ph_gst_number') }}" type="text" value="{{ old('gst_number', $supplier->gst_number ?? '') }}">
        @error('gst_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
            <option {{ old('status', $supplier->status ?? 'active') === 'active' ? 'selected' : '' }} value="active">
                {{ __('messages.active') }}</option>
            <option {{ old('status', $supplier->status ?? '') === 'inactive' ? 'selected' : '' }} value="inactive">
                {{ __('messages.inactive') }}</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.city') }}</label>
        <input class="form-control @error('city') is-invalid @enderror" name="city"
            placeholder="{{ __('messages.city') }}" type="text" value="{{ old('city', $supplier->city ?? '') }}">
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
        <textarea class="form-control @error('address') is-invalid @enderror" name="address" placeholder="{{ __('messages.ph_street_address') }}"
            rows="2">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end border-top mt-2 gap-2 pt-4">
    <a class="btn btn-outline-secondary" href="{{ route('suppliers.index') }}">
        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
    </a>
    <button class="btn btn-primary" type="submit">
        <i class="bx bx-save me-1"></i> {{ isset($supplier) ? __('messages.update') : __('messages.save') }}
    </button>
</div>
