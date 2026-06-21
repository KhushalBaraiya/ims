@csrf

<div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6 text-[14px]">
    <!-- Main Category -->
    <div class="sm:col-span-3">
        <x-select label="Main Category" name="main_category_id" required>
            <option value="">Select Main Category</option>
            @foreach($mainCategories as $cat)
                <option value="{{ $cat->id }}" {{ old('main_category_id', $subCategory->main_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </x-select>
    </div>

    <!-- Sub Category Name -->
    <div class="sm:col-span-3">
        <x-input label="Sub Category Name" name="name" :value="old('name', $subCategory->name ?? '')" required placeholder="e.g. Laptops, Motherboards" />
    </div>

    <!-- Sub Category Code (Slug) -->
    <div class="sm:col-span-3">
        <x-input label="Sub Category Code (SKU Prefix)" name="slug" :value="old('slug', $subCategory->slug ?? '')" required placeholder="e.g. LAPTOP, MBOARD" />
        <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">Unique identifier for the subcategory (letters, numbers, dashes, underscores only).</p>
    </div>

    <!-- Status -->
    <div class="sm:col-span-3">
        <x-select label="Status" name="status" required>
            <option value="active" {{ old('status', $subCategory->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $subCategory->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </x-select>
    </div>

    <!-- Description -->
    <div class="sm:col-span-6">
        <x-textarea label="Description" name="description" :value="old('description', $subCategory->description ?? '')" placeholder="Describe the sub category..." />
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <x-button href="{{ route('sub-categories.index') }}" variant="secondary">
        Cancel
    </x-button>
    <x-button type="submit" variant="primary">
        {{ isset($subCategory) ? 'Update Sub Category' : 'Save Sub Category' }}
    </x-button>
</div>
