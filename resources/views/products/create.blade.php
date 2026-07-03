@extends('layouts.admin')
@section('title', isset($isCopy) ? 'Copy Product' : __('messages.create_product'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($isCopy) ? 'Copy Product' : __('messages.create_product') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($isCopy) ? 'Copy' : __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert  -danger alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>{{ __('messages.fix_errors') }}:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $e)
                    <li class="small">{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="productForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">

            {{-- ══ LEFT COLUMN ══════════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- ── Card 1: Basic Info ──────────────────────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-primary"></i>Basic Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Product Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Product Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Core i9 Processor"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    SKU / Code <span class="text-danger">*</span>
                                    <span class="ms-1 text-muted small fw-normal">(auto or manual)</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" name="code" id="skuInput"
                                        class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code', $product->code ?? '') }}" placeholder="e.g. PRD-CORE-A3X9"
                                        required>
                                    <button type="button" id="generateSkuBtn" class="btn btn-outline-secondary"
                                        title="Auto-generate SKU">
                                        <i class="bx bx-refresh"></i>
                                    </button>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Barcode --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Barcode</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror"
                                    value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="e.g. 8901234567890">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Main Category --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Main Category <span
                                        class="text-danger">*</span></label>
                                <select name="main_category_id" id="main_category_id"
                                    class="form-select @error('main_category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('main_category_id', $product->main_category_id ?? '') == $c->id ? 'selected' : '' }}>
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
                                <select name="sub_category_id" id="sub_category_id"
                                    class="form-select @error('sub_category_id') is-invalid @enderror">
                                    <option value="">Select Sub Category</option>
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Brand --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Brand <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror"
                                    required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('brand_id', $product->brand_id ?? '') == $b->id ? 'selected' : '' }}>
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
                                <input type="text" name="unit_name"
                                    class="form-control @error('unit_name') is-invalid @enderror"
                                    value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}" placeholder="Piece"
                                    required>
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Unit Code --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Unit Code <span class="text-danger">*</span></label>
                                <input type="text" name="unit_code"
                                    class="form-control @error('unit_code') is-invalid @enderror"
                                    value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}" placeholder="PCS"
                                    required>
                                @error('unit_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Card 2: Pricing & Stock Alert ──────────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-money me-2 text-success"></i>Pricing & Stock Alert
                        </h6>
                        <span class="badge bg-label-primary small" id="profitBadge">Profit: —</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Purchase Price --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Purchase Price <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                                        class="form-control @error('purchase_price') is-invalid @enderror"
                                        value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}" required>
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Selling Price --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Selling Price <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="selling_price" id="selling_price"
                                        class="form-control @error('selling_price') is-invalid @enderror"
                                        value="{{ old('selling_price', $product->selling_price ?? '0.00') }}" required>
                                    @error('selling_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tax % --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tax %</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="tax_percentage" id="tax_percentage"
                                        class="form-control @error('tax_percentage') is-invalid @enderror"
                                        value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}"
                                        oninput="if(parseFloat(this.value)>100){this.value=100;}if(parseFloat(this.value)<0){this.value=0;}">
                                    <span class="input-group-text">%</span>
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Max 100%</div>
                            </div>

                            {{-- Min Stock Alert --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Min Stock Alert</label>
                                <input type="number" step="0.01" name="minimum_stock_alert"
                                    class="form-control @error('minimum_stock_alert') is-invalid @enderror"
                                    value="{{ old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00') }}"
                                    placeholder="e.g. 5">
                                @error('minimum_stock_alert')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Card 3: Opening Stock (create / copy only) ──────────────── --}}
                @if (!$product->exists || isset($isCopy))
                    <div class="card shadow-sm mb-4">
                        <div
                            class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-box me-2 text-warning"></i>Opening Stock
                            </h6>
                            <span class="badge bg-label-warning small">Create only</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="add_opening_stock"
                                    id="addOpeningStock" value="1" {{ old('add_opening_stock') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="addOpeningStock">
                                    <i class="bx bx-plus-circle me-1 text-success"></i>
                                    Add initial stock while creating this product
                                </label>
                                <div class="form-text">When enabled, a Purchase record will be created automatically for
                                    traceability.</div>
                            </div>

                            <div id="openingStockBox" class="{{ old('add_opening_stock') ? '' : 'd-none' }} mt-3">
                                <div class="row g-3 align-items-end">

                                    {{-- Supplier --}}
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Supplier <span
                                                class="text-danger">*</span></label>
                                        <select name="supplier_id" id="supplier_id"
                                            class="form-select @error('supplier_id') is-invalid @enderror">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                        <label class="form-label fw-semibold">Opening Qty <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0.01" name="initial_qty"
                                            id="initial_qty"
                                            class="form-control @error('initial_qty') is-invalid @enderror"
                                            value="{{ old('initial_qty', '') }}" placeholder="e.g. 10">
                                        @error('initial_qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Live total preview --}}
                                    <div class="col-md-4">
                                        <div class="alert alert-info mb-0 py-2 px-3 d-flex align-items-center gap-2">
                                            <i class="bx bx-calculator text-info fs-5"></i>
                                            <div>
                                                <div class="small fw-semibold text-info">Purchase Total</div>
                                                <div class="fw-bold" id="openingStockTotal">—</div>
                                                <div class="form-text mb-0">Qty × Purchase Price</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Card 4: Technical Specifications ───────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-chip me-2 text-info"></i>Technical Specifications
                            <span class="ms-2 badge bg-label-secondary small fw-normal">Optional</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Manufacturer</label>
                                <input type="text" name="manufacturer" class="form-control"
                                    value="{{ old('manufacturer', $product->manufacturer ?? '') }}"
                                    placeholder="e.g. Intel, Asus">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Model Number</label>
                                <input type="text" name="model_number" class="form-control"
                                    value="{{ old('model_number', $product->model_number ?? '') }}"
                                    placeholder="e.g. ROG-STRIX-Z790">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Part / Serial No.</label>
                                <input type="text" name="part_number" class="form-control"
                                    value="{{ old('part_number', $product->part_number ?? '') }}"
                                    placeholder="e.g. 90MB1CS0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Warranty</label>
                                <input type="text" name="warranty" class="form-control"
                                    value="{{ old('warranty', $product->warranty ?? '') }}" placeholder="e.g. 3 Years">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Color</label>
                                <input type="text" name="color" class="form-control"
                                    value="{{ old('color', $product->color ?? '') }}" placeholder="e.g. Space Grey">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Weight</label>
                                <input type="text" name="weight" class="form-control"
                                    value="{{ old('weight', $product->weight ?? '') }}" placeholder="e.g. 1.2 kg">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Country of Origin</label>
                                <input type="text" name="country_of_origin" class="form-control"
                                    value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}"
                                    placeholder="e.g. Taiwan">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Card 5: Media & Description ─────────────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-image me-2 text-warning"></i>Media & Description
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- ── Left: Images ── --}}
                            <div class="col-md-5">

                                {{-- Primary Image --}}
                                <label class="form-label fw-semibold mb-2">Primary Image</label>
                                <div class="border rounded-3 p-3 mb-3" style="background:rgba(105,108,255,.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Preview box --}}
                                        <div class="rounded-3 border overflow-hidden flex-shrink-0 d-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;background:#f0f1ff;">
                                            <img id="imagePreview"
                                                src="{{ isset($product) && $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                                class="{{ isset($product) && $product->image ? '' : 'd-none' }}"
                                                style="width:90px;height:90px;object-fit:contain;">
                                            <span id="imageNoPreview"
                                                class="{{ isset($product) && $product->image ? 'd-none' : '' }} text-center text-muted small">
                                                <i class="bx bx-image d-block mb-1"
                                                    style="font-size:2rem;opacity:.35;"></i>
                                                No Image
                                            </span>
                                        </div>
                                        {{-- Buttons --}}
                                        <div>
                                            <button type="button" id="triggerImageBtn"
                                                class="btn btn-primary d-block mb-2">
                                                <i class="bx bx-upload me-1"></i> Upload
                                            </button>
                                            <button type="button" id="removeImageBtn"
                                                class="btn btn-outline-danger d-block {{ isset($product) && $product->image ? '' : 'd-none' }}">
                                                <i class="bx bx-trash me-1"></i> Remove
                                            </button>
                                            <div class="form-text mt-1">PNG, JPG, WEBP<br>Max 2 MB</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                                <input type="hidden" name="remove_image" id="remove_image" value="0">

                                {{-- Gallery Images --}}
                                <label class="form-label fw-semibold mb-2">Gallery Images</label>
                                <input type="file" name="gallery[]" id="galleryInput" multiple class="d-none"
                                    accept="image/*">
                                <input type="hidden" name="clear_gallery" id="clear_gallery" value="0">
                                <input type="hidden" name="remove_gallery_images" id="remove_gallery_images"
                                    value="">

                                {{-- Drop Zone --}}
                                <div id="galleryDropZone"
                                    class="border border-2 border-dashed rounded-3 p-3 text-center mb-2"
                                    style="border-color:rgba(105,108,255,.35)!important;background:rgba(105,108,255,.03);cursor:pointer;transition:border-color .2s,background .2s;">
                                    <i class="bx bx-cloud-upload d-block mb-1 text-primary"
                                        style="font-size:1.8rem;opacity:.6;"></i>
                                    <div class="small text-muted">Drop images here or</div>
                                    <button type="button" id="triggerGalleryBtn" class="btn btn-outline-primary mt-1">
                                        <i class="bx bx-images me-1"></i> Browse Files
                                    </button>
                                    <div class="form-text mt-1 mb-0">PNG, JPG, WEBP · Max 2MB each</div>
                                </div>

                                {{-- Thumbnail Grid --}}
                                <div id="galleryPreviewContainer"
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:4px;">
                                    @if (isset($product) && $product->gallery && count($product->gallery) > 0)
                                        @foreach ($product->gallery as $galImg)
                                            <div class="position-relative gallery-thumb" data-image="{{ $galImg }}"
                                                style="aspect-ratio:1;border-radius:8px;overflow:visible;">
                                                <img src="{{ asset('uploads/products/' . $galImg) }}" class="w-100 h-100"
                                                    style="object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.12);">
                                                <button type="button"
                                                    class="remove-gallery-img-btn position-absolute d-flex align-items-center justify-content-center bg-danger text-white border-0 rounded-circle shadow"
                                                    style="width:20px;height:20px;font-size:12px;font-weight:700;line-height:1;padding:0;cursor:pointer;top:-6px;right:-6px;z-index:2;"
                                                    title="Remove">×</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button type="button" id="clearAllGalleryBtn"
                                    class="btn btn-link text-danger px-0 mt-2 d-none">
                                    <i class="bx bx-trash me-1"></i> Clear All
                                </button>
                            </div>

                            {{-- Descriptions --}}
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Short Description</label>
                                    <textarea name="short_description" rows="3"
                                        class="form-control @error('short_description') is-invalid @enderror" placeholder="Key features summary...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Full Description / Specifications</label>
                                    <textarea name="full_description" rows="8"
                                        class="form-control @error('full_description') is-invalid @enderror"
                                        placeholder="Complete specifications, box contents...">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                                    @error('full_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ══ RIGHT COLUMN ═════════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- ── Card: Publish ──────────────────────────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>Publish
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Status toggle --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">Status</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="statusToggle"
                                        name="status" value="active"
                                        {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold {{ old('status', 'active') === 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', 'active') === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($isCopy) ? 'Save Copied Product' : __('messages.save') }}
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Card: Quick Tips ────────────────────────────────────────── --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="bx bx-bulb me-2 text-warning"></i>Quick Tips
                        </h6>
                        <ul class="list-unstyled mb-0 small text-muted">
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

            // ── SKU Auto-Generate ──────────────────────────────────────────────────
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

            // ── Sub-category dynamic load ──────────────────────────────────────────
            const subCategories = @json($subCategories);
            const selectedSubCategoryId = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";

            function loadSubcategories(mainCategoryId, preselectedId) {
                const subSelect = $('#sub_category_id');
                if (subSelect.hasClass('select2-hidden-accessible')) {
                    subSelect.select2('destroy');
                }
                subSelect.html('<option value="">Select Sub Category</option>');
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

            // ── Profit badge ───────────────────────────────────────────────────────
            function updateProfitBadge() {
                const buy = parseFloat($('#purchase_price').val()) || 0;
                const sell = parseFloat($('#selling_price').val()) || 0;
                if (buy > 0 && sell > 0) {
                    const profit = sell - buy;
                    const pct = ((profit / buy) * 100).toFixed(1);
                    const sign = profit >= 0 ? '+' : '';
                    $('#profitBadge')
                        .text(`Profit: ${sign}₹${profit.toFixed(2)} (${sign}${pct}%)`)
                        .removeClass('bg-label-primary bg-label-danger')
                        .addClass(profit >= 0 ? 'bg-label-primary' : 'bg-label-danger');
                } else {
                    $('#profitBadge').text('Profit: —');
                }
            }
            $('#purchase_price, #selling_price').on('input', updateProfitBadge);
            updateProfitBadge();

            // ── Opening stock toggle ───────────────────────────────────────────────
            function syncOpeningStockRequired(checked) {
                $('#supplier_id').prop('required', checked);
                $('#initial_qty').prop('required', checked);
                if (!checked) {
                    $('#supplier_id').val('').trigger('change');
                    $('#initial_qty').val('');
                    $('#openingStockTotal').text('—');
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

            // ── Opening stock live total ───────────────────────────────────────────
            function updateOpeningTotal() {
                const qty = parseFloat($('#initial_qty').val()) || 0;
                const price = parseFloat($('#purchase_price').val()) || 0;
                if (qty > 0 && price > 0) {
                    const sym = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';
                    $('#openingStockTotal').text(sym + (qty * price).toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }));
                } else {
                    $('#openingStockTotal').text('—');
                }
            }
            $('#initial_qty, #purchase_price').on('input', updateOpeningTotal);
            updateOpeningTotal();

            // ── Primary image upload ───────────────────────────────────────────────
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

            // ── Gallery management ─────────────────────────────────────────────────
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
                                    title="Remove">×</button>
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

            // Remove a NEW (not-yet-uploaded) thumbnail — rebuild the FileList
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
