@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.supplier_name') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $supplier->name ?? '') }}" placeholder="e.g. Acme Corporation" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.company_name') }}</label>
        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
            value="{{ old('company_name', $supplier->company_name ?? '') }}" placeholder="e.g. Acme Corp Inc.">
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.phone_label') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $supplier->phone ?? '') }}" placeholder="+1234567890" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.th_email') }}</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $supplier->email ?? '') }}" placeholder="supplier@company.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.gst_number') }}</label>
        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror"
            value="{{ old('gst_number', $supplier->gst_number ?? '') }}" placeholder="22AAAAA0000A1Z5">
        @error('gst_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.opening_balance') }}</label>
        <input type="number" step="0.01" name="opening_balance"
            class="form-control @error('opening_balance') is-invalid @enderror"
            value="{{ old('opening_balance', $supplier->opening_balance ?? '0.00') }}" placeholder="0.00">
        @error('opening_balance')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $supplier->status ?? 'active') === 'active' ? 'selected' : '' }}>
                {{ __('messages.active') }}</option>
            <option value="inactive" {{ old('status', $supplier->status ?? '') === 'inactive' ? 'selected' : '' }}>
                {{ __('messages.inactive') }}</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.city') }}</label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
            value="{{ old('city', $supplier->city ?? '') }}" placeholder="{{ __('messages.city') }}">
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('messages.address_label') }}</label>
        <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
            placeholder="Street address...">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($supplier) ? __('messages.update') : __('messages.save') }}
    </button>
</div>
