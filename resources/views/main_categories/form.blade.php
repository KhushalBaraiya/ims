@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.category_name') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $mainCategory->name ?? '') }}" placeholder="{{ __('messages.ph_category_name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.category_code') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $mainCategory->slug ?? '') }}" placeholder="{{ __('messages.ph_category_code_eg') }}" required>
        <div class="form-text">{{ __('messages.slug_hint') }}</div>
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('messages.description_label') }}</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
            placeholder="{{ __('messages.ph_category_description') }}">{{ old('description', $mainCategory->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active"
                {{ old('status', $mainCategory->status ?? 'active') === 'active' ? 'selected' : '' }}>
                {{ __('messages.active') }}</option>
            <option value="inactive" {{ old('status', $mainCategory->status ?? '') === 'inactive' ? 'selected' : '' }}>
                {{ __('messages.inactive') }}</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('main-categories.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i>
        {{ isset($mainCategory) ? __('messages.update') : __('messages.save') }} {{ __('messages.category') }}
    </button>
</div>
