@extends('layouts.admin')
@section('title', __('messages.edit_product') . ' — ' . $product->name)

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
                    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if (request('from') === 'low_stock')
                <a class="btn btn-outline-warning btn-sm" href="{{ route('stocks.low_stock') }}">
                    <i class="bx bx-error-circle me-1"></i> {{ __('messages.low_stock_alert') }}
                </a>
            @endif
            <a class="btn btn-outline-info btn-sm" href="{{ route('products.show', $product->id) }}">
                <i class="bx bx-show me-1"></i> {{ __('messages.prod_view_btn_txt') }}
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('products.index') }}">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Hero Ribbon --}}
    @php
        $currentQty = (float) ($product->stock->quantity ?? 0);
        $alertQty = (float) ($product->minimum_stock_alert ?? 0);
        $isOut = $currentQty <= 0;
        $isLow = !$isOut && $alertQty > 0 && $currentQty <= $alertQty;
    @endphp
    <div class="card mb-4 border-0 shadow-sm" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body d-flex align-items-center gap-3 px-4 py-3">
            @if ($product->image)
                <img class="rounded-circle flex-shrink-0 border border-2 border-white"
                    src="{{ asset('uploads/products/' . $product->image) }}"
                    style="width:46px;height:46px;object-fit:cover;" alt="{{ $product->name }}">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 border border-2 border-white"
                    style="width:46px;height:46px;background:rgba(255,255,255,.2);">
                    <i class="bx bx-package text-white" style="font-size:1.3rem;"></i>
                </div>
            @endif
            <div>
                <div class="fw-bold text-white">{{ $product->name }}</div>
                <div class="small text-white opacity-75">
                    SKU: {{ $product->code }} &nbsp;&middot;&nbsp;
                    {{ $product->mainCategory->name ?? __('messages.uncategorized') }}
                </div>
            </div>
            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
                @if ($isOut || $isLow)
                    <span class="badge {{ $isOut ? 'bg-danger' : 'bg-warning text-dark' }} fw-semibold">
                        <i class="bx {{ $isOut ? 'bx-x-circle' : 'bx-error-circle' }} me-1"></i>
                        {{ $isOut ? __('messages.out_of_stock') : __('messages.low_stock') }}
                        &mdash; {{ number_format($currentQty, 0) }} {{ $product->unit_code ?? 'PCS' }}
                    </span>
                @endif
                <span class="badge text-primary bg-white fw-semibold">{{ ucfirst($product->status) }}</span>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
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

    <div class="row g-4">

        {{-- ================================================================ --}}
        {{-- LEFT COLUMN col-lg-8                                             --}}
        {{-- ================================================================ --}}
        <div class="col-lg-8">
            <form action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" id="productForm"
                method="POST">
                @csrf
                @method('PUT')

                {{-- Card 1: Basic Info --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle text-primary me-2"></i>{{ __('messages.basic_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.product_name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name"
                                    type="text" required placeholder="{{ __(`messages.ph_product_name`) }}"
                                    value="{{ old('name', $product->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.sku_code_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('code') is-invalid @enderror" name="code"
                                    type="text" required readonly value="{{ old('code', $product->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.barcode_label') }}</label>
                                <input class="form-control @error('barcode') is-invalid @enderror" name="barcode"
                                    type="text" placeholder="{{ __(`messages.ph_barcode`) }}"
                                    value="{{ old('barcode', $product->barcode) }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.main_category_label') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('main_category_id') is-invalid @enderror"
                                    id="main_category_id" name="main_category_id" required>
                                    <option value="">{{ __('messages.select_category') }}</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('main_category_id', $product->main_category_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('main_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.brand_label') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id"
                                    required>
                                    <option value="">{{ __('messages.select_brand') }}</option>
                                    @foreach ($brands as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}>
                                            {{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.unit_name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('unit_name') is-invalid @enderror" name="unit_name"
                                    type="text" required placeholder="{{ __(`messages.ph_unit_name`) }}"
                                    value="{{ old('unit_name', $product->unit_name) }}">
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.unit_code_label') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control @error('unit_code') is-invalid @enderror" name="unit_code"
                                    type="text" required placeholder="{{ __(`messages.ph_unit_code`) }}"
                                    value="{{ old('unit_code', $product->unit_code) }}">
                                @error('unit_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Pricing & Stock Alert --}}
                <div class="card mb-4 shadow-sm" id="pricingStockCard">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-money text-success me-2"></i>{{ __('messages.prod_pricing_stock') }}
                        </h6>
                        <span class="badge bg-label-primary small"
                            id="profitBadge">{{ __('messages.prod_profit_badge') }}: —</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_purchase_price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '₹' }}</span>
                                    <input class="form-control @error('purchase_price') is-invalid @enderror"
                                        id="purchase_price" name="purchase_price" type="number" step="0.01"
                                        min="0" required
                                        value="{{ old('purchase_price', $product->purchase_price) }}">
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_selling_price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '₹' }}</span>
                                    <input class="form-control @error('selling_price') is-invalid @enderror"
                                        id="selling_price" name="selling_price" type="number" step="0.01"
                                        min="0" required
                                        value="{{ old('selling_price', $product->selling_price) }}">
                                    @error('selling_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.discount') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ current_currency()?->symbol ?? '₹' }}</span>
                                    <input class="form-control @error('discount_price_amount') is-invalid @enderror"
                                        id="discount_price_amount" name="discount_price_amount" type="number"
                                        step="0.01" min="0"
                                        value="{{ old('discount_price_amount', $product->discount_price_amount) }}">
                                    @error('discount_price_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.prod_tax_pct') }}</label>
                                <div class="input-group">
                                    <input class="form-control @error('tax_percentage') is-invalid @enderror"
                                        id="tax_percentage" name="tax_percentage" type="number" step="0.01"
                                        min="0" max="100"
                                        oninput="if(parseFloat(this.value)>100){this.value=100;}if(parseFloat(this.value)<0){this.value=0;}"
                                        value="{{ old('tax_percentage', $product->tax_percentage) }}">
                                    <span class="input-group-text">%</span>
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">{{ __('messages.prod_tax_max') }}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">{{ __('messages.prod_min_stock_alert') }}</label>
                                <input class="form-control @error('minimum_stock_alert') is-invalid @enderror"
                                    name="minimum_stock_alert" type="number" step="0.01" min="0"
                                    placeholder="{{ __(`messages.ph_min_stock`) }}"
                                    value="{{ old('minimum_stock_alert', $product->minimum_stock_alert) }}">
                                @error('minimum_stock_alert')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @php
                                $lsQty = (float) ($product->stock->quantity ?? 0);
                                $lsAlert = (float) ($product->minimum_stock_alert ?? 0);
                                $lsOut = $lsQty <= 0;
                                $lsLow = !$lsOut && $lsAlert > 0 && $lsQty <= $lsAlert;
                                $lsNeeded = $lsAlert > $lsQty ? $lsAlert - $lsQty : 0;
                                $lsRestock = $lsNeeded * ($product->purchase_price ?? 0);
                            @endphp
                            @if ($lsOut || $lsLow)
                                <div class="col-12">
                                    <div class="rounded-3 border p-3 d-flex flex-wrap gap-3 align-items-center"
                                        style="background:{{ $lsOut ? 'rgba(234,84,85,.07)' : 'rgba(255,171,0,.06)' }};border-color:{{ $lsOut ? 'rgba(234,84,85,.35)' : 'rgba(255,171,0,.4)' }} !important;">
                                        <span
                                            class="badge {{ $lsOut ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2 fs-6 flex-shrink-0">
                                            <i class="bx {{ $lsOut ? 'bx-x-circle' : 'bx-error-circle' }} me-1"></i>
                                            {{ $lsOut ? __('messages.out_of_stock') : __('messages.low_stock') }}
                                        </span>
                                        <div class="d-flex flex-wrap gap-2 flex-grow-1 align-items-center">
                                            <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                                <div class="fw-bold {{ $lsOut ? 'text-danger' : 'text-warning' }}"
                                                    style="font-size:1.05rem;">{{ number_format($lsQty, 0) }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">Current Qty</div>
                                            </div>
                                            <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                                <div class="fw-bold text-secondary" style="font-size:1.05rem;">
                                                    {{ number_format($lsAlert, 0) }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">Alert Level</div>
                                            </div>
                                            @if ($lsNeeded > 0)
                                                <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                                    <div class="fw-bold text-primary" style="font-size:1.05rem;">
                                                        +{{ number_format($lsNeeded, 0) }}</div>
                                                    <div class="text-muted" style="font-size:.72rem;">Qty Needed</div>
                                                </div>
                                                <div class="text-center px-3 py-1 rounded-2 border bg-white">
                                                    <div class="fw-bold text-primary" style="font-size:1.05rem;">
                                                        {{ format_currency($lsRestock) }}</div>
                                                    <div class="text-muted" style="font-size:.72rem;">Restock Value</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card 3: Technical Specifications --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-chip text-info me-2"></i>{{ __('messages.prod_tech_specs') }}
                            <span
                                class="badge bg-label-secondary small fw-normal ms-2">{{ __('messages.prod_optional') }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_manufacturer') }}</label>
                                <input class="form-control" name="manufacturer" type="text"
                                    placeholder="{{ __(`messages.ph_manufacturer`) }}"
                                    value="{{ old('manufacturer', $product->manufacturer) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_model_number') }}</label>
                                <input class="form-control" name="model_number" type="text"
                                    placeholder="{{ __(`messages.ph_model_number`) }}"
                                    value="{{ old('model_number', $product->model_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_part_serial') }}</label>
                                <input class="form-control" name="part_number" type="text"
                                    placeholder="{{ __(`messages.ph_part_number`) }}" value="{{ old('part_number', $product->part_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_warranty') }}</label>
                                <input class="form-control" name="warranty" type="text" placeholder="{{ __(`messages.ph_warranty`) }}"
                                    value="{{ old('warranty', $product->warranty) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_color') }}</label>
                                <input class="form-control" name="color" type="text" placeholder="{{ __(`messages.ph_color`) }}"
                                    value="{{ old('color', $product->color) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_weight') }}</label>
                                <input class="form-control" name="weight" type="text" placeholder="{{ __(`messages.ph_weight`) }}"
                                    value="{{ old('weight', $product->weight) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">{{ __('messages.prod_country_origin') }}</label>
                                <input class="form-control" name="country_of_origin" type="text"
                                    placeholder="{{ __(`messages.ph_country_origin`) }}"
                                    value="{{ old('country_of_origin', $product->country_of_origin) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Media & Description --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-image text-warning me-2"></i>{{ __('messages.prod_media_desc') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label
                                    class="form-label fw-semibold mb-2">{{ __('messages.prod_primary_image') }}</label>
                                <div class="rounded-3 mb-3 border p-3" style="background:rgba(105,108,255,.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden border"
                                            style="width:90px;height:90px;background:#f0f1ff;">
                                            <img id="imagePreview" class="{{ $product->image ? '' : 'd-none' }}"
                                                src="{{ $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                                alt="{{ $product->name }}"
                                                style="width:90px;height:90px;object-fit:contain;">
                                            <span id="imageNoPreview"
                                                class="{{ $product->image ? 'd-none' : '' }} text-muted small text-center">
                                                <i class="bx bx-image d-block mb-1"
                                                    style="font-size:2rem;opacity:.35;"></i>
                                                No Image
                                            </span>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary d-block mb-2" id="triggerImageBtn"
                                                type="button">
                                                <i class="bx bx-upload me-1"></i> {{ __('messages.prod_upload') }}
                                            </button>
                                            <button
                                                class="btn btn-outline-danger d-block {{ $product->image ? '' : 'd-none' }}"
                                                id="removeImageBtn" type="button">
                                                <i class="bx bx-trash me-1"></i> {{ __('messages.prod_remove') }}
                                            </button>
                                            <div class="form-text mt-1">PNG, JPG, WEBP<br>Max 2 MB</div>
                                        </div>
                                    </div>
                                </div>
                                <input accept="image/*" class="d-none" id="imageInput" name="image" type="file">
                                <input id="remove_image" name="remove_image" type="hidden" value="0">

                                <label
                                    class="form-label fw-semibold mb-2">{{ __('messages.prod_gallery_images') }}</label>
                                <input accept="image/*" class="d-none" id="galleryInput" multiple name="gallery[]"
                                    type="file">
                                <input id="clear_gallery" name="clear_gallery" type="hidden" value="0">
                                <input id="remove_gallery_images" name="remove_gallery_images" type="hidden"
                                    value="">

                                <div class="rounded-3 mb-2 border border-2 border-dashed p-3 text-center"
                                    id="galleryDropZone"
                                    style="border-color:rgba(105,108,255,.35)!important;background:rgba(105,108,255,.03);cursor:pointer;">
                                    <i class="bx bx-cloud-upload d-block text-primary mb-1"
                                        style="font-size:1.8rem;opacity:.6;"></i>
                                    <div class="small text-muted">{{ __('messages.prod_drop_here') }}</div>
                                    <button class="btn btn-outline-primary btn-sm mt-1" id="triggerGalleryBtn"
                                        type="button">
                                        <i class="bx bx-images me-1"></i> {{ __('messages.prod_browse_files') }}
                                    </button>
                                    <div class="form-text mb-0 mt-1">PNG, JPG, WEBP — Max 2 MB each</div>
                                </div>

                                <div id="galleryPreviewContainer"
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:4px;">
                                    @if ($product->gallery && count($product->gallery) > 0)
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
                                                    title="Remove" type="button">&times;</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button
                                    class="btn btn-link text-danger {{ $product->gallery && count($product->gallery) > 0 ? '' : 'd-none' }} mt-1 px-0"
                                    id="clearAllGalleryBtn" type="button">
                                    <i class="bx bx-trash me-1"></i> {{ __('messages.prod_clear_all') }}
                                </button>
                            </div>

                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">{{ __('messages.prod_short_desc') }}</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" name="short_description"
                                        rows="3" placeholder="{{ __(`messages.ph_short_description`) }}">{{ old('short_description', $product->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">{{ __('messages.prod_full_desc') }}</label>
                                    <textarea class="form-control @error('full_description') is-invalid @enderror" name="full_description"
                                        rows="8" placeholder="{{ __(`messages.ph_full_description`) }}">{{ old('full_description', $product->full_description) }}</textarea>
                                    @error('full_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>{{-- /productForm — only wraps left column --}}
        </div>{{-- /col-lg-8 --}}

        {{-- ================================================================ --}}
        {{-- RIGHT COLUMN col-lg-4 — OUTSIDE productForm (avoids nested form) --}}
        {{-- ================================================================ --}}
        <div class="col-lg-4 d-flex flex-column gap-4">

            {{-- Publish Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-send text-primary me-2"></i>{{ __('messages.publish') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- Status toggle — inputs belong to productForm via form= attribute --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">{{ __('messages.status') }}</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="hidden" name="status" value="inactive" form="productForm">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="statusToggle"
                                    name="status" value="active" form="productForm"
                                    {{ old('status', $product->status) === 'active' ? 'checked' : '' }}>
                            </div>
                            <span id="statusLabel"
                                class="fw-semibold {{ old('status', $product->status) === 'active' ? 'text-success' : 'text-danger' }}">
                                {{ old('status', $product->status) === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" form="productForm" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary">
                            <i class="bx bx-show me-1"></i> {{ __('messages.prod_view_product') }}
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Stock Adjust Card (standalone form — NOT nested) --}}
            @can('stocks.create')
                <div class="card shadow-sm border-warning border-opacity-50">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-slider text-warning me-2"></i>{{ __('messages.stock_adjust_title') }}
                        </h6>
                        <span
                            class="badge {{ ($product->stock->quantity ?? 0) > 0 ? 'bg-success' : 'bg-danger' }} fw-semibold"
                            id="qa_stock_badge">
                            {{ number_format($product->stock->quantity ?? 0, 0) }} {{ $product->unit_code ?? 'PCS' }}
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('stocks.store_adjustment') }}" method="POST" id="quickAdjustForm">
                            @csrf
                            <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                            <input type="hidden" name="_redirect_back" value="{{ url()->current() }}">

                            <div class="d-flex gap-2 mb-3">
                                <input type="radio" class="btn-check" name="items[0][type]" id="qa_plus" value="Plus"
                                    checked>
                                <label class="btn btn-outline-success btn-sm w-50 fw-semibold" for="qa_plus">
                                    <i class="bx bx-plus-circle me-1"></i> Plus (+)
                                </label>
                                <input type="radio" class="btn-check" name="items[0][type]" id="qa_minus"
                                    value="Minus">
                                <label class="btn btn-outline-danger btn-sm w-50 fw-semibold" for="qa_minus">
                                    <i class="bx bx-minus-circle me-1"></i> Minus (-)
                                </label>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <button class="btn btn-outline-secondary" type="button" id="qa_dec_btn">
                                    <i class="bx bx-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center fw-bold" name="items[0][quantity]"
                                    id="qa_qty" value="1" min="1" required>
                                <button class="btn btn-outline-secondary" type="button" id="qa_inc_btn">
                                    <i class="bx bx-plus"></i>
                                </button>
                                <span class="input-group-text">{{ $product->unit_code ?? 'PCS' }}</span>
                            </div>

                            <div class="rounded-2 px-3 py-2 mb-3 d-flex align-items-center justify-content-between small"
                                style="background:rgba(105,108,255,.07);border:1px solid rgba(105,108,255,.15);">
                                <span class="text-muted fw-semibold">After Adjustment</span>
                                <span class="fw-bold text-primary" id="qa_preview">
                                    {{ number_format(($product->stock->quantity ?? 0) + 1, 0) }}
                                    {{ $product->unit_code ?? 'PCS' }}
                                </span>
                            </div>

                            <input type="date" class="form-control form-control-sm mb-2" name="transaction_date"
                                value="{{ date('Y-m-d') }}" required>
                            <textarea class="form-control form-control-sm mb-3" name="notes" rows="2"
                                placeholder="{{ __('messages.reason_placeholder') }}"></textarea>

                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-semibold">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_adjustment') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endcan

            {{-- Product Info Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle text-secondary me-2"></i>{{ __('messages.prod_info_card') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0 small">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $product->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                            <span>{{ $product->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                            <span>{{ $product->updated_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fw-semibold">{{ __('messages.current_stock') }}</span>
                            <strong class="{{ ($product->stock->quantity ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($product->stock->quantity ?? 0) }} {{ $product->unit_code ?? 'PCS' }}
                            </strong>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted fw-semibold">{{ __('messages.prod_tab_adjustments') }}</span>
                            <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                                class="text-warning small">
                                <i class="bx bx-slider me-1"></i>{{ __('messages.prod_adjust_stock_btn') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>{{-- /col-lg-4 --}}

    </div>{{-- /row --}}

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Low Stock auto-scroll
            @if (request('from') === 'low_stock')
                setTimeout(function() {
                    var card = document.getElementById('pricingStockCard');
                    if (card) {
                        card.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        card.style.transition = 'box-shadow 0.3s ease';
                        card.style.boxShadow = '0 0 0 3px rgba(255,193,7,0.6)';
                        setTimeout(function() {
                            card.style.boxShadow = '';
                        }, 3000);
                    }
                }, 400);
            @endif

            // Status toggle label
            @php
                $activeLabel = __('messages.active');
                $inactiveLabel = __('messages.inactive');
            @endphp
            $('#statusToggle').on('change', function() {
                if (this.checked) {
                    $('#statusLabel').text('{{ $activeLabel }}').removeClass('text-danger').addClass(
                        'text-success');
                } else {
                    $('#statusLabel').text('{{ $inactiveLabel }}').removeClass('text-success').addClass(
                        'text-danger');
                }
            });

            // Sub-category dynamic load
            @php $selSubCat = old('sub_category_id', $product->sub_category_id ?: ''); @endphp
            @php $selectSubTxt = __('messages.select_sub_category'); @endphp
            var subCategories = {!! json_encode(
                $subCategories->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'main_category_id' => $s->main_category_id]),
            ) !!};
            var selectedSubCategoryId = '{{ $selSubCat }}';

            function loadSubcategories(mainCatId, preselectedId) {
                var $sub = $('#sub_category_id');
                if ($sub.hasClass('select2-hidden-accessible')) $sub.select2('destroy');
                $sub.html('<option value="">{{ $selectSubTxt }}</option>');
                subCategories.filter(function(s) {
                        return s.main_category_id == mainCatId;
                    })
                    .forEach(function(s) {
                        $sub.append('<option value="' + s.id + '"' + (s.id == preselectedId ? ' selected' :
                            '') + '>' + s.name + '</option>');
                    });
                $sub.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: '{{ $selectSubTxt }}'
                });
            }

            $('#main_category_id').on('change', function() {
                loadSubcategories($(this).val(), '');
            });
            var initMainCat = $('#main_category_id').val();
            if (initMainCat) loadSubcategories(initMainCat, selectedSubCategoryId);

            // Profit badge
            @php
                $profitLabel = __('messages.prod_profit_badge');
            @endphp

            function updateProfitBadge() {
                var buy = parseFloat($('#purchase_price').val()) || 0;
                var sell = parseFloat($('#selling_price').val()) || 0;
                if (buy > 0 && sell > 0) {
                    var profit = sell - buy;
                    var pct = ((profit / buy) * 100).toFixed(1);
                    var sign = profit >= 0 ? '+' : '';
                    $('#profitBadge').text('{{ $profitLabel }}: ' + sign + profit.toFixed(2) + ' (' + sign +
                            pct + '%)')
                        .removeClass('bg-label-primary bg-label-danger')
                        .addClass(profit >= 0 ? 'bg-label-primary' : 'bg-label-danger');
                } else {
                    $('#profitBadge').text('{{ $profitLabel }}: —').removeClass('bg-label-danger').addClass(
                        'bg-label-primary');
                }
            }
            $('#purchase_price, #selling_price').on('input', updateProfitBadge);
            updateProfitBadge();

            // Primary image upload
            $('#triggerImageBtn').on('click', function() {
                $('#imageInput').trigger('click');
            });
            $('#imageInput').on('change', function(e) {
                var file = e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    $('#imagePreview').attr('src', ev.target.result).removeClass('d-none');
                    $('#imageNoPreview').addClass('d-none');
                    $('#removeImageBtn').removeClass('d-none');
                    $('#remove_image').val('0');
                };
                reader.readAsDataURL(file);
            });
            $('#removeImageBtn').on('click', function() {
                $('#imagePreview').attr('src', '').addClass('d-none');
                $('#imageNoPreview').removeClass('d-none');
                $('#imageInput').val('');
                $(this).addClass('d-none');
                $('#remove_image').val('1');
            });

            // Gallery management
            var removedGalleryImages = [];
            $('#galleryDropZone').on('click', function() {
                $('#galleryInput').trigger('click');
            });
            $('#triggerGalleryBtn').on('click', function(e) {
                e.stopPropagation();
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
            $('#galleryInput').on('change', function() {
                addGalleryFiles(this.files);
            });

            function addGalleryFiles(files) {
                Array.from(files).forEach(function(file) {
                    if (!file.type.startsWith('image/')) return;
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var $thumb = $(
                            '<div class="position-relative gallery-thumb gallery-new" style="aspect-ratio:1;border-radius:8px;overflow:visible;"><img src="' +
                            ev.target.result +
                            '" class="w-100 h-100" style="object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.12);"><button type="button" class="remove-new-gallery-btn position-absolute d-flex align-items-center justify-content-center bg-danger text-white border-0 rounded-circle shadow" style="width:20px;height:20px;font-size:12px;font-weight:700;line-height:1;padding:0;cursor:pointer;top:-6px;right:-6px;z-index:2;" title="Remove">&times;</button></div>'
                        );
                        $thumb.data('file', file);
                        $('#galleryPreviewContainer').append($thumb);
                        syncClearAllBtn();
                    };
                    reader.readAsDataURL(file);
                });
            }
            $(document).on('click', '.remove-new-gallery-btn', function() {
                $(this).closest('.gallery-new').remove();
                rebuildGalleryInput();
                syncClearAllBtn();
            });
            $(document).on('click', '.remove-gallery-img-btn', function(e) {
                e.preventDefault();
                var $c = $(this).closest('[data-image]');
                removedGalleryImages.push($c.data('image'));
                $('#remove_gallery_images').val(removedGalleryImages.join(','));
                $c.remove();
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
                $('#clearAllGalleryBtn').toggleClass('d-none', $('#galleryPreviewContainer .gallery-thumb')
                    .length === 0);
            }
            syncClearAllBtn();

            function rebuildGalleryInput() {
                var dt = new DataTransfer();
                $('#galleryPreviewContainer .gallery-new').each(function() {
                    var f = $(this).data('file');
                    if (f) dt.items.add(f);
                });
                document.getElementById('galleryInput').files = dt.files;
            }

            // Quick Stock Adjust — +/- and preview
            var currentStock = {{ $product->stock->quantity ?? 0 }};
            @php $unitCode = $product->unit_code ?: 'PCS'; @endphp
            var unitCode = '{{ $unitCode }}';

            function updateQaPreview() {
                var qty = parseInt($('#qa_qty').val()) || 0;
                var type = $('input[name="items[0][type]"]:checked').val();
                var newStock = (type === 'Plus') ? currentStock + qty : currentStock - qty;
                $('#qa_preview').text(newStock.toFixed(0) + ' ' + unitCode);
            }
            $('#qa_inc_btn').on('click', function() {
                var $i = $('#qa_qty');
                $i.val(parseInt($i.val() || 0) + 1).trigger('input');
            });
            $('#qa_dec_btn').on('click', function() {
                var $i = $('#qa_qty');
                var v = parseInt($i.val() || 0);
                if (v > 1) $i.val(v - 1).trigger('input');
            });
            $('#qa_qty, input[name="items[0][type]"]').on('input change', updateQaPreview);
            updateQaPreview();

        }); // end ready
    </script>
@endpush
