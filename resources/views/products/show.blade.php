@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Product Profile</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Deep technical insights, pricing structures, and inventory levels.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <x-button href="{{ route('products.index') }}" variant="secondary">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Catalog
            </x-button>
            @can('products.update')
                <x-button href="{{ route('products.edit', $product->id) }}" variant="primary">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Edit Product
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Visual Gallery and Descriptions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Media Card -->
            <x-card>
                <div class="space-y-4">
                    <!-- Primary Photo -->
                    <div class="aspect-square w-full rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center shadow-sm">
                        <img id="primaryViewer" src="{{ $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Gallery Carousel / Grid -->
                    @if($product->gallery && count($product->gallery) > 0)
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Photo Gallery</span>
                            <div class="grid grid-cols-4 gap-2">
                                <!-- Include primary image in thumb list -->
                                <div class="aspect-square rounded-lg overflow-hidden border-2 border-blue-500 cursor-pointer thumbnail-item" data-src="{{ $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}">
                                    <img src="{{ $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}" class="w-full h-full object-cover">
                                </div>
                                @foreach($product->gallery as $galImg)
                                    <div class="aspect-square rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 cursor-pointer thumbnail-item transition-all hover:border-blue-500" data-src="{{ asset('uploads/products/' . $galImg) }}">
                                        <img src="{{ asset('uploads/products/' . $galImg) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>

            <!-- Description Card -->
            <x-card title="Product Descriptions">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Short Summary</span>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-1 italic">{{ $product->short_description ?: 'No short description available.' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Full Overview</span>
                        <p class="text-sm text-slate-650 dark:text-slate-405 mt-1 block whitespace-pre-line leading-relaxed">{{ $product->full_description ?: 'No detailed specs listed.' }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Middle & Right Columns: Product Specs, Pricing, Stock metadata -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Overview -->
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                    <div>
                        <span class="text-[11px] font-bold text-blue-500 uppercase tracking-widest">{{ $product->brand->name ?? 'Generic' }}</span>
                        <h2 class="text-lg font-bold text-slate-850 dark:text-slate-100 mt-0.5">{{ $product->name }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">SKU: <span class="font-mono font-bold">{{ $product->code }}</span> | Barcode: <span class="font-mono">{{ $product->barcode ?: '-' }}</span></p>
                    </div>
                    <div>
                        <x-badge :variant="$product->status === 'active' ? 'success' : 'danger'" :text="ucfirst($product->status)" />
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Main Category</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $product->mainCategory->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Sub Category</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $product->subCategory->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Unit of Measurement</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $product->unit->name ?? '-' }} ({{ $product->unit->short_name ?? '-' }})</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Vendor Supplier</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $product->supplier->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Featured Flag</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">
                            @if($product->is_featured)
                                <span class="text-amber-500 font-bold"><i class="fa-solid fa-star mr-1"></i> Featured Product</span>
                            @else
                                <span class="text-slate-400">Regular</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase">Currency Base</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $product->currency->name ?? '-' }} ({{ $product->currency->code ?? '-' }} - {{ $product->currency->symbol ?? '-' }})</span>
                    </div>
                </div>
            </x-card>

            <!-- Pricing structures -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Commercial pricing card -->
                <x-card title="Commercial Pricing">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Maximum Retail Price (MRP)</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $product->currency->symbol ?? '$' }}{{ number_format($product->mrp, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Purchase Unit Cost</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $product->currency->symbol ?? '$' }}{{ number_format($product->purchase_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-bold text-slate-850 dark:text-slate-100">ERP Selling Price</span>
                            <span class="text-base font-bold text-blue-600 dark:text-blue-400">{{ $product->currency->symbol ?? '$' }}{{ number_format($product->selling_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Associated Tax %</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ number_format($product->tax_percentage, 2) }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Standard Discount %</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ number_format($product->discount_percentage, 2) }}%</span>
                        </div>
                    </div>
                </x-card>

                <!-- Inventory details card -->
                <x-card title="Stock & Alerts">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Current Physical Stock</span>
                            <span class="text-base font-bold {{ ($product->stock->quantity ?? 0) <= $product->minimum_stock_alert ? 'text-red-500' : 'text-emerald-500' }}">
                                {{ number_format($product->stock->quantity ?? 0.00, 2) }} {{ $product->unit->short_name ?? 'Units' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Minimum Alert Quantity</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ number_format($product->minimum_stock_alert, 2) }} {{ $product->unit->short_name ?? 'Units' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 uppercase font-semibold">Opening Stock Quantity</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ number_format($product->opening_stock, 2) }} {{ $product->unit->short_name ?? 'Units' }}</span>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Hardware Specs details card -->
            <x-card title="Hardware Specifications">
                <div class="grid grid-cols-2 gap-y-4 gap-x-6">
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Manufacturer</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->manufacturer ?: '-' }}</span>
                    </div>
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Model Number</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->model_number ?: '-' }}</span>
                    </div>
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Part Number / MPN</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->part_number ?: '-' }}</span>
                    </div>
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Warranty Period</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->warranty ?: '-' }}</span>
                    </div>
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Color Variant</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->color ?: '-' }}</span>
                    </div>
                    <div class="border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Physical Weight</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->weight ?: '-' }}</span>
                    </div>
                    <div class="col-span-2 border-b border-slate-50 dark:border-slate-850 pb-2">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Country of Origin</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $product->country_of_origin ?: '-' }}</span>
                    </div>
                </div>
            </x-card>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Thumbnail click logic for viewer
        $('.thumbnail-item').on('click', function() {
            // Reset borders
            $('.thumbnail-item').removeClass('border-blue-500').addClass('border-slate-200 dark:border-slate-800');
            // Set current
            $(this).addClass('border-blue-500').removeClass('border-slate-200 dark:border-slate-800');
            // Swap src
            $('#primaryViewer').attr('src', $(this).data('src'));
        });
    });
</script>
@endpush
