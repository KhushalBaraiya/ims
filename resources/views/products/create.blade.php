@extends('layouts.admin')
@section('title', isset($isCopy) ? 'Copy Product' : __('messages.create_product'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($isCopy) ? 'Copy Product' : __('messages.create_product') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($isCopy) ? 'Copy' : __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card mb-4 border-0 shadow-sm" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body d-flex align-items-center flex-wrap gap-3 px-4 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 border border-2 border-white"
                style="width:46px;height:46px;background:rgba(255,255,255,.2);">
                <i class="bx bx-package text-white" style="font-size:1.3rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-white">{{ isset($isCopy) ? 'Copy Product' : __('messages.create_product') }}</div>
                <div class="small text-white opacity-75">Fill in the details below to
                    {{ isset($isCopy) ? 'copy this product' : 'add a new product to your catalog' }}</div>
            </div>
            <span class="badge text-primary fw-semibold bg-white">
                <i class="bx bx-plus me-1"></i>{{ isset($isCopy) ? 'Copy' : 'New Product' }}
            </span>
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

    <form action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm" method="POST">
        @csrf
        <div class="row g-4">

            {{-- -- LEFT COLUMN -------------------------------------------------- --}}
            <div class="col-lg-8">

                {{-- -- Card 1: Basic Info ---------------------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-primary me-2"></i>{{ __('messages.basic_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Product Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.product_name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="{{ __('messages.ph_product_name') }}" required type="text"
                                    value="{{ old('name', $product->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.sku_code_label') }} <span class="text-danger">*</span>
                                    <span
                                        class="text-muted small fw-normal ms-1">({{ __('messages.auto_or_manual') }})</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control @error('code') is-invalid @enderror" id="skuInput"
                                        name="code" placeholder="{{ __(`messages.ph_sku_code`) }}" required type="text"
                                        value="{{ old('code', $product->code ?? '') }}">
                                    <button class="btn btn-outline-secondary" id="generateSkuBtn" title="Auto-generate SKU"
                                        type="button">
                                        <i class="bx bx-refresh"></i>
                                    </button>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Barcode --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.barcode_label') }}</label>
                                <input class="form-control @error('barcode') is-invalid @enderror" name="barcode"
                                    placeholder="{{ __('messages.ph_barcode') }}" type="text"
                                    value="{{ old('barcode', $product->barcode ?? '') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Main Category --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.main_category_label') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('main_category_id') is-invalid @enderror"
                                    id="main_category_id" name="main_category_id" required>
                                    <option value="">{{ __('messages.select_category') }}</option>
                                    @foreach ($categories as $c)
                                        <option
                                            {{ old('main_category_id', $product->main_category_id ?? '') == $c->id ? 'selected' : '' }}
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
                                <label class="form-label fw-semibold">{{ __('messages.sub_category_label') }}</label>
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
                                <label class="form-label fw-semibold">{{ __('messages.brand_label') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id"
                                    required>
                                    <option value="">{{ __('messages.select_brand') }}</option>
                                    @foreach ($brands as $b)
                                        <option {{ old('brand_id', $product->brand_id ?? '') == $b->id ? 'selected' : '' }}
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
                                <label class="form-label fw-semibold">{{ __('messages.unit_name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('unit_name') is-invalid @enderror" name="unit_name"
                                    placeholder="{{ __('messages.ph_unit_name') }}" required type="text"
                                    value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}">
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Unit Code --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.unit_code_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('unit_code') is-invalid @enderror" name="unit_code"
                                    placeholder="{{ __('messages.ph_unit_code') }}" required type="text"
                                    value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}">
                                @error('unit_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- -- Card 2: Pricing & Stock Alert ---------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div
                        class="card-header border-bottom d-flex align-items-center justify-content-between bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-money text-success me-2"></i>Pricing & Stock Alert
                        </h6>
                        <span class="badge bg-label-primary small" id="profitBadge">Profit: ?</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Purchase Price --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_purchase_price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '?' }}</span>
                                    <input class="form-control @error('purchase_price') is-invalid @enderror"
                                        id="purchase_price" name="purchase_price" required step="0.01" type="number"
                                        value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}">
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Selling Price --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_selling_price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '?' }}</span>
                                    <input class="form-control @error('selling_price') is-invalid @enderror"
                                        id="selling_price" name="selling_price" required step="0.01" type="number"
                                        value="{{ old('selling_price', $product->selling_price ?? '0.00') }}">
                                    @error('selling_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Discount Amount --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.prod_discount_amount') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '?' }}</span>
                                    <input class="form-control @error('discount_price_amount') is-invalid @enderror"
                                        id="discount_price_amount" min="0" name="discount_price_amount"
                                        step="0.01" type="number"
                                        value="{{ old('discount_price_amount', $product->discount_price_amount ?? '0.00') }}">
                                    @error('discount_price_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Selling Tax % --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.prod_selling_tax_pct') }}</label>
                                <div class="input-group">
                                    <input class="form-control @error('tax_percentage') is-invalid @enderror"
                                        id="tax_percentage" max="100" min="0" name="tax_percentage"
                                        oninput="if(parseFloat(this.value)>100){this.value=100;}if(parseFloat(this.value)<0){this.value=0;}"
                                        step="0.01" type="number"
                                        value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}">
                                    <span class="input-group-text">%</span>
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">{{ __('messages.prod_tax_max') }}</div>
                            </div>

                            {{-- Min Stock Alert --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.prod_min_stock_alert') }}</label>
                                <input class="form-control @error('minimum_stock_alert') is-invalid @enderror"
                                    name="minimum_stock_alert" placeholder="{{ __('messages.ph_min_stock') }}"
                                    step="0.01" type="number"
                                    value="{{ old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00') }}">
                                @error('minimum_stock_alert')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- -- Card 3: Opening Stock (create / copy only) ---------------- --}}
                @if (!$product->exists || isset($isCopy))
                    <div class="card mb-4 shadow-sm">
                        <div
                            class="card-header border-bottom d-flex align-items-center justify-content-between bg-transparent py-3">
                            <h6 class="fw-semibold mb-0">
                                <i class="bx bx-box text-warning me-2"></i>{{ __('messages.prod_opening_stock_card') }}
                            </h6>
                            <span class="badge bg-label-warning small">{{ __('messages.prod_create_only_badge') }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check form-switch mb-0">
                                <input {{ old('add_opening_stock') ? 'checked' : '' }} class="form-check-input"
                                    id="addOpeningStock" name="add_opening_stock" role="switch" type="checkbox"
                                    value="1">
                                <label class="form-check-label fw-semibold" for="addOpeningStock">
                                    <i class="bx bx-plus-circle text-success me-1"></i>
                                    {{ __('messages.add_initial_stock_hint') }}
                                </label>
                                <div class="form-text">{{ __('messages.prod_opening_stock_desc') }}</div>
                            </div>

                            <div class="{{ old('add_opening_stock') ? '' : 'd-none' }} mt-3" id="openingStockBox">
                                <div class="row g-3 align-items-end">

                                    {{-- Supplier --}}
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">{{ __('messages.prod_supplier_label') }}
                                            <span class="text-danger">*</span></label>
                                        <select class="form-select @error('supplier_id') is-invalid @enderror"
                                            id="supplier_id" name="supplier_id">
                                            <option value="">{{ __('messages.select_supplier') }}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                                    value="{{ $supplier->id }}">
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Opening Qty --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">{{ __('messages.prod_opening_qty_label') }}
                                            <span class="text-danger">*</span></label>
                                        <input class="form-control @error('initial_qty') is-invalid @enderror"
                                            id="initial_qty" min="0.01" name="initial_qty"
                                            placeholder="{{ __('messages.ph_opening_qty') }}" step="0.01"
                                            type="number" value="{{ old('initial_qty', '') }}">
                                        @error('initial_qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Live total preview --}}
                                    <div class="col-md-4">
                                        <div class="alert alert-info d-flex align-items-center mb-0 gap-2 px-3 py-2">
                                            <i class="bx bx-calculator text-info fs-5"></i>
                                            <div>
                                                <div class="small fw-semibold text-info">Purchase Total</div>
                                                <div class="fw-bold" id="openingStockTotal">?</div>
                                                <div class="form-text mb-0">Qty ? Purchase Price</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- -- Card 4: Technical Specifications ------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-chip text-info me-2"></i>{{ __('messages.prod_tech_specs') }}
                            <span
                                class="badge bg-label-secondary small fw-normal ms-2">{{ __('messages.prod_optional') }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_manufacturer') }}</label>
                                <input class="form-control" name="manufacturer" placeholder="{{ __(`messages.ph_manufacturer`) }}"
                                    type="text" value="{{ old('manufacturer', $product->manufacturer ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_model_number') }}</label>
                                <input class="form-control" name="model_number" placeholder="{{ __(`messages.ph_model_number`) }}"
                                    type="text" value="{{ old('model_number', $product->model_number ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_part_serial') }}</label>
                                <input class="form-control" name="part_number" placeholder="{{ __(`messages.ph_part_number`) }}"
                                    type="text" value="{{ old('part_number', $product->part_number ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_warranty') }}</label>
                                <input class="form-control" name="warranty" placeholder="{{ __(`messages.ph_warranty`) }}" type="text"
                                    value="{{ old('warranty', $product->warranty ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_color') }}</label>
                                <input class="form-control" name="color" placeholder="{{ __(`messages.ph_color`) }}" type="text"
                                    value="{{ old('color', $product->color ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_weight') }}</label>
                                <input class="form-control" name="weight" placeholder="{{ __(`messages.ph_weight`) }}" type="text"
                                    value="{{ old('weight', $product->weight ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_country_origin') }}</label>
                                <input class="form-control" name="country_of_origin" placeholder="{{ __(`messages.ph_country_origin`) }}"
                                    type="text"
                                    value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- -- Card 5: Media & Description ------------------------------- --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-transparent py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-image text-warning me-2"></i>{{ __('messages.prod_media_desc') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- -- Left: Images -- --}}
                            <div class="col-md-5">

                                {{-- Primary Image --}}
                                <label
                                    class="form-label fw-semibold mb-2">{{ __('messages.prod_primary_image') }}</label>
                                <div class="rounded-3 mb-3 border p-3" style="background:rgba(105,108,255,.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Preview box --}}
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden border"
                                            style="width:90px;height:90px;background:#f0f1ff;">
                                            <img class="{{ isset($product) && $product->image ? '' : 'd-none' }}"
                                                id="imagePreview"
                                                src="{{ isset($product) && $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                                style="width:90px;height:90px;object-fit:contain;">
                                            <span
                                                class="{{ isset($product) && $product->image ? 'd-none' : '' }} text-muted small text-center"
                                                id="imageNoPreview">
                                                <i class="bx bx-image d-block mb-1"
                                                    style="font-size:2rem;opacity:.35;"></i>
                                                No Image
                                            </span>
                                        </div>
                                        {{-- Buttons --}}
                                        <div>
                                            <button class="btn btn-primary d-block mb-2" id="triggerImageBtn"
                                                type="button">
                                                <i class="bx bx-upload me-1"></i> {{ __('messages.prod_upload') }}
                                            </button>
                                            <button
                                                class="btn btn-outline-danger d-block {{ isset($product) && $product->image ? '' : 'd-none' }}"
                                                id="removeImageBtn" type="button">
                                                <i class="bx bx-trash me-1"></i> {{ __('messages.prod_remove') }}
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
                                    <div class="form-text mb-0 mt-1">PNG, JPG, WEBP ? Max 2MB each</div>
                                </div>

                                {{-- Thumbnail Grid --}}
                                <div id="galleryPreviewContainer"
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:4px;">
                                    @if (isset($product) && $product->gallery && count($product->gallery) > 0)
                                        @foreach ($product->gallery as $galImg)
                                            <div class="position-relative gallery-thumb"
                                                data-image="{{ $galImg }}"
                                                style="aspect-ratio:1;border-radius:8px;overflow:visible;">
                                                <img class="w-100 h-100"
                                                    src="{{ asset('uploads/products/' . $galImg) }}"
                                                    style="object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.12);">
                                                <button
                                                    class="remove-gallery-img-btn position-absolute d-flex align-items-center justify-content-center bg-danger rounded-circle border-0 text-white shadow"
                                                    style="width:20px;height:20px;font-size:12px;font-weight:700;line-height:1;padding:0;cursor:pointer;top:-6px;right:-6px;z-index:2;"
                                                    title="Remove" type="button">?</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button class="btn btn-link text-danger d-none mt-2 px-0" id="clearAllGalleryBtn"
                                    type="button">
                                    <i class="bx bx-trash me-1"></i> Clear All
                                </button>
                            </div>

                            {{-- Descriptions --}}
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Short Description</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" name="short_description"
                                        placeholder="{{ __(`messages.ph_short_description`) }}" rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Full Description / Specifications</label>
                                    <textarea class="form-control @error('full_description') is-invalid @enderror" name="full_description"
                                        placeholder="{{ __(`messages.ph_full_description`) }}" rows="8">{{ old('full_description', $product->full_description ?? '') }}</textarea>
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
                                    <input {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                                        class="form-check-input" id="statusToggle" name="status" role="switch"
                                        type="checkbox" value="active">
                                </div>
                                <span
                                    class="fw-semibold {{ old('status', 'active') === 'active' ? 'text-success' : 'text-danger' }}"
                                    id="statusLabel">
                                    {{ old('status', 'active') === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($isCopy) ? 'Save Copied Product' : __('messages.save') }}
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- -- Card: Quick Tips ------------------------------------------ --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="bx bx-bulb text-warning me-2"></i>Quick Tips
                        </h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="bx bx-check-circle text-success me-1"></i>SKU must be unique
                                across all products.</li>
                            <li class="mb-2"><i class="bx bx-check-circle text-success me-1"></i>Select Main Category
                                first to load Sub Categories.</li>
                            <li class="mb-2"><i class="bx bx-check-circle text-success me-1"></i>Enable Opening Stock to
                                auto-create a purchase record.</li>
                            <li class="mb-0"><i class="bx bx-check-circle text-success me-1"></i>Primary image max size
                                is 2MB (PNG/JPG/WEBP).</li>
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

            // -- SKU Auto-Generate --------------------------------------------------
            let skuManuallyEdited = $('#skuInput').val().trim() !== '';

            function generateSku(nameVal) {
                // Take first 4 letters of name (uppercase, letters only)
                const prefix = (nameVal || '')
                    .toUpperCase()
                    .replace(/[^A-Z0-9]/g, '')
                    .substring(0, 4)
                    .padEnd(3, 'X');

                // 4-char random alphanumeric
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let rand = '';
                for (let i = 0; i < 4; i++) {
                    rand += chars.charAt(Math.floor(Math.random() * chars.length));
                }

                return 'PRD-' + prefix + '-' + rand;
            }

            // Generate on wand button click (always regenerate)
            $('#generateSkuBtn').on('click', function() {
                const name = $('input[name="name"]').val().trim();
                const sku = generateSku(name);
                $('#skuInput').val(sku).trigger('input');
                skuManuallyEdited = false;
                // Flash feedback
                $('#skuInput').addClass('is-valid');
                setTimeout(() => $('#skuInput').removeClass('is-valid'), 1500);
            });

            // Auto-suggest when product name is typed (only if SKU not manually touched)
            $('input[name="name"]').on('input', function() {
                if (!skuManuallyEdited && $('#skuInput').val().trim() === '') {
                    $('#skuInput').val(generateSku($(this).val().trim()));
                }
            });

            // Mark as manually edited if user types in SKU field directly
            $('#skuInput').on('input', function() {
                skuManuallyEdited = true;
            });


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
                    $('#profitBadge').text('Profit: ?');
                }
            }
            $('#purchase_price, #selling_price').on('input', updateProfitBadge);
            updateProfitBadge();

            // -- Opening stock toggle -----------------------------------------------
            function syncOpeningStockRequired(checked) {
                $('#supplier_id').prop('required', checked);
                $('#initial_qty').prop('required', checked);
                if (!checked) {
                    $('#supplier_id').val('').trigger('change');
                    $('#initial_qty').val('');
                    $('#openingStockTotal').text('?');
                }
            }

            $('#addOpeningStock').on('change', function() {
                const checked = $(this).is(':checked');
                $('#openingStockBox').toggleClass('d-none', !checked);
                syncOpeningStockRequired(checked);
            });

            if ($('#addOpeningStock').is(':checked')) {
                syncOpeningStockRequired(true);
            }

            // -- Opening stock live total -------------------------------------------
            function updateOpeningTotal() {
                const qty = parseFloat($('#initial_qty').val()) || 0;
                const price = parseFloat($('#purchase_price').val()) || 0;
                if (qty > 0 && price > 0) {
                    const sym = '{{ addslashes(optional(current_currency())->symbol ?? '?') }}';
                    $('#openingStockTotal').text(sym + (qty * price).toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }));
                } else {
                    $('#openingStockTotal').text('?');
                }
            }
            $('#initial_qty, #purchase_price').on('input', updateOpeningTotal);
            updateOpeningTotal();

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

            // Trigger hidden file input via button or clicking drop zone
            $('#triggerGalleryBtn, #galleryDropZone').on('click', function(e) {
                if ($(e.target).is('#galleryDropZone') || $(e.target).closest('#galleryDropZone').length) {
                    $('#galleryInput').trigger('click');
                }
            });
            $('#triggerGalleryBtn').on('click', function(e) {
                e.stopPropagation();
                $('#galleryInput').trigger('click');
            });

            // Drag-over highlight
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
                if (e.type === 'drop') {
                    addGalleryFiles(e.originalEvent.dataTransfer.files);
                }
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
                                    title="Remove">?</button>
                            </div>`);
                        thumb.data('file', file);
                        $('#galleryPreviewContainer').append(thumb);
                        syncClearAllBtn();
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Show new-file previews below existing ones
            $('#galleryInput').on('change', function() {
                addGalleryFiles(this.files);
            });

            // Remove a NEW (not-yet-uploaded) thumbnail ? rebuild the FileList
            $(document).on('click', '.remove-new-gallery-btn', function() {
                $(this).closest('.gallery-new').remove();
                rebuildGalleryInput();
                syncClearAllBtn();
            });

            // Remove a SAVED gallery image
            $(document).on('click', '.remove-gallery-img-btn', function(e) {
                e.preventDefault();
                const container = $(this).closest('[data-image]');
                removedGalleryImages.push(container.data('image'));
                $('#remove_gallery_images').val(removedGalleryImages.join(','));
                container.remove();
                syncClearAllBtn();
            });

            // Clear all (saved + new)
            $('#clearAllGalleryBtn').on('click', function(e) {
                e.preventDefault();
                $('#galleryPreviewContainer').empty();
                $('#clear_gallery').val('1');
                removedGalleryImages = [];
                $('#remove_gallery_images').val('');
                $('#galleryInput').val('');
                syncClearAllBtn();
            });

            // Show/hide "Clear All" based on thumb count
            function syncClearAllBtn() {
                const has = $('#galleryPreviewContainer .gallery-thumb').length > 0;
                $('#clearAllGalleryBtn').toggleClass('d-none', !has);
            }
            syncClearAllBtn();

            // Rebuild DataTransfer so file input reflects only remaining new files
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
