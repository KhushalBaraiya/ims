@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Main Category <span class="text-danger">*</span></label>
        <select name="main_category_id" class="form-select @error('main_category_id') is-invalid @enderror" required>
            <option value="">Select Main Category</option>
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
        <label class="form-label fw-semibold">Sub Category Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $subCategory->name ?? '') }}" placeholder="e.g. Laptops" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Code / SKU Prefix <span class="text-danger">*</span></label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $subCategory->slug ?? '') }}" placeholder="e.g. LAPTOP" required>
        <div class="form-text">Unique identifier (letters, numbers, dashes only).</div>
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $subCategory->status ?? 'active') === 'active' ? 'selected' : '' }}>
                Active</option>
            <option value="inactive" {{ old('status', $subCategory->status ?? '') === 'inactive' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
            placeholder="Describe this sub category...">{{ old('description', $subCategory->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($subCategory) ? 'Update' : 'Save' }}
    </button>
</div>
