@extends('layouts.admin')
@section('title', __('messages.edit_product') . ' � ' . $product->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_product') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('products.show', $product->id) }}">{{ Str::limit($product->name, 30) }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if (request('from') === 'low_stock')
                <a class="btn btn-outline-warning btn-sm" href="{{ route('stocks.low_stock') }}">
                    <i class="bx bx-error-circle me-1"></i> Back to Low Stock Alert
                </a>
            @endif
            <a class="btn btn-outline-secondary" href="{{ route('products.show', $product->id) }}">
                <i class="bx bx-show me-1"></i> View
            </a>
            <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Product identity ribbon --}}
    <div class="card mb-4 border-0 shadow-sm" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body d-flex align-items-center gap-3 px-4 py-3">
            @if ($product->image)
                <img class="rounded-circle flex-shrink-0 border border-2 border-white"
                    src="{{ asset('uploads/products/' . $product->image) }}"
                    style="width:46px;height:46px;object-fit:cover;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 border border-2 border-white"
                    style="width:46px;height:46px;background:rgba(255,255,255,.2);">
                    <i class="bx bx-package text-white" style="font-size:1.3rem;"></i>
                </div>
            @endif
            <div>
                <div class="fw-bold text-white">{{ $product->name }}</div>
                <div class="small text-white opacity-75">SKU: {{ $product->code }} &nbsp;�&nbsp;
                    {{ $product->mainCategory->name ?? __('messages.uncategorized') }}</div>
            </div>
            @php
                $currentQty = (float) ($product->stock->quantity ?? 0);
                $alertQty = (float) ($product->minimum_stock_alert ?? 0);
                $isOut = $currentQty <= 0;
                $isLow = !$isOut && $alertQty > 0 && $currentQty <= $alertQty;
            @endphp
            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
                @if ($isOut || $isLow)
                    <span class="badge {{ $isOut ? 'bg-danger' : 'bg-warning text-dark' }} fw-semibold">
                        <i class="bx {{ $isOut ? 'bx-x-circle' : 'bx-error-circle' }} me-1"></i>
                        {{ $isOut ? __('messages.out_of_stock') : __('messages.low_stock') }}
                        &mdash; {{ number_format($currentQty, 0) }} {{ $product->unit_code ?? 'PCS' }}
                    </span>
                @endif
                <span class="badge text-primary bg-white">{{ ucfirst($product->status) }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>{{ __('messages.fix_errors') }}:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $e)
                    <li class="small">{{ $e }}</li>
                @endforeach
            </ul>
            <button class="btn-close" data-bs-dismiss="alert" type="button"></button>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" id="productForm"
        method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">

            {{-- -- LEFT COLUMN -------------------------------------------------- --}}
            <div class="col-lg-8">

                {{-- -- Card 1: Basic Info ---------------------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-primary me-2"></i>Basic Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Product Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Product Name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="e.g. Core i9 Processor" required type="text"
                                    value="{{ old('name', $product->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- SKU --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">SKU / Code <span class="text-danger">*</span></label>
                                <input class="form-control @error('code') is-invalid @enderror" id="skuInput"
                                    name="code" placeholder="e.g. LPT-i9-001" required type="text"
                                    value="{{ old('code', $product->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Barcode --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Barcode</label>
                                <input class="form-control @error('barcode') is-invalid @enderror" name="barcode"
                                    placeholder="e.g. 8901234567890" type="text"
                                    value="{{ old('barcode', $product->barcode) }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Main Category --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Main Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('main_category_id') is-invalid @enderror"
                                    id="main_category_id" name="main_category_id" required>
                                    <option value="">{{ __('messages.select_category') }}</option>
                                    @foreach ($categories as $c)
                                        <option
                                            {{ old('main_category_id', $product->main_category_id) == $c->id ? 'selected' : '' }}
                                            value="{{ $c->id }}">
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('main_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sub Category --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sub Category</label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror"
                                    id="sub_category_id" name="sub_category_id">
                                    <option value="">{{ __('messages.select_sub_category') }}</option>
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Brand --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Brand <span class="text-danger">*</span></label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id"
                                    required>
                                    <option value="">{{ __('messages.select_brand') }}</option>
                                    @foreach ($brands as $b)
                                        <option {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}
                                            value="{{ $b->id }}">
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Unit Name --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Unit Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('unit_name') is-invalid @enderror" name="unit_name"
                                    placeholder="Piece" required type="text"
                                    value="{{ old('unit_name', $product->unit_name) }}">
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Unit Code --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Unit Code <span class="text-danger">*</span></label>
                                <input class="form-control @error('unit_code') is-invalid @enderror" name="unit_code"
                                    placeholder="PCS" required type="text"
                                    value="{{ old('unit_code', $product->unit_code) }}">
                                @error('unit_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- -- Card 2: Pricing & Stock Alert ---------------------------- --}}
                <div class="card mb-4 shadow-sm" id="pricingStockCard">
                    <div
                        class="card-header border-bottom d-flex align-items-center justify-content-between bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-money text-success me-2"></i>Pricing & Stock Alert
                        </h6>
                        <span class="badge bg-label-primary small" id="profitBadge">Profit: �</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Purchase Price <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">?</span>
                                    <input class="form-control @error('purchase_price') is-invalid @enderror"
                                        id="purchase_price" name="purchase_price" required step="0.01" type="number"
                                        value="{{ old('purchase_price', $product->purchase_price) }}">
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Selling Price <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input class="form-control @error('selling_price') is-invalid @enderror"
                                        id="selling_price" name="selling_price" required step="0.01" type="number"
                                        value="{{ old('selling_price', $product->selling_price) }}">
                                    @error('selling_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Discount Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input class="form-control @error('discount_price_amount') is-invalid @enderror"
                                        id="discount_price_amount" min="0" name="discount_price_amount"
                                        step="0.01" type="number"
                                        value="{{ old('discount_price_amount', $product->discount_price_amount) }}">
                                    @error('discount_price_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Selling Tax %</label>
                                <div class="input-group">
                                    <input class="form-control @error('tax_percentage') is-invalid @enderror"
                                        id="tax_percentage" max="100" min="0" name="tax_percentage"
                                        oninput="if(parseFloat(this.value)>100){this.value=100;}if(parseFloat(this.value)<0){this.value=0;}"
                                        step="0.01" type="number"
                                        value="{{ old('tax_percentage', $product->tax_percentage) }}">
                                    <span class="input-group-text">%</span>
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Max 100%</div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Min Stock Alert</label>
                                <input class="form-control @error('minimum_stock_alert') is-invalid @enderror"
                                    name="minimum_stock_alert" placeholder="e.g. 5" step="0.01" type="number"
                                    value="{{ old('minimum_stock_alert', $product->minimum_stock_alert) }}">
                                @error('minimum_stock_alert')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- ── Low Stock Alert Status Panel ── --}}
                            @php
                                $lsQty     = (float) ($product->stock->quantity ?? 0);
                                $lsAlert   = (float) ($product->minimum_stock_alert ?? 0);
                                $lsOut     = $lsQty <= 0;
                                $lsLow     = !$lsOut && $lsAlert > 0 && $lsQty <= $lsAlert;
                                $lsNeeded  = ($lsAlert > $lsQty) ? ($lsAlert - $lsQty) : 0;
                                $lsRestock = $lsNeeded * ($product->purchase_price ?? 0);
                            @endphp
                            @if ($lsOut || $lsLow)
                            <div class="col-12 mt-1">
                                <div class="rounded-3 border p-3 d-flex flex-wrap gap-3 align-items-center"
                                     style="background:{{ $lsOut ? 'rgba(234,84,85,.07)' : 'rgba(255,171,0,.06)' }};
                                            border-color:{{ $lsOut ? 'rgba(234,84,85,.35)' : 'rgba(255,171,0,.4)' }}!important;">
                                    {{-- Status Badge --}}
                                    <span class="badge {{ $lsOut ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2 fs-6 flex-shrink-0">
                                        <i class="bx {{ $lsOut ? 'bx-x-circle' : 'bx-error-circle' }} me-1"></i>
                                        {{ $lsOut ? __("messages.out_of_stock") : __("messages.low_stock") }}
                                    </span>
                                    {{-- Stats --}}
                                    <div class="d-flex flex-wrap gap-2 flex-grow-1 align-items-center">
                                        <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                            <div class="fw-bold {{ $lsOut ? 'text-danger' : 'text-warning' }}" style="font-size:1.05rem;">{{ number_format($lsQty, 0) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Current Qty</div>
                                        </div>
                                        <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                            <div class="fw-bold text-secondary" style="font-size:1.05rem;">{{ number_format($lsAlert, 0) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Alert Level</div>
                                        </div>
                                        @if ($lsNeeded > 0)
                                        <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                            <div class="fw-bold text-primary" style="font-size:1.05rem;">+{{ number_format($lsNeeded, 0) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Qty Needed</div>
                                        </div>
                                        <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                            <div class="fw-bold text-primary" style="font-size:1.05rem;">{{ format_currency($lsRestock) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">Restock Value</div>
                                        </div>
                                        @endif
                                    </div>
                                    {{-- Adjust Stock Button --}}
                                    <div class="flex-shrink-0">
                                        @can("stocks.create")
                                        <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                                           class="btn btn-sm {{ $lsOut ? 'btn-danger' : 'btn-warning' }} fw-semibold">
                                            <i class="bx bx-plus-medical me-1"></i> Adjust Stock
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- -- Card 3: Technical Specifications ------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-chip text-info me-2"></i>Technical Specifications
                            <span class="badge bg-label-secondary small fw-normal ms-2">Optional</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Manufacturer</label>
                                <input class="form-control" name="manufacturer" placeholder="e.g. Intel, Asus"
                                    type="text" value="{{ old('manufacturer', $product->manufacturer) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Model Number</label>
                                <input class="form-control" name="model_number" placeholder="e.g. ROG-STRIX-Z790"
                                    type="text" value="{{ old('model_number', $product->model_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Part / Serial No.</label>
                                <input class="form-control" name="part_number" placeholder="e.g. 90MB1CS0"
                                    type="text" value="{{ old('part_number', $product->part_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Warranty</label>
                                <input class="form-control" name="warranty" placeholder="e.g. 3 Years" type="text"
                                    value="{{ old('warranty', $product->warranty) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Color</label>
                                <input class="form-control" name="color" placeholder="e.g. Space Grey" type="text"
                                    value="{{ old('color', $product->color) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Weight</label>
                                <input class="form-control" name="weight" placeholder="e.g. 1.2 kg" type="text"
                                    value="{{ old('weight', $product->weight) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Country of Origin</label>
                                <input class="form-control" name="country_of_origin" placeholder="e.g. Taiwan"
                                    type="text" value="{{ old('country_of_origin', $product->country_of_origin) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- -- Card 4: Media & Description ------------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-image text-warning me-2"></i>Media & Description
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- -- Left: Images -- --}}
                            <div class="col-md-5">

                                {{-- Primary Image --}}
                                <label class="form-label fw-semibold mb-2">Primary Image</label>
                                <div class="rounded-3 mb-3 border p-3" style="background:rgba(105,108,255,.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden border"
                                            style="width:90px;height:90px;background:#f0f1ff;">
                                            <img class="{{ $product->image ? '' : 'd-none' }}" id="imagePreview"
                                                src="{{ $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                                style="width:90px;height:90px;object-fit:contain;">
                                            <span
                                                class="{{ $product->image ? 'd-none' : '' }} text-muted small text-center"
                                                id="imageNoPreview">
                                                <i class="bx bx-image d-block mb-1"
                                                    style="font-size:2rem;opacity:.35;"></i>
                                                No Image
                                            </span>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary d-block mb-2" id="triggerImageBtn"
                                                type="button">
                                                <i class="bx bx-upload me-1"></i> Upload
                                            </button>
                                            <button
                                                class="btn btn-outline-danger d-block {{ $product->image ? '' : 'd-none' }}"
                                                id="removeImageBtn" type="button">
                                                <i class="bx bx-trash me-1"></i> Remove
                                            </button>
                                            <div class="form-text mt-1">PNG, JPG, WEBP<br>Max 2 MB</div>
                                        </div>
                                    </div>
                                </div>
                                <input accept="image/*" class="d-none" id="imageInput" name="image" type="file">
                                <input id="remove_image" name="remove_image" type="hidden" value="0">

                                {{-- Gallery Images --}}
                                <label class="form-label fw-semibold mb-2">Gallery Images</label>
                                <input accept="image/*" class="d-none" id="galleryInput" multiple name="gallery[]"
                                    type="file">
                                <input id="clear_gallery" name="clear_gallery" type="hidden" value="0">
                                <input id="remove_gallery_images" name="remove_gallery_images" type="hidden"
                                    value="">

                                {{-- Drop Zone --}}
                                <div class="rounded-3 mb-2 border border-2 border-dashed p-3 text-center"
                                    id="galleryDropZone"
                                    style="border-color:rgba(105,108,255,.35)!important;background:rgba(105,108,255,.03);cursor:pointer;transition:border-color .2s,background .2s;">
                                    <i class="bx bx-cloud-upload d-block text-primary mb-1"
                                        style="font-size:1.8rem;opacity:.6;"></i>
                                    <div class="small text-muted">Drop images here or</div>
                                    <button class="btn btn-outline-primary mt-1" id="triggerGalleryBtn" type="button">
                                        <i class="bx bx-images me-1"></i> Browse Files
                                    </button>
                                    <div class="form-text mb-0 mt-1">PNG, JPG, WEBP � Max 2MB each</div>
                                </div>

                                {{-- Thumbnail Grid --}}
                                <div id="galleryPreviewContainer"
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:4px;">
                                    @if ($product->gallery && count($product->gallery) > 0)
                                        @foreach ($product->gallery as $galImg)
                                            <div class="position-relative gallery-thumb" data-image="{{ $galImg }}"
                                                style="aspect-ratio:1;border-radius:8px;overflow:visible;">
                                                <img class="w-100 h-100" src="{{ asset('uploads/products/' . $galImg) }}"
                                                    style="object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.12);">
                                                <button
                                                    class="remove-gallery-img-btn position-absolute d-flex align-items-center justify-content-center bg-danger rounded-circle border-0 text-white shadow"
                                                    style="width:20px;height:20px;font-size:12px;font-weight:700;line-height:1;padding:0;cursor:pointer;top:-6px;right:-6px;z-index:2;"
                                                    title="Remove" type="button">�</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button
                                    class="btn btn-link text-danger {{ $product->gallery && count($product->gallery) > 0 ? '' : 'd-none' }} mt-1 px-0"
                                    id="clearAllGalleryBtn" type="button">
                                    <i class="bx bx-trash me-1"></i> Clear All
                                </button>
                            </div>

                            {{-- -- Right: Descriptions -- --}}
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Short Description</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" name="short_description"
                                        placeholder="Key features summary..." rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Full Description / Specifications</label>
                                    <textarea class="form-control @error('full_description') is-invalid @enderror" name="full_description"
                                        placeholder="Complete specifications, box contents..." rows="8">{{ old('full_description', $product->full_description) }}</textarea>
                                    @error('full_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- -- RIGHT COLUMN ------------------------------------------------- --}}
            <div class="col-lg-4">

                {{-- -- Card: Publish -------------------------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-send text-primary me-2"></i>Publish
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Status toggle --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input name="status" type="hidden" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input {{ old('status', $product->status) === 'active' ? 'checked' : '' }}
                                        class="form-check-input" id="statusToggle" name="status" role="switch"
                                        type="checkbox" value="active">
                                </div>
                                <span
                                    class="fw-semibold {{ old('status', $product->status) === 'active' ? 'text-success' : 'text-danger' }}"
                                    id="statusLabel">
                                    {{ old('status', $product->status) === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i> {{ __('messages.update') }}
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('products.show', $product->id) }}">
                                <i class="bx bx-show me-1"></i> View Product
                            </a>
                            <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- -- Card: Quick Info ------------------------------------------ --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="bx bx-info-circle text-info me-2"></i>Product Info
                        </h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.created') }}</span>
                                <strong class="text-body">{{ $product->created_at->format('d M Y') }}</strong>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.last_updated') }}</span>
                                <strong class="text-body">{{ $product->updated_at->format('d M Y') }}</strong>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.current_stock') }}</span>
                                <strong
                                    class="{{ ($product->stock->quantity ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($product->stock->quantity ?? 0) }} {{ $product->unit_code }}
                                </strong>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>{{ __('messages.stock_note') }}</span>
                                <span class="text-warning small">Adjust via Stock page</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}

        </div>{{-- end row --}}
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // -- Low Stock Alert: Auto-scroll to Pricing & Stock Alert card ---------
            @if (request('from') === 'low_stock')
                setTimeout(function() {
                    const card = document.getElementById('pricingStockCard');
                    if (card) {
                        card.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        // Flash highlight
                        card.style.transition = 'box-shadow 0.3s ease, border 0.3s ease';
                        card.style.boxShadow = '0 0 0 3px rgba(255, 193, 7, 0.6)';
                        card.style.border = '2px solid #ffc107';
                        setTimeout(function() {
                            card.style.boxShadow = '';
                            card.style.border = '';
                        }, 3000);
                    }
                }, 400);
            @endif

            // -- Status toggle label ------------------------------------------------
            $('#statusToggle').on('change', function() {
                if (this.checked) {
                    $('#statusLabel').text('{{ __('messages.active') }}').removeClass('text-danger')
                        .addClass('text-success');
                } else {
                    $('#statusLabel').text('{{ __('messages.inactive') }}').removeClass('text-success')
                        .addClass('text-danger');
                }
            });

            // -- Sub-category dynamic load ------------------------------------------
            const subCategories = @json($subCategories);
            const selectedSubCategoryId = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";

            function loadSubcategories(mainCategoryId, preselectedId) {
                const subSelect = $('#sub_category_id');
                if (subSelect.hasClass('select2-hidden-accessible')) {
                    subSelect.select2('destroy');
                }
                subSelect.html('<option value="">{{ __('messages.select_sub_category') }}</option>');
                subCategories
                    .filter(s => s.main_category_id == mainCategoryId)
                    .forEach(s => {
                        subSelect.append(
                            `<option value="${s.id}" ${s.id == preselectedId ? 'selected' : ''}>${s.name}</option>`
                        );
                    });
                subSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select Sub Category',
                });
            }

            $('#main_category_id').on('change', function() {
                loadSubcategories($(this).val());
            });

            const initialMainCatId = $('#main_category_id').val();
            if (initialMainCatId) {
                loadSubcategories(initialMainCatId, selectedSubCategoryId);
            }

            // -- Profit badge -------------------------------------------------------
            function updateProfitBadge() {
                const buy = parseFloat($('#purchase_price').val()) || 0;
                const sell = parseFloat($('#selling_price').val()) || 0;
                if (buy > 0 && sell > 0) {
                    const profit = sell - buy;
                    const pct = ((profit / buy) * 100).toFixed(1);
                    const sign = profit >= 0 ? '+' : '';
                    $('#profitBadge')
                        .text(`Profit: ${sign}?${profit.toFixed(2)} (${sign}${pct}%)`)
                        .removeClass('bg-label-primary bg-label-danger')
                        .addClass(profit >= 0 ? 'bg-label-primary' : 'bg-label-danger');
                } else {
                    $('#profitBadge').text('Profit: �');
                }
            }
            $('#purchase_price, #selling_price').on('input', updateProfitBadge);
            updateProfitBadge();

            // -- Primary image upload -----------------------------------------------
            $('#triggerImageBtn').on('click', () => $('#imageInput').trigger('click'));

            $('#imageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
                        $('#imageNoPreview').addClass('d-none');
                        $('#removeImageBtn').removeClass('d-none');
                        $('#remove_image').val('0');
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#removeImageBtn').on('click', function() {
                $('#imagePreview').attr('src', '').addClass('d-none');
                $('#imageNoPreview').removeClass('d-none');
                $('#imageInput').val('');
                $(this).addClass('d-none');
                $('#remove_image').val('1');
            });

            // -- Gallery management -------------------------------------------------
            let removedGalleryImages = [];

            $('#triggerGalleryBtn').on('click', function(e) {
                e.stopPropagation();
                $('#galleryInput').trigger('click');
            });

            $('#galleryDropZone').on('click', function() {
                $('#galleryInput').trigger('click');
            });

            $('#galleryDropZone').on('dragover', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': '#696cff',
                    'background': 'rgba(105,108,255,.08)'
                });
            }).on('dragleave drop', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': '',
                    'background': ''
                });
                if (e.type === 'drop') addGalleryFiles(e.originalEvent.dataTransfer.files);
            });

            function addGalleryFiles(files) {
                Array.from(files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const thumb = $(`
                    <div class="position-relative gallery-thumb gallery-new" style="aspect-ratio:1;border-radius:8px;overflow:visible;">
                        <img src="${e.target.result}" class="w-100 h-100"
                            style="object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.12);">
                        <button type="button"
                            class="remove-new-gallery-btn position-absolute d-flex align-items-center justify-content-center bg-danger text-white border-0 rounded-circle shadow"
                            style="width:20px;height:20px;font-size:12px;font-weight:700;line-height:1;padding:0;cursor:pointer;top:-6px;right:-6px;z-index:2;"
                            title="Remove">�</button>
                    </div>`);
                        thumb.data('file', file);
                        $('#galleryPreviewContainer').append(thumb);
                        syncClearAllBtn();
                    };
                    reader.readAsDataURL(file);
                });
            }

            $('#galleryInput').on('change', function() {
                addGalleryFiles(this.files);
            });

            $(document).on('click', '.remove-new-gallery-btn', function() {
                $(this).closest('.gallery-new').remove();
                rebuildGalleryInput();
                syncClearAllBtn();
            });

            $(document).on('click', '.remove-gallery-img-btn', function(e) {
                e.preventDefault();
                const container = $(this).closest('[data-image]');
                removedGalleryImages.push(container.data('image'));
                $('#remove_gallery_images').val(removedGalleryImages.join(','));
                container.remove();
                syncClearAllBtn();
            });

            $('#clearAllGalleryBtn').on('click', function(e) {
                e.preventDefault();
                $('#galleryPreviewContainer').empty();
                $('#clear_gallery').val('1');
                removedGalleryImages = [];
                $('#remove_gallery_images').val('');
                $('#galleryInput').val('');
                syncClearAllBtn();
            });

            function syncClearAllBtn() {
                const has = $('#galleryPreviewContainer .gallery-thumb').length > 0;
                $('#clearAllGalleryBtn').toggleClass('d-none', !has);
            }
            syncClearAllBtn();

            function rebuildGalleryInput() {
                const dt = new DataTransfer();
                $('#galleryPreviewContainer .gallery-new').each(function() {
                    const file = $(this).data('file');
                    if (file) dt.items.add(file);
                });
                document.getElementById('galleryInput').files = dt.files;
            }

        });
    </script>
@endpush
