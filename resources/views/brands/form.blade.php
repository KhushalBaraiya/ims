@csrf

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.brand_name') }} <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $brand->name ?? '') }}" placeholder="e.g. Nike, Adidas" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.brand_code') }} <span class="text-danger">*</span></label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
        value="{{ old('slug', $brand->slug ?? '') }}" placeholder="e.g. NIKE, ADIDAS" required>
    <div class="form-text">{{ __('messages.description_label') }}</div>
    @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.description_label') }}</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
        placeholder="Describe this brand...">{{ old('description', $brand->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
