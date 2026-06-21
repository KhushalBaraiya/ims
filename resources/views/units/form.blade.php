@csrf

<div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6 text-[14px]">
    <!-- Unit Name -->
    <div class="sm:col-span-3">
        <x-input label="Unit Name" name="name" :value="old('name', $unit->name ?? '')" required placeholder="e.g. Piece, Kilogram" />
    </div>

    <!-- Short Name -->
    <div class="sm:col-span-3">
        <x-input label="Short Name" name="short_name" :value="old('short_name', $unit->short_name ?? '')" required placeholder="e.g. PCS, KG" />
    </div>

    <!-- Status -->
    <div class="sm:col-span-3">
        <x-select label="Status" name="status" required>
            <option value="active" {{ old('status', $unit->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $unit->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </x-select>
    </div>

    <!-- Description -->
    <div class="sm:col-span-6">
        <x-textarea label="Description" name="description" :value="old('description', $unit->description ?? '')" placeholder="Describe the unit briefly..." />
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <x-button href="{{ route('units.index') }}" variant="secondary">
        Cancel
    </x-button>
    <x-button type="submit" variant="primary">
        {{ isset($unit) ? 'Update Unit' : 'Save Unit' }}
    </x-button>
</div>
