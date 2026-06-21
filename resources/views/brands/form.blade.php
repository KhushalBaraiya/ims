@csrf

<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
    <!-- Brand Name -->
    <div class="sm:col-span-3">
        <label for="name" class="block text-sm font-semibold text-slate-700">Brand Name <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <input type="text" name="name" id="name" value="{{ old('name', $brand->name ?? '') }}" required
                class="block w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/50 @error('name') border-red-500 focus:ring-red-500/30 @enderror"
                placeholder="e.g. Sony, Samsung">
        </div>
        @error('name')
            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Brand Code (slug) -->
    <div class="sm:col-span-3">
        <label for="slug" class="block text-sm font-semibold text-slate-700">Brand Code <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug ?? '') }}" required
                class="block w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/50 @error('slug') border-red-500 focus:ring-red-500/30 @enderror"
                placeholder="e.g. SONY, SAMSUNG-01">
        </div>
        <p class="mt-1 text-xs text-slate-400">Unique alphanumeric code used to identify the brand.</p>
        @error('slug')
            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Description -->
    <div class="sm:col-span-6">
        <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
        <div class="mt-1">
            <textarea id="description" name="description" rows="3"
                class="block w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/50 @error('description') border-red-500 focus:ring-red-500/30 @enderror"
                placeholder="Describe this brand briefly...">{{ old('description', $brand->description ?? '') }}</textarea>
        </div>
        @error('description')
            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Status -->
    <div class="sm:col-span-3">
        <label for="status" class="block text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <select id="status" name="status" required
                class="block w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/50 @error('status') border-red-500 focus:ring-red-500/30 @enderror">
                <option value="active" {{ old('status', $brand->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $brand->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        @error('status')
            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3">
    <a href="{{ route('brands.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
        Cancel
    </a>
    <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-2 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
        {{ isset($brand) ? 'Update Brand' : 'Save Brand' }}
    </button>
</div>
