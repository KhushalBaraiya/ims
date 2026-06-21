@extends('layouts.app')

@section('title', 'Edit Purchase Return')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Purchase Return</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify the return details and quantities. Stock adjustments will be recalculated automatically.</p>
    </div>

    <form method="POST" action="{{ route('purchase-returns.update', $purchaseReturn->id) }}" id="returnForm" novalidate>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">

            <!-- Left Column -->
            <div class="lg:col-span-1 space-y-6">
                <x-card title="Return Details">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Return No</label>
                            <input type="text" class="block w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-500 font-bold font-mono outline-none" value="{{ $purchaseReturn->return_no }}" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Purchase Order</label>
                            <input type="hidden" name="purchase_id" value="{{ $purchaseReturn->purchase_id }}">
                            <input type="text" class="block w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-500 font-bold font-mono outline-none" value="{{ $purchaseReturn->purchase->purchase_no ?? '-' }}" readonly>
                        </div>
                        <div>
                            <x-input label="Return Date" type="date" name="return_date" :value="old('return_date', $purchaseReturn->return_date)" required />
                        </div>
                        <div>
                            <x-input label="Reference No" name="reference_no" :value="old('reference_no', $purchaseReturn->reference_no ?? '')" placeholder="Optional reference..." />
                        </div>
                        <div>
                            <x-select label="Status" name="status" required>
                                <option value="Completed" {{ old('status', $purchaseReturn->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Pending"   {{ old('status', $purchaseReturn->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </x-select>
                        </div>
                    </div>
                </x-card>
                <x-card title="Refund Details">
                    <div class="space-y-4">
                        <div>
                            <x-input label="Refunded Amount" type="number" step="0.01" name="refunded_amount" id="refunded_amount" :value="old('refunded_amount', $purchaseReturn->refunded_amount)" required />
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Items -->
            <div class="lg:col-span-3 space-y-6">
                <x-card title="Return Items">
                    <div class="overflow-x-auto">
                        <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-2.5 px-3">Product</th>
                                    <th class="py-2.5 px-3 w-32 text-center">Orig. Qty Returned</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Return Qty</th>
                                    <th class="py-2.5 px-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                                @foreach($purchaseReturn->items as $index => $item)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $item->product->name }}
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                    </td>
                                    <td class="py-3 px-3 text-center text-slate-500 font-semibold">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="py-3 px-3">
                                        <input type="number" step="0.01" min="0" name="items[{{ $index }}][quantity]" value="{{ old("items.{$index}.quantity", $item->quantity) }}" class="block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-bold focus:border-blue-500">
                                    </td>
                                    <td class="py-3 px-3">
                                        <input type="text" name="items[{{ $index }}][reason]" value="{{ old("items.{$index}.reason", $item->reason ?? '') }}" class="block w-full rounded border border-slate-305 dark:border-slate-855 bg-white dark:bg-slate-950 py-1 px-2 text-xs text-slate-800 dark:text-slate-100 outline-none placeholder-slate-400" placeholder="Reason...">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-card title="Notes">
                        <x-textarea name="notes" rows="6" :value="old('notes', $purchaseReturn->notes ?? '')" placeholder="Return reason, conditions..." />
                    </x-card>
                    <x-card title="Refund Summary">
                        <div class="space-y-4 text-xs">
                            <div class="bg-violet-50 dark:bg-violet-950/20 p-3 rounded-xl border border-violet-100 dark:border-violet-900/30 flex justify-between items-center">
                                <span class="text-[10px] text-violet-500 font-bold uppercase">Refunded Amount</span>
                                <span class="font-black text-violet-600 dark:text-violet-400 text-sm">₹{{ number_format($purchaseReturn->refunded_amount, 2) }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button href="{{ route('purchase-returns.index') }}" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Update Return</x-button>
                </div>
            </div>
        </div>
    </form>
@endsection
