@extends('layouts.admin')
@section('title', __('admin.product_attributes'))
@section('content')

    @push('styles')
        <style>
            .attr-card {
                border-radius: 1rem;
            }

            .color-row {
                border: 2px solid #f59e0b !important;
                background: #fffbf0;
                border-radius: .85rem;
                transition: box-shadow .2s;
            }

            .color-row:hover {
                box-shadow: 0 6px 20px rgba(245, 158, 11, .18);
            }

            .size-row {
                border: 2px solid #0ea5e9 !important;
                background: #f0f9ff;
                border-radius: .85rem;
                transition: box-shadow .2s;
            }

            .size-row:hover {
                box-shadow: 0 6px 20px rgba(14, 165, 233, .18);
            }

            .variation-row {
                border-radius: .6rem;
            }

            .detail-row input {
                font-size: .9rem;
            }

            .preset-size-btn {
                font-size: .78rem;
                padding: 3px 10px;
            }

            .color-swatch-preview {
                width: 30px;
                height: 30px;
                border-radius: 6px;
                border: 2px solid #dee2e6;
                cursor: pointer;
                flex-shrink: 0;
            }

            .thumb-preview {
                width: 52px;
                height: 52px;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }

            .section-nav-btn {
                text-align: left;
                border-radius: .6rem;
            }

            .section-nav-btn:hover {
                transform: translateX(3px);
            }

            .form-control-color {
                height: 38px;
                cursor: pointer;
            }

            .img-remove-btn {
                position: absolute;
                top: -6px;
                right: -6px;
                width: 22px;
                height: 22px;
                border-radius: 50%;
                font-size: .7rem;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .existing-img-wrap {
                position: relative;
                display: inline-block;
            }
        </style>
    @endpush

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.product_attributes') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">{{ __('admin.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-outline-info">
                <i class="bx bx-show me-1"></i>{{ __('admin.view') }}
            </a>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bx bx-error-circle me-2"></i><strong>Please fix the errors below:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="attrForm" action="{{ route('admin.product.attributes.update', $product->id) }}" method="POST"
        enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row g-4">

            {{-- ═══ LEFT: Product Info + Nav ═══ --}}
            <div class="col-lg-3">

                {{-- Product Info Card --}}
                <div class="card shadow-sm mb-4" style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-2 d-flex align-items-center justify-content-center"
                                style="width:44px;height:44px;background:#f59e0b;">
                                <i class="bx bx-tag-alt text-white fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="color:#f59e0b;font-size:.95rem;">
                                    {{ Str::limit($product->name, 20) }}</div>
                                <div class="small" style="opacity:.65;">SKU: {{ $product->sku ?? '—' }}</div>
                            </div>
                        </div>
                        <hr style="border-color:rgba(255,255,255,.15);">
                        <div class="row g-2 small">
                            <div class="col-6">
                                <div style="opacity:.55;font-size:.7rem;">{{ __('admin.main_category') }}</div>
                                <div class="fw-semibold">{{ $product->category->name ?? '—' }}</div>
                            </div>
                            <div class="col-6">
                                <div style="opacity:.55;font-size:.7rem;">{{ __('admin.brand') }}</div>
                                <div class="fw-semibold">{{ $product->brand->name ?? '—' }}</div>
                            </div>
                            <div class="col-6">
                                <div style="opacity:.55;font-size:.7rem;">{{ __('admin.price') }}</div>
                                <div class="fw-semibold text-success">₹{{ number_format($product->price, 0) }}</div>
                            </div>
                            <div class="col-6">
                                <div style="opacity:.55;font-size:.7rem;">{{ __('admin.status') }}</div>
                                <span
                                    class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $product->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Nav --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold small text-muted"><i class="bx bx-navigation me-1"></i>Jump To</h6>
                    </div>
                    <div class="card-body p-3 d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm fw-semibold section-nav-btn"
                            data-target="section-details">
                            <i class="bx bx-list-ul me-1"></i> Product Details
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm fw-semibold section-nav-btn"
                            data-target="section-colors">
                            <i class="bx bx-palette me-1"></i> Colors
                            <span class="badge bg-warning text-dark ms-1"
                                id="colorCountBadge">{{ count($colorData) }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm fw-semibold section-nav-btn"
                            data-target="section-sizes">
                            <i class="bx bx-ruler me-1"></i> Sizes
                            <span class="badge bg-info text-white ms-1" id="sizeCountBadge">{{ count($sizeData) }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm fw-semibold section-nav-btn"
                            data-target="section-variations">
                            <i class="bx bx-layer me-1"></i> Variations
                            <span class="badge bg-success ms-1" id="varCountBadge">{{ count($variationsData) }}</span>
                        </button>
                    </div>
                </div>

                {{-- Save Button (sticky) --}}
                <div class="card shadow-sm">
                    <div class="card-body p-3 d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg fw-semibold" id="submitBtn">
                            <i class="bx bx-check me-1"></i> Save All Attributes
                        </button>
                        <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> {{ __('admin.cancel') }}
                        </a>
                    </div>
                </div>

            </div>{{-- /col-lg-3 --}}

            {{-- ═══ RIGHT: Sections ═══ --}}
            <div class="col-lg-9">

                {{-- ════════════════════════════════════════════════
                     SECTION 1: Product Details
                ════════════════════════════════════════════════ --}}
                <div class="card shadow-sm mb-4 attr-card" id="section-details">
                    <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
                        <i class="bx bx-list-ul text-warning fs-5"></i>
                        <h6 class="mb-0 fw-semibold">Product Details</h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Fixed Fields --}}
                        <div class="row g-3 mb-4 pb-4 border-bottom">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Fabric</label>
                                <input type="text" name="fabric"
                                    class="form-control @error('fabric') is-invalid @enderror"
                                    placeholder="e.g. Cotton Lycra, Polyester"
                                    value="{{ old('fabric', $productDetails['fabric']) }}">
                                @error('fabric')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Age Group</label>
                                <input type="text" name="age_group"
                                    class="form-control @error('age_group') is-invalid @enderror"
                                    placeholder="e.g. Adults, Teens, Kids"
                                    value="{{ old('age_group', $productDetails['age_group']) }}">
                                @error('age_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Care Instructions</label>
                                <input type="text" name="care_instructions"
                                    class="form-control @error('care_instructions') is-invalid @enderror"
                                    placeholder="e.g. Machine washable, Dry clean"
                                    value="{{ old('care_instructions', $productDetails['care_instructions']) }}">
                                @error('care_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Clothing Features</label>
                                <input type="text" name="clothing_features"
                                    class="form-control @error('clothing_features') is-invalid @enderror"
                                    placeholder="e.g. Moisture wicking, Anti-odour"
                                    value="{{ old('clothing_features', $productDetails['clothing_features']) }}">
                                @error('clothing_features')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Neckline</label>
                                <input type="text" name="neckline"
                                    class="form-control @error('neckline') is-invalid @enderror"
                                    placeholder="e.g. Round, V-Neck, Polo"
                                    value="{{ old('neckline', $productDetails['neckline']) }}">
                                @error('neckline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Sleeve Length Type</label>
                                <input type="text" name="sleeve_length_type"
                                    class="form-control @error('sleeve_length_type') is-invalid @enderror"
                                    placeholder="e.g. Short, Full, Sleeveless"
                                    value="{{ old('sleeve_length_type', $productDetails['sleeve_length_type']) }}">
                                @error('sleeve_length_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Dynamic Key-Value Pairs --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="fw-semibold small">Additional Details</span>
                                <span class="text-muted small ms-1">(Key → Value pairs)</span>
                            </div>
                            <button type="button" id="addDetailBtn" class="btn btn-sm btn-warning fw-semibold">
                                <i class="bx bx-plus me-1"></i>Add more
                            </button>
                        </div>

                        <div class="mb-2">
                            <div class="row g-2 mb-1">
                                <div class="col-5"><span class="text-muted small fw-semibold">Key</span></div>
                                <div class="col-6"><span class="text-muted small fw-semibold">Value</span></div>
                            </div>
                        </div>
                        <div id="detailsContainer">
                            @forelse($detailsData as $i => $detail)
                                <div class="detail-row d-flex gap-2 mb-2 align-items-start">
                                    <input type="text" name="details[{{ $i }}][key]"
                                        class="form-control @error('details.' . $i . '.key') is-invalid @enderror"
                                        placeholder="e.g. Material, Wash"
                                        value="{{ old('details.' . $i . '.key', $detail['key']) }}">
                                    <input type="text" name="details[{{ $i }}][value]"
                                        class="form-control @error('details.' . $i . '.value') is-invalid @enderror"
                                        placeholder="e.g. 100% Cotton"
                                        value="{{ old('details.' . $i . '.value', $detail['value']) }}">
                                    <button type="button" class="btn btn-danger btn-sm remove-detail-btn flex-shrink-0"
                                        style="width:38px;height:38px;" title="Remove">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="detail-row d-flex gap-2 mb-2 align-items-start">
                                    <input type="text" name="details[0][key]" class="form-control"
                                        placeholder="e.g. Material">
                                    <input type="text" name="details[0][value]" class="form-control"
                                        placeholder="e.g. 100% Cotton">
                                    <button type="button" class="btn btn-danger btn-sm remove-detail-btn flex-shrink-0"
                                        style="width:38px;height:38px;" title="Remove">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- ════════════════════════════════════════════════
                     SECTION 2: Colors
                ════════════════════════════════════════════════ --}}
                <div class="card shadow-sm mb-4 attr-card" id="section-colors">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-palette text-warning fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Colors</h6>
                            <span class="badge bg-label-warning" id="colorCountHeader">{{ count($colorData) }}</span>
                        </div>
                        <button type="button" id="addColorBtn" class="btn btn-sm fw-semibold"
                            style="background:#fff8e1;border:2px dashed #f59e0b;color:#b45309;">
                            <i class="bx bx-plus me-1"></i>Add Color
                        </button>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->has('colors.*') || $errors->has('colors.*.color_name') || $errors->has('colors.*.color_images.*'))
                            <div class="alert alert-danger alert-sm mb-3 py-2">
                                <i class="bx bx-error-circle me-1"></i>
                                Please fix color errors below.
                            </div>
                        @endif

                        <div id="colorRows">
                            @forelse($colorData as $i => $color)
                                <div class="color-row p-3 mb-3 position-relative" data-idx="{{ $i }}">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="color-swatch-preview color-preview-swatch"
                                                style="background:{{ $color['color_code'] ?: '#cccccc' }};"></div>
                                            <span class="fw-semibold color-name-label">
                                                {{ $color['color_name'] ?: 'Color' }}
                                            </span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-color-btn">
                                            <i class="bx bx-x me-1"></i>Remove
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold small">
                                                Color Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="color" name="colors[{{ $i }}][color_code]"
                                                    class="form-control form-control-color p-1 color-picker-input"
                                                    value="{{ $color['color_code'] ?: '#000000' }}"
                                                    style="width:46px;flex-shrink:0;">
                                                <input type="text" name="colors[{{ $i }}][color_name]"
                                                    class="form-control color-name-input @error('colors.' . $i . '.color_name') is-invalid @enderror"
                                                    placeholder="e.g. Crimson Red"
                                                    value="{{ old('colors.' . $i . '.color_name', $color['color_name']) }}">
                                                @error('colors.' . $i . '.color_name')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small">Images</label>
                                            <input type="file" name="colors[{{ $i }}][color_images][]"
                                                class="form-control form-control-sm @error('colors.' . $i . '.color_images.*') is-invalid @enderror"
                                                multiple accept="image/jpg,image/jpeg,image/png,image/webp">
                                            <div class="form-text">JPG/PNG/WEBP, max 2MB each</div>
                                            @error('colors.' . $i . '.color_images.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @if (!empty($color['existing_images']))
                                                <div class="d-flex flex-wrap gap-2 mt-2"
                                                    id="existingColorImgs_{{ $i }}">
                                                    @foreach ($color['existing_images'] as $img)
                                                        <div class="existing-img-wrap">
                                                            <img src="{{ asset('uploads/product-colors/' . $img) }}"
                                                                class="thumb-preview"
                                                                onerror="this.src='{{ asset('assets/img/no-image.png') }}'">
                                                        </div>
                                                    @endforeach
                                                    <input type="hidden"
                                                        name="colors[{{ $i }}][existing_images]"
                                                        value='@json($color['existing_images'])'>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small">Quantity</label>
                                            <input type="number" name="colors[{{ $i }}][color_qty]"
                                                class="form-control @error('colors.' . $i . '.color_qty') is-invalid @enderror"
                                                min="0" placeholder="0"
                                                value="{{ old('colors.' . $i . '.color_qty', $color['color_qty']) }}">
                                            @error('colors.' . $i . '.color_qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="colorEmpty" class="text-center py-5 text-muted">
                                    <i class="bx bx-palette" style="font-size:3rem;opacity:.2;"></i>
                                    <p class="mt-2 mb-0 small">No colors added yet. Click "Add Color" to start.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- ════════════════════════════════════════════════
                     SECTION 3: Sizes
                ════════════════════════════════════════════════ --}}
                <div class="card shadow-sm mb-4 attr-card" id="section-sizes">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-ruler text-info fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Sizes</h6>
                            <span class="badge bg-label-info" id="sizeCountHeader">{{ count($sizeData) }}</span>
                        </div>
                        <button type="button" id="addSizeBtn" class="btn btn-sm fw-semibold"
                            style="background:#e0f2fe;border:2px dashed #0ea5e9;color:#0369a1;">
                            <i class="bx bx-plus me-1"></i>Add Size
                        </button>
                    </div>
                    <div class="card-body p-4">

                        {{-- Quick Add Presets --}}
                        <div class="mb-4 pb-3 border-bottom">
                            <span class="text-muted small fw-semibold me-2">Quick add:</span>
                            @foreach (['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '28', '30', '32', '34', '36', '38', '40'] as $preset)
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-1 preset-size-btn"
                                    data-size="{{ $preset }}">{{ $preset }}</button>
                            @endforeach
                        </div>

                        <div id="sizeRows">
                            @forelse($sizeData as $i => $size)
                                <div class="size-row p-3 mb-2 d-flex flex-wrap gap-3 align-items-end"
                                    data-idx="{{ $i }}">
                                    <div style="min-width:110px;flex:1;">
                                        <label class="form-label fw-semibold small mb-1">
                                            Size Label <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="sizes[{{ $i }}][size_value]"
                                            class="form-control @error('sizes.' . $i . '.size_value') is-invalid @enderror"
                                            placeholder="XS / 28 / Free"
                                            value="{{ old('sizes.' . $i . '.size_value', $size['size_value']) }}">
                                        @error('sizes.' . $i . '.size_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div style="min-width:100px;flex:1;">
                                        <label class="form-label fw-semibold small mb-1">Size Number</label>
                                        <input type="text" name="sizes[{{ $i }}][size_number]"
                                            class="form-control" placeholder="e.g. 34"
                                            value="{{ old('sizes.' . $i . '.size_number', $size['size_number']) }}">
                                    </div>
                                    <div style="min-width:130px;flex:1;">
                                        <label class="form-label fw-semibold small mb-1">Gender</label>
                                        <select name="sizes[{{ $i }}][gender]" class="form-select">
                                            <option value="">All</option>
                                            @foreach (['Men', 'Women', 'Unisex', 'Kids', 'Boys', 'Girls'] as $g)
                                                <option value="{{ $g }}"
                                                    {{ ($size['gender'] ?? '') === $g ? 'selected' : '' }}>
                                                    {{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="min-width:90px;flex:0 0 90px;">
                                        <label class="form-label fw-semibold small mb-1">Qty</label>
                                        <input type="number" name="sizes[{{ $i }}][size_qty]"
                                            class="form-control @error('sizes.' . $i . '.size_qty') is-invalid @enderror"
                                            min="0" placeholder="0"
                                            value="{{ old('sizes.' . $i . '.size_qty', $size['size_qty']) }}">
                                        @error('sizes.' . $i . '.size_qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-size-btn"
                                            title="Remove size">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div id="sizeEmpty" class="text-center py-5 text-muted">
                                    <i class="bx bx-ruler" style="font-size:3rem;opacity:.2;"></i>
                                    <p class="mt-2 mb-0 small">No sizes added. Use quick-add buttons or "Add Size".</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- ════════════════════════════════════════════════
                     SECTION 4: Variations
                ════════════════════════════════════════════════ --}}
                <div class="card shadow-sm mb-4 attr-card" id="section-variations">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-layer text-success fs-5"></i>
                            <h6 class="mb-0 fw-semibold">Product Variations
                                <span class="text-muted small fw-normal">(Optional)</span>
                            </h6>
                            <span class="badge bg-label-success" id="varCountHeader">{{ count($variationsData) }}</span>
                        </div>
                        <button type="button" id="addVariationBtn" class="btn btn-sm btn-outline-success fw-semibold">
                            <i class="bx bx-plus me-1"></i>Add Variation
                        </button>
                    </div>
                    <div class="card-body p-4">

                        {{-- Header --}}
                        <div class="row g-2 mb-2 text-muted small fw-semibold d-none d-md-flex">
                            <div class="col-md-4">Variation Name <span class="text-danger">*</span></div>
                            <div class="col-md-3">Price (₹)</div>
                            <div class="col-md-4">Image</div>
                            <div class="col-md-1"></div>
                        </div>

                        <div id="variationRows">
                            @forelse($variationsData as $i => $var)
                                <div class="variation-row row g-2 mb-2 align-items-start p-2 bg-light rounded-3"
                                    data-idx="{{ $i }}">
                                    <div class="col-md-4">
                                        <input type="text" name="variations[{{ $i }}][variation_name]"
                                            class="form-control @error('variations.' . $i . '.variation_name') is-invalid @enderror"
                                            placeholder="e.g. Topwear, Combo"
                                            value="{{ old('variations.' . $i . '.variation_name', $var['variation_name']) }}">
                                        @error('variations.' . $i . '.variation_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">₹</span>
                                            <input type="number" name="variations[{{ $i }}][variation_price]"
                                                class="form-control @error('variations.' . $i . '.variation_price') is-invalid @enderror"
                                                placeholder="0.00" step="0.01" min="0"
                                                value="{{ old('variations.' . $i . '.variation_price', $var['variation_price']) }}">
                                        </div>
                                        @error('variations.' . $i . '.variation_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="variations[{{ $i }}][variation_image]"
                                            class="form-control form-control-sm @error('variations.' . $i . '.variation_image') is-invalid @enderror"
                                            accept="image/jpg,image/jpeg,image/png,image/webp">
                                        <div class="form-text">JPG/PNG/WEBP, max 2MB</div>
                                        @error('variations.' . $i . '.variation_image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @if (!empty($var['existing_image']))
                                            <div class="mt-1 d-flex align-items-center gap-2">
                                                <img src="{{ asset('uploads/product-variations/' . $var['existing_image']) }}"
                                                    class="thumb-preview"
                                                    onerror="this.src='{{ asset('assets/img/no-image.png') }}'">
                                                <input type="hidden"
                                                    name="variations[{{ $i }}][existing_image]"
                                                    value="{{ $var['existing_image'] }}">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-variation-btn"
                                            style="width:36px;height:36px;" title="Remove">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="variation-row row g-2 mb-2 align-items-start p-2 bg-light rounded-3"
                                    data-idx="0">
                                    <div class="col-md-4">
                                        <input type="text" name="variations[0][variation_name]" class="form-control"
                                            placeholder="e.g. Topwear, Combo">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">₹</span>
                                            <input type="number" name="variations[0][variation_price]"
                                                class="form-control" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="variations[0][variation_image]"
                                            class="form-control form-control-sm"
                                            accept="image/jpg,image/jpeg,image/png,image/webp">
                                        <div class="form-text">JPG/PNG/WEBP, max 2MB</div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-variation-btn"
                                            style="width:36px;height:36px;">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- Bottom Save --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}
                    </a>
                    <button type="submit" class="btn btn-dark btn-lg px-5 fw-semibold" id="submitBtnBottom">
                        <i class="bx bx-check me-1"></i> Save All Attributes
                    </button>
                </div>

            </div>{{-- /col-lg-9 --}}
        </div>{{-- /row --}}
    </form>

@endsection

@push('scripts')
    <script>
        // ── Index counters ──────────────────────────────────────────
        let colorIdx = {{ count($colorData) }};
        let sizeIdx = {{ max(count($sizeData), 1) }};
        let detailIdx = {{ max(count($detailsData), 1) }};
        let varIdx = {{ max(count($variationsData), 1) }};

        // ── Helpers ──────────────────────────────────────────────────
        function updateCounts() {
            const cc = document.querySelectorAll('.color-row').length;
            const sc = document.querySelectorAll('.size-row').length;
            const vc = document.querySelectorAll('.variation-row').length;

            ['colorCountBadge', 'colorCountHeader'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = cc;
            });
            ['sizeCountBadge', 'sizeCountHeader'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = sc;
            });
            ['varCountBadge', 'varCountHeader'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = vc;
            });

            const ce = document.getElementById('colorEmpty');
            if (ce) ce.style.display = cc ? 'none' : '';
            const se = document.getElementById('sizeEmpty');
            if (se) se.style.display = sc ? 'none' : '';
        }
        updateCounts();

        // ── Section scroll nav ──────────────────────────────────────
        document.querySelectorAll('.section-nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                if (target) target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // ── ADD DETAIL ROW ──────────────────────────────────────────
        document.getElementById('addDetailBtn').addEventListener('click', function() {
            const i = detailIdx++;
            document.getElementById('detailsContainer').insertAdjacentHTML('beforeend', `
            <div class="detail-row d-flex gap-2 mb-2 align-items-start">
                <input type="text" name="details[${i}][key]" class="form-control" placeholder="e.g. Material">
                <input type="text" name="details[${i}][value]" class="form-control" placeholder="e.g. 100% Cotton">
                <button type="button" class="btn btn-danger btn-sm remove-detail-btn flex-shrink-0"
                        style="width:38px;height:38px;" title="Remove">
                    <i class="bx bx-x"></i>
                </button>
            </div>`);
        });

        // ── ADD COLOR ROW ───────────────────────────────────────────
        document.getElementById('addColorBtn').addEventListener('click', function() {
            const i = colorIdx++;
            const empty = document.getElementById('colorEmpty');
            if (empty) empty.style.display = 'none';
            document.getElementById('colorRows').insertAdjacentHTML('beforeend', `
            <div class="color-row p-3 mb-3 position-relative" data-idx="${i}">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="color-swatch-preview color-preview-swatch" style="background:#cccccc;"></div>
                        <span class="fw-semibold color-name-label">Color</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-color-btn">
                        <i class="bx bx-x me-1"></i>Remove
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">
                            Color Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="color" name="colors[${i}][color_code]"
                                   class="form-control form-control-color p-1 color-picker-input"
                                   value="#cccccc" style="width:46px;flex-shrink:0;">
                            <input type="text" name="colors[${i}][color_name]"
                                   class="form-control color-name-input attr-color-name"
                                   placeholder="e.g. Crimson Red" required>
                            <div class="invalid-feedback">Color name is required.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Images</label>
                        <input type="file" name="colors[${i}][color_images][]"
                               class="form-control form-control-sm" multiple
                               accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">JPG/PNG/WEBP, max 2MB each</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Quantity</label>
                        <input type="number" name="colors[${i}][color_qty]"
                               class="form-control" min="0" placeholder="0" value="0">
                    </div>
                </div>
            </div>`);
            updateCounts();
        });

        // ── ADD SIZE ROW ────────────────────────────────────────────
        function addSizeRow(val = '', num = '', gender = '', qty = 0) {
            const i = sizeIdx++;
            const genders = ['', 'Men', 'Women', 'Unisex', 'Kids', 'Boys', 'Girls'];
            const gOpts = genders.map(g =>
                `<option value="${g}" ${g === gender ? 'selected' : ''}>${g || 'All'}</option>`
            ).join('');

            const empty = document.getElementById('sizeEmpty');
            if (empty) empty.style.display = 'none';
            document.getElementById('sizeRows').insertAdjacentHTML('beforeend', `
            <div class="size-row p-3 mb-2 d-flex flex-wrap gap-3 align-items-end" data-idx="${i}">
                <div style="min-width:110px;flex:1;">
                    <label class="form-label fw-semibold small mb-1">Size Label <span class="text-danger">*</span></label>
                    <input type="text" name="sizes[${i}][size_value]" class="form-control attr-size-label"
                           placeholder="XS / 28 / Free" value="${val}" required>
                    <div class="invalid-feedback">Size label is required.</div>
                </div>
                <div style="min-width:100px;flex:1;">
                    <label class="form-label fw-semibold small mb-1">Size Number</label>
                    <input type="text" name="sizes[${i}][size_number]" class="form-control"
                           placeholder="e.g. 34" value="${num}">
                </div>
                <div style="min-width:130px;flex:1;">
                    <label class="form-label fw-semibold small mb-1">Gender</label>
                    <select name="sizes[${i}][gender]" class="form-select">${gOpts}</select>
                </div>
                <div style="min-width:90px;flex:0 0 90px;">
                    <label class="form-label fw-semibold small mb-1">Qty</label>
                    <input type="number" name="sizes[${i}][size_qty]" class="form-control"
                           min="0" placeholder="0" value="${qty}">
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-size-btn" title="Remove">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>`);
            updateCounts();
        }

        document.getElementById('addSizeBtn').addEventListener('click', () => addSizeRow());

        // Quick add presets
        document.querySelectorAll('.preset-size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                addSizeRow(this.dataset.size, '', '', 0);
                this.classList.toggle('btn-secondary');
                this.classList.toggle('btn-outline-secondary');
            });
        });

        // ── ADD VARIATION ROW ───────────────────────────────────────
        document.getElementById('addVariationBtn').addEventListener('click', function() {
            const i = varIdx++;
            document.getElementById('variationRows').insertAdjacentHTML('beforeend', `
            <div class="variation-row row g-2 mb-2 align-items-start p-2 bg-light rounded-3" data-idx="${i}">
                <div class="col-md-4">
                    <input type="text" name="variations[${i}][variation_name]"
                           class="form-control attr-var-name" placeholder="e.g. Topwear, Combo" required>
                    <div class="invalid-feedback">Variation name is required.</div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">₹</span>
                        <input type="number" name="variations[${i}][variation_price]"
                               class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="file" name="variations[${i}][variation_image]"
                           class="form-control form-control-sm" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG/PNG/WEBP, max 2MB</div>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-variation-btn"
                            style="width:36px;height:36px;">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            </div>`);
            updateCounts();
        });

        // ── REMOVE HANDLERS (event delegation) ─────────────────────
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-detail-btn')) e.target.closest('.detail-row').remove();
            if (e.target.closest('.remove-color-btn')) {
                e.target.closest('.color-row').remove();
                updateCounts();
            }
            if (e.target.closest('.remove-size-btn')) {
                e.target.closest('.size-row').remove();
                updateCounts();
            }
            if (e.target.closest('.remove-variation-btn')) {
                e.target.closest('.variation-row').remove();
                updateCounts();
            }
        });

        // ── COLOR PICKER LIVE PREVIEW ───────────────────────────────
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('color-picker-input')) {
                const row = e.target.closest('.color-row');
                if (row) {
                    const swatch = row.querySelector('.color-preview-swatch');
                    if (swatch) swatch.style.background = e.target.value;
                }
            }
            if (e.target.classList.contains('color-name-input')) {
                const row = e.target.closest('.color-row');
                if (row) {
                    const label = row.querySelector('.color-name-label');
                    if (label) label.textContent = e.target.value || 'Color';
                }
            }
        });

        // ── CLIENT-SIDE VALIDATION ──────────────────────────────────
        document.getElementById('attrForm').addEventListener('submit', function(e) {
            let valid = true;
            let firstError = null;

            // Validate color names
            document.querySelectorAll('.color-row').forEach((row, idx) => {
                const nameInput = row.querySelector('input[class*="color-name-input"]');
                if (nameInput && !nameInput.value.trim()) {
                    nameInput.classList.add('is-invalid');
                    if (!nameInput.nextElementSibling || !nameInput.nextElementSibling.classList.contains(
                            'invalid-feedback')) {
                        nameInput.insertAdjacentHTML('afterend',
                            '<div class="invalid-feedback d-block">Color name is required.</div>');
                    }
                    valid = false;
                    firstError = firstError || nameInput;
                } else if (nameInput) {
                    nameInput.classList.remove('is-invalid');
                    const fb = nameInput.parentElement.querySelector('.invalid-feedback');
                    if (fb) fb.remove();
                }

                // Image size validation
                row.querySelectorAll('input[type="file"]').forEach(fileInput => {
                    if (!fileInput.files) return;
                    for (let f of fileInput.files) {
                        if (f.size > 2 * 1024 * 1024) {
                            fileInput.classList.add('is-invalid');
                            valid = false;
                            firstError = firstError || fileInput;
                        } else {
                            fileInput.classList.remove('is-invalid');
                        }
                    }
                });
            });

            // Validate size labels
            document.querySelectorAll('.size-row').forEach(row => {
                const sizeInput = row.querySelector('input[name*="[size_value]"]');
                if (sizeInput && !sizeInput.value.trim()) {
                    sizeInput.classList.add('is-invalid');
                    valid = false;
                    firstError = firstError || sizeInput;
                } else if (sizeInput) {
                    sizeInput.classList.remove('is-invalid');
                }
            });

            // Validate variation names
            document.querySelectorAll('.variation-row').forEach(row => {
                const nameInput = row.querySelector('input[name*="[variation_name]"]');
                if (nameInput && !nameInput.value.trim()) {
                    // Only validate if any other field in the row is filled
                    const priceInput = row.querySelector('input[name*="[variation_price]"]');
                    const fileInput = row.querySelector('input[type="file"]');
                    const hasData = (priceInput && priceInput.value) || (fileInput && fileInput.files &&
                        fileInput.files.length);
                    if (hasData) {
                        nameInput.classList.add('is-invalid');
                        valid = false;
                        firstError = firstError || nameInput;
                    }
                } else if (nameInput) {
                    nameInput.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                if (firstError) firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            // Disable buttons on submit
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML =
                '<i class="bx bx-loader-alt bx-spin me-1"></i> Saving...';
            const bottomBtn = document.getElementById('submitBtnBottom');
            if (bottomBtn) {
                bottomBtn.disabled = true;
                bottomBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Saving...';
            }
        });

        // ── Real-time invalid class removal ────────────────────────
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('is-invalid') && e.target.value.trim()) {
                e.target.classList.remove('is-invalid');
            }
        });

        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        backdrop: false
                    });
                }
            });
        @endif
    </script>
@endpush
