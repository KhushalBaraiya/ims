@csrf

<div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6 text-[14px]">
    <!-- Customer Name -->
    <div class="sm:col-span-3">
        <x-input label="Customer Name" name="name" :value="old('name', $customer->name ?? '')" required placeholder="e.g. John Doe" />
    </div>

    <!-- Email -->
    <div class="sm:col-span-3">
        <x-input label="Email Address" name="email" type="email" :value="old('email', $customer->email ?? '')" placeholder="e.g. john@example.com" />
    </div>

    <!-- Phone -->
    <div class="sm:col-span-2">
        <x-input label="Phone Number" name="phone" :value="old('phone', $customer->phone ?? '')" required placeholder="e.g. +1234567890" />
    </div>

    <!-- Alternative Phone -->
    <div class="sm:col-span-2">
        <x-input label="Alternative Phone" name="alt_phone" :value="old('alt_phone', $customer->alt_phone ?? '')" placeholder="e.g. +1098765432" />
    </div>

    <!-- GST Number -->
    <div class="sm:col-span-2">
        <x-input label="GST Number" name="gst_number" :value="old('gst_number', $customer->gst_number ?? '')" placeholder="e.g. 22AAAAA0000A1Z5" />
    </div>

    <!-- Opening Balance -->
    <div class="sm:col-span-2">
        <x-input label="Opening Balance" name="opening_balance" type="number" step="0.01" :value="old('opening_balance', $customer->opening_balance ?? '0.00')" placeholder="e.g. 0.00" />
    </div>

    <!-- Status -->
    <div class="sm:col-span-2">
        <x-select label="Status" name="status" required>
            <option value="active" {{ old('status', $customer->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $customer->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </x-select>
    </div>

    <!-- Pincode -->
    <div class="sm:col-span-2">
        <x-input label="Pincode / ZIP" name="pincode" :value="old('pincode', $customer->pincode ?? '')" placeholder="e.g. 10001" />
    </div>

    <!-- Address -->
    <div class="sm:col-span-6">
        <x-textarea label="Street Address" name="address" :value="old('address', $customer->address ?? '')" placeholder="e.g. 456 Maple Rd" />
    </div>

    <!-- City -->
    <div class="sm:col-span-2">
        <x-input label="City" name="city" :value="old('city', $customer->city ?? '')" placeholder="e.g. Los Angeles" />
    </div>

    <!-- State -->
    <div class="sm:col-span-2">
        <x-input label="State / Province" name="state" :value="old('state', $customer->state ?? '')" placeholder="e.g. CA" />
    </div>

    <!-- Country -->
    <div class="sm:col-span-2">
        <x-input label="Country" name="country" :value="old('country', $customer->country ?? '')" placeholder="e.g. United States" />
    </div>

    <!-- Notes -->
    <div class="sm:col-span-6">
        <x-input label="Notes" name="notes" :value="old('notes', $customer->notes ?? '')" placeholder="Any internal comments..." />
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <x-button href="{{ route('customers.index') }}" variant="secondary">
        Cancel
    </x-button>
    <x-button type="submit" variant="primary">
        {{ isset($customer) ? 'Update Customer' : 'Save Customer' }}
    </x-button>
</div>
