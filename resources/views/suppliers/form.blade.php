@csrf

<div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6 text-[14px]">
    <!-- Supplier Name -->
    <div class="sm:col-span-3">
        <x-input label="Supplier Name" name="name" :value="old('name', $supplier->name ?? '')" required placeholder="e.g. Acme Corporation" />
    </div>

    <!-- Company Name -->
    <div class="sm:col-span-3">
        <x-input label="Company Name" name="company_name" :value="old('company_name', $supplier->company_name ?? '')" placeholder="e.g. Acme Corp Inc." />
    </div>

    <!-- Contact Person -->
    <div class="sm:col-span-2">
        <x-input label="Contact Person" name="contact_person" :value="old('contact_person', $supplier->contact_person ?? '')" placeholder="e.g. John Doe" />
    </div>

    <!-- Phone -->
    <div class="sm:col-span-2">
        <x-input label="Phone Number" name="phone" :value="old('phone', $supplier->phone ?? '')" required placeholder="e.g. +1234567890" />
    </div>

    <!-- Alternative Phone -->
    <div class="sm:col-span-2">
        <x-input label="Alternative Phone" name="alt_phone" :value="old('alt_phone', $supplier->alt_phone ?? '')" placeholder="e.g. +1098765432" />
    </div>

    <!-- Email -->
    <div class="sm:col-span-3">
        <x-input label="Email Address" name="email" type="email" :value="old('email', $supplier->email ?? '')" placeholder="e.g. supplier@company.com" />
    </div>

    <!-- GST Number -->
    <div class="sm:col-span-3">
        <x-input label="GST Number" name="gst_number" :value="old('gst_number', $supplier->gst_number ?? '')" placeholder="e.g. 22AAAAA0000A1Z5" />
    </div>

    <!-- PAN Number -->
    <div class="sm:col-span-2">
        <x-input label="PAN Number" name="pan_number" :value="old('pan_number', $supplier->pan_number ?? '')" placeholder="e.g. ABCDE1234F" />
    </div>

    <!-- Opening Balance -->
    <div class="sm:col-span-2">
        <x-input label="Opening Balance" name="opening_balance" type="number" step="0.01" :value="old('opening_balance', $supplier->opening_balance ?? '0.00')" placeholder="e.g. 1500.00" />
    </div>

    <!-- Status -->
    <div class="sm:col-span-2">
        <x-select label="Status" name="status" required>
            <option value="active" {{ old('status', $supplier->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $supplier->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </x-select>
    </div>

    <!-- Address -->
    <div class="sm:col-span-6">
        <x-textarea label="Street Address" name="address" :value="old('address', $supplier->address ?? '')" placeholder="e.g. 123 Main St, Suite 400" />
    </div>

    <!-- City -->
    <div class="sm:col-span-2">
        <x-input label="City" name="city" :value="old('city', $supplier->city ?? '')" placeholder="e.g. New York" />
    </div>

    <!-- State -->
    <div class="sm:col-span-2">
        <x-input label="State / Province" name="state" :value="old('state', $supplier->state ?? '')" placeholder="e.g. NY" />
    </div>

    <!-- Country -->
    <div class="sm:col-span-2">
        <x-input label="Country" name="country" :value="old('country', $supplier->country ?? '')" placeholder="e.g. United States" />
    </div>

    <!-- Pincode -->
    <div class="sm:col-span-2">
        <x-input label="Pincode / ZIP" name="pincode" :value="old('pincode', $supplier->pincode ?? '')" placeholder="e.g. 10001" />
    </div>

    <!-- Notes -->
    <div class="sm:col-span-4">
        <x-input label="Notes" name="notes" :value="old('notes', $supplier->notes ?? '')" placeholder="Any internal annotations about this supplier..." />
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <x-button href="{{ route('suppliers.index') }}" variant="secondary">
        Cancel
    </x-button>
    <x-button type="submit" variant="primary">
        {{ isset($supplier) ? 'Update Supplier' : 'Save Supplier' }}
    </x-button>
</div>
