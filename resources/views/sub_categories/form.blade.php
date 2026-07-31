@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.th_main_category') }} <span
                class="text-danger">*</span></label>
        <select name="main_category_id" class="form-select @error('main_category_id') is-invalid @enderror" required>
            <option value="">{{ __('messages.select_main_category') }}</option>
            @foreach ($mainCategories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('main_category_id', $subCategory->main_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('main_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.sub_category_name') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $subCategory->name ?? '') }}" placeholder="{{ __('messages.ph_sub_category_name_eg') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.th_code') }} / {{ __('messages.sku_prefix') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $subCategory->slug ?? '') }}" placeholder="{{ __('messages.ph_sub_category_code_eg') }}" required>
        <div class="form-text">{{ __('messages.slug_hint') }}</div>
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active"
                {{ old('status', $subCategory->status ?? 'active') === 'active' ? 'selected' : '' }}>
                {{ __('messages.active') }}</option>
            <option value="inactive" {{ old('status', $subCategory->status ?? '') === 'inactive' ? 'selected' : '' }}>
                {{ __('messages.inactive') }}</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('messages.description_label') }}</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
            placeholder="{{ __('messages.ph_sub_category_description') }}">{{ old('description', $subCategory->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($subCategory) ? __('messages.update') : __('messages.save') }}
    </button>
</div>
