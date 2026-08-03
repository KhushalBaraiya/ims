@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.customer_name') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $customer->name ?? '') }}" placeholder="{{ __('messages.ph_full_name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.th_email') }}</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $customer->email ?? '') }}" placeholder="{{ __('messages.ph_email_field') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.phone_label') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $customer->phone ?? '') }}" placeholder="{{ __('messages.ph_phone_number') }}" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.gst_number') }}</label>
        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror"
            value="{{ old('gst_number', $customer->gst_number ?? '') }}" placeholder="{{ __('messages.ph_gst_number') }}">
        @error('gst_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.opening_balance') }}</label>
        <input type="number" step="0.01" name="opening_balance"
            class="form-control @error('opening_balance') is-invalid @enderror"
            value="{{ old('opening_balance', $customer->opening_balance ?? '0.00') }}" placeholder="{{ __('messages.ph_opening_balance') }}">
        @error('opening_balance')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $customer->status ?? 'active') === 'active' ? 'selected' : '' }}>
                {{ __('messages.active') }}</option>
            <option value="inactive" {{ old('status', $customer->status ?? '') === 'inactive' ? 'selected' : '' }}>
                {{ __('messages.inactive') }}</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.city') }}</label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
            value="{{ old('city', $customer->city ?? '') }}" placeholder="{{ __('messages.city') }}">
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
        <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
            placeholder="{{ __('messages.ph_street_address') }}">{{ old('address', $customer->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($customer) ? __('messages.update') : __('messages.save') }}
    </button>
</div>
