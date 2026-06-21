@extends('layouts.admin')
@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($product) ? __('admin.edit_product') : __('admin.add_product') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">{{ __('admin.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($product) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bx bx-arrow-back me-1"></i> {{ __('admin.back') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm"
        action="{{ isset($product) ? route('admin.product.update', $product->id) : route('admin.product.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($product))
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- ===== LEFT COLUMN ===== --}}
            <div class="col-xl-8 col-lg-7">

                {{-- Basic Info --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-detail me-2 text-primary"></i>{{ __('admin.basic_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.product_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="productName"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_product_name') }}"
                                    value="{{ old('name', $product->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.slug_label') }}</label>
                                <input type="text" name="slug" id="productSlug"
                                    class="form-control form-control-lg @error('slug') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_product_slug') }}"
                                    value="{{ old('slug', $product->slug ?? '') }}">
                                <div class="form-text">{{ __('admin.leave_blank_autogen') }}</div>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.sku_label') }}</label>
                                <input type="text" name="sku"
                                    class="form-control form-control-lg @error('sku') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_product_sku') }}"
                                    value="{{ old('sku', $product->sku ?? '') }}">
                                @error('sku')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.short_description') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" rows="3"
                                    class="form-control form-control-lg @error('description') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_product_short_desc') }}">{{ old('description', $product->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.product_details_label') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="product_details" rows="5"
                                    class="form-control form-control-lg @error('product_details') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_product_details') }}">{{ old('product_details', $product->product_details ?? '') }}</textarea>
                                @error('product_details')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-rupee me-2 text-success"></i>{{ __('admin.pricing_stock') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.mrp_label') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="original_price" id="originalPrice"
                                        class="form-control @error('original_price') is-invalid @enderror"
                                        placeholder="{{ __('admin.ph_mrp') }}"
                                        value="{{ old('original_price', $product->original_price ?? '') }}">
                                </div>
                                @error('original_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.discount') }} (%) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="discount_percent" id="discountInput"
                                        class="form-control @error('discount_percent') is-invalid @enderror"
                                        placeholder="{{ __('admin.ph_discount_percent') }}" min="0" max="100"
                                        value="{{ old('discount_percent', $product->discount_percent ?? 0) }}">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                                @error('discount_percent')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.selling_price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="price" id="priceInput"
                                        class="form-control @error('price') is-invalid @enderror"
                                        placeholder="{{ __('admin.ph_selling_price') }}"
                                        value="{{ old('price', $product->price ?? '') }}">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Discount preview --}}
                            <div class="col-12">
                                <div id="discountPreview" class="d-none rounded-3 p-3 discount-preview-bg">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bx bx-tag-alt text-success fs-3"></i>
                                        <div>
                                            <div class="text-muted small">{{ __('admin.customer_saves') }}</div>
                                            <div class="fw-bold fs-5 text-success" id="savingAmount"></div>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <span class="badge bg-success fs-6 px-3 py-2" id="discountBadge"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.quantity') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantityInput"
                                    class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_quantity') }}"
                                    value="{{ old('quantity', $product->quantity ?? 0) }}">
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.total') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="total_price" id="totalPriceInput"
                                        class="form-control @error('total_price') is-invalid @enderror"
                                        placeholder="{{ __('admin.ph_total_price') }}"
                                        value="{{ old('total_price', $product->total_price ?? '') }}">
                                </div>
                                @error('total_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.rating') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.1" name="rating"
                                        class="form-control @error('rating') is-invalid @enderror"
                                        placeholder="{{ __('admin.ph_rating') }}"
                                        value="{{ old('rating', $product->rating ?? 0) }}">
                                    <span class="input-group-text bg-light"><i
                                            class="bx bxs-star text-warning"></i></span>
                                </div>
                                @error('rating')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-images me-2 text-info"></i>{{ __('admin.product_images') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Drop zone --}}
                        <div id="dropZone" class="border-2 border-dashed rounded-3 text-center p-5 mb-3 drop-zone"
                            onclick="document.getElementById('imageInput').click()"
                            ondragover="event.preventDefault();this.style.background='#e8edff'"
                            ondragleave="this.style.background='#f8f9ff'" ondrop="handleDrop(event)">
                            <i class="bx bx-cloud-upload text-primary upload-icon"></i>
                            <p class="mb-1 fw-semibold text-primary fs-5">{{ __('admin.click_drag_images') }} <span
                                    class="text-danger">*</span></p>
                            <p class="text-muted small mb-0">{{ __('admin.jpg_png_webp_each') }}</p>
                        </div>

                        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="d-none">

                        @error('images')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        {{-- New image previews --}}
                        <div id="imagePreviewBox" class="d-flex flex-wrap gap-3 mt-2"></div>

                        {{-- Existing images --}}
                        @if (isset($product) && !empty($product->images))
                            <div class="mt-4">
                                <p class="fw-semibold text-muted mb-3 fs-6">
                                    <i class="bx bx-photo-album me-1"></i> {{ __('admin.existing_images') }}
                                    <span class="badge bg-secondary ms-1">{{ count($product->images) }}</span>
                                </p>
                                <div class="d-flex flex-wrap gap-3" id="existingImages">
                                    @foreach ($product->images as $img)
                                        <div class="position-relative" id="img-wrap-{{ $loop->index }}">
                                            <img src="{{ asset('uploads/products/' . $img) }}" width="110"
                                                height="110" class="tbl-img" onerror="imgError(this)">
                                            <button type="button"
                                                onclick="deleteImage('{{ $img }}', {{ $product->id }}, 'img-wrap-{{ $loop->index }}')"
                                                class="btn btn-danger position-absolute d-flex align-items-center justify-content-center p-0 img-delete-btn">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="col-xl-4 col-lg-5">

                {{-- Publish --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $product->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $product->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $product->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('admin.is_favourite') }} <span
                                    class="text-danger">*</span></label>
                            <select name="is_favourite"
                                class="form-select form-select-lg @error('is_favourite') is-invalid @enderror">
                                <option value="">{{ __('admin.select_option') }}</option>
                                <option value="0"
                                    {{ old('is_favourite', $product->is_favourite ?? 0) == 0 ? 'selected' : '' }}>
                                    🤍 {{ __('admin.no') }}
                                </option>
                                <option value="1"
                                    {{ old('is_favourite', $product->is_favourite ?? 0) == 1 ? 'selected' : '' }}>
                                    ❤️ {{ __('admin.yes') }}
                                </option>
                            </select>
                            @error('is_favourite')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($product) ? __('admin.update_product') : __('admin.save_product') }}
                            </button>
                            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-lg">
                                {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Classification --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-category me-2 text-warning"></i>{{ __('admin.classification') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.category') }}</label>
                            <div class="select2-lg">
                                <select name="category_id" id="productCategorySelect"
                                    class="select2-category w-100 @error('category_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_category') }}</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.sub_category') }}</label>
                            <div class="select2-lg">
                                <select name="subcategory_id" id="productSubcategorySelect"
                                    class="select2-subcategory w-100 @error('subcategory_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_sub_category') }}</option>
                                    @foreach ($subcategories as $sub)
                                        <option value="{{ $sub->id }}"
                                            {{ old('subcategory_id', $product->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('subcategory_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.sub_in_category') }}</label>
                            <div class="select2-lg">
                                <select name="sub_in_categories_id" id="productSubInCategorySelect"
                                    class="select2-subincategory w-100 @error('sub_in_categories_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_option') }}</option>
                                    @foreach ($subInCategories as $sub)
                                        <option value="{{ $sub->id }}"
                                            {{ old('sub_in_categories_id', $product->sub_in_categories_id ?? '') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('sub_in_categories_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">{{ __('admin.brand') }}</label>
                            <div class="select2-lg">
                                <select name="brand_id" id="productBrandSelect"
                                    class="select2-brand w-100 @error('brand_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_brand') }}</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('brand_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // ===== jQuery Validation =====
        $(document).ready(function() {

            // ===== Select2 Init =====
            $('.select2-category').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_category') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-subcategory').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_sub_category') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-subincategory').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-brand').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_brand') }}',
                allowClear: true,
                width: '100%'
            });
            $('select[name="is_favourite"]').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // Re-trigger subcategory/subincategory load after Select2 init - handled below outside ready()

            // Custom rule: max file size per file
            $.validator.addMethod('filesize', function(value, element, param) {
                if (!element.files || element.files.length === 0) return true;
                for (let i = 0; i < element.files.length; i++) {
                    if (element.files[i].size > param) return false;
                }
                return true;
            }, 'File size must not exceed 2MB.');

            // Custom rule: regex pattern match
            $.validator.addMethod('pattern', function(value, element, param) {
                if (this.optional(element)) return true;
                return param.test(value);
            }, '{{ __('admin.val_invalid_format') }}');

            $('#productForm').validate({
                rules: {
                    // Basic Info
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    slug: {
                        maxlength: 255,
                        pattern: /^[a-z0-9]+(?:-[a-z0-9]+)*$/
                    },
                    sku: {
                        maxlength: 100
                    },

                    // Relationships
                    category_id: {
                        required: true
                    },
                    subcategory_id: {
                        required: true
                    },
                    sub_in_categories_id: {
                        required: true
                    },
                    brand_id: {
                        required: true
                    },

                    // Description
                    description: {
                        required: true,
                        maxlength: 1000
                    },
                    product_details: {
                        required: true
                    },

                    // Pricing
                    original_price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    total_price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    discount_percent: {
                        required: true,
                        digits: true,
                        min: 0,
                        max: 100
                    },

                    // Media
                    'images[]': {
                        required: {{ isset($product) ? 'false' : 'true' }},
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    },

                    // Extra Info
                    quantity: {
                        required: true,
                        digits: true,
                        min: 0
                    },
                    rating: {
                        required: true,
                        number: true,
                        min: 0,
                        max: 5
                    },

                    // Status
                    is_favourite: {
                        required: true
                    }
                },
                messages: {
                    // Basic Info
                    name: {
                        required: "{{ __('admin.val_product_name_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    slug: {
                        maxlength: "{{ __('admin.val_max_255') }}",
                        pattern: "{{ __('admin.val_slug_pattern') }}"
                    },
                    sku: {
                        maxlength: "{{ __('admin.val_max_100') }}"
                    },

                    // Relationships
                    category_id: {
                        required: "{{ __('admin.val_category_select_required') }}"
                    },
                    subcategory_id: {
                        required: "{{ __('admin.val_sub_category_required') }}"
                    },
                    sub_in_categories_id: {
                        required: "{{ __('admin.val_sub_in_category_required') }}"
                    },
                    brand_id: {
                        required: "{{ __('admin.val_brand_select_required') }}"
                    },

                    // Description
                    description: {
                        required: "{{ __('admin.val_short_desc_required') }}",
                        maxlength: "{{ __('admin.val_max_1000') }}"
                    },
                    product_details: {
                        required: "{{ __('admin.val_product_details_required') }}"
                    },

                    // Pricing
                    original_price: {
                        required: "{{ __('admin.val_mrp_required') }}",
                        number: "{{ __('admin.val_mrp_number') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    price: {
                        required: "{{ __('admin.val_selling_price_required') }}",
                        number: "{{ __('admin.val_selling_price_number') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    total_price: {
                        required: "{{ __('admin.val_total_price_required') }}",
                        number: "{{ __('admin.val_total_price_number') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    discount_percent: {
                        required: "{{ __('admin.val_discount_percent_required') }}",
                        digits: "{{ __('admin.val_whole_number') }}",
                        min: "{{ __('admin.val_not_negative') }}",
                        max: "{{ __('admin.val_discount_max') }}"
                    },

                    // Media
                    'images[]': {
                        required: "{{ __('admin.val_product_images_required') }}",
                        accept: "{{ __('admin.val_image_accept') }}",
                        filesize: "{{ __('admin.val_image_size') }}"
                    },

                    // Extra Info
                    quantity: {
                        required: "{{ __('admin.val_quantity_required') }}",
                        digits: "{{ __('admin.val_whole_number') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    rating: {
                        required: "{{ __('admin.val_rating_required') }}",
                        number: "{{ __('admin.val_rating_number') }}",
                        min: "{{ __('admin.val_not_negative') }}",
                        max: "{{ __('admin.val_rating_max_5') }}"
                    },

                    // Status
                    is_favourite: {
                        required: "{{ __('admin.val_favourite_required') }}"
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                errorPlacement: function(error, element) {
                    if (element.closest('.select2-lg').length) {
                        element.closest('.select2-lg').after(error);
                    } else if (element.closest('.input-group').length) {
                        element.closest('.input-group').after(error);
                    } else {
                        element.after(error);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    var val = $(element).val();
                    if (val && val.length > 0) {
                        $(element).addClass('is-valid');
                    } else {
                        $(element).removeClass('is-valid');
                    }
                },
                submitHandler: function(form) {
                    var submitBtn = $(form).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html(
                        '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.processing') }}'
                    );
                    form.submit();
                }
            });
        });

        // ===== DYNAMIC CATEGORY → SUB → SUB IN =====
        const selectedSubId = '{{ old('subcategory_id', $product->subcategory_id ?? '') }}';
        const selectedSubInId = '{{ old('sub_in_categories_id', $product->sub_in_categories_id ?? '') }}';

        function loadSubcategories(categoryId, selectedId) {
            const $sub = $('#productSubcategorySelect');
            const $subIn = $('#productSubInCategorySelect');
            $sub.html('<option value="">{{ __('admin.loading') }}</option>').prop('disabled', true);
            $subIn.html('<option value="">{{ __('admin.select_option') }}</option>').prop('disabled', true);
            if (!categoryId) {
                $sub.html('<option value="">{{ __('admin.select_sub_category') }}</option>').prop('disabled', false);
                return;
            }
            $.get('/admin/subcategories-by-category/' + categoryId, function(data) {
                let opts = '<option value="">{{ __('admin.select_sub_category') }}</option>';
                $.each(data, function(i, s) {
                    opts +=
                        `<option value="${s.id}" ${s.id == selectedId ? 'selected' : ''}>${s.name}</option>`;
                });
                $sub.html(opts).prop('disabled', false);
                // If selectedId exists, load sub-in too
                if (selectedId) loadSubInCategories(selectedId, selectedSubInId);
            });
        }

        function loadSubInCategories(subcategoryId, selectedId) {
            const $subIn = $('#productSubInCategorySelect');
            $subIn.html('<option value="">{{ __('admin.loading') }}</option>').prop('disabled', true);
            if (!subcategoryId) {
                $subIn.html('<option value="">{{ __('admin.select_option') }}</option>').prop('disabled', false);
                return;
            }
            $.get('/admin/subincategories-by-subcategory/' + subcategoryId, function(data) {
                let opts = '<option value="">{{ __('admin.select_option') }}</option>';
                $.each(data, function(i, s) {
                    opts +=
                        `<option value="${s.id}" ${s.id == selectedId ? 'selected' : ''}>${s.name}</option>`;
                });
                $subIn.html(opts).prop('disabled', false);
            });
        }

        // On page load — edit mode
        const initCatId = $('#productCategorySelect').val();
        if (initCatId) loadSubcategories(initCatId, selectedSubId);

        // On category change
        $('#productCategorySelect').on('change', function() {
            loadSubcategories($(this).val(), '');
        });

        // On subcategory change
        $('#productSubcategorySelect').on('change', function() {
            loadSubInCategories($(this).val(), '');
        });

        // Preserve existing slug on edit and auto-generate for new products
        const slugField = document.getElementById('productSlug');
        if (slugField && slugField.value) {
            slugField.dataset.manual = '1';
        }
        document.getElementById('productName').addEventListener('input', function() {
            const existing = slugField.dataset.manual;
            if (existing) return;
            slugField.value = this.value
                .toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
        });
        slugField.addEventListener('input', function() {
            this.dataset.manual = this.value ? '1' : '';
        });

        // Discount calculator
        function calcDiscount() {
            const mrp = parseFloat(document.getElementById('originalPrice').value) || 0;
            const disc = parseFloat(document.getElementById('discountInput').value) || 0;
            const preview = document.getElementById('discountPreview');
            if (mrp > 0 && disc > 0) {
                const saving = (mrp * disc / 100).toFixed(2);
                const final = (mrp - saving).toFixed(2);
                document.getElementById('priceInput').value = final;
                document.getElementById('savingAmount').textContent = '₹' + saving;
                document.getElementById('discountBadge').textContent = disc + '% OFF';
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
            calcTotal();
        }

        // Total Price = Selling Price × Quantity
        function calcTotal() {
            const price = parseFloat(document.getElementById('priceInput').value) || 0;
            const qty = parseFloat(document.getElementById('quantityInput').value) || 0;
            if (price > 0 && qty > 0) {
                document.getElementById('totalPriceInput').value = (price * qty).toFixed(2);
            }
        }

        document.getElementById('originalPrice').addEventListener('input', calcDiscount);
        document.getElementById('discountInput').addEventListener('input', calcDiscount);
        document.getElementById('priceInput').addEventListener('input', calcTotal);
        document.getElementById('quantityInput').addEventListener('input', calcTotal);

        // Image preview
        function renderPreviews(files) {
            const box = document.getElementById('imagePreviewBox');
            box.innerHTML = '';
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    box.insertAdjacentHTML('beforeend', `
                    <div class="position-relative">
                        <img src="${e.target.result}" width="110" height="110" class="new-img-thumb">
                        <span class="badge bg-primary position-absolute bottom-0 start-0 m-1 img-filename-badge">
                            ${file.name}
                        </span>
                    </div>`);
                };
                reader.readAsDataURL(file);
            });
        }
        document.getElementById('imageInput').addEventListener('change', function() {
            renderPreviews(this.files);
        });

        // Drag & drop
        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('dropZone').style.background = '#f8f9ff';
            const dt = e.dataTransfer;
            document.getElementById('imageInput').files = dt.files;
            renderPreviews(dt.files);
        }

        // Delete existing image
        function deleteImage(imageName, productId, divId) {
            if (!confirm('{{ __('admin.confirm_delete_image') }}')) return;
            fetch("{{ route('admin.product.deleteImage') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image: imageName,
                    product_id: productId
                })
            }).then(r => r.json()).then(d => {
                if (d.success) document.getElementById(divId).remove();
            });
        }
        // Status toggle
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = '{{ __('admin.status_active') }}';
                    lbl.className = 'fw-semibold fs-6 text-success';
                } else {
                    lbl.textContent = '{{ __('admin.status_inactive') }}';
                    lbl.className = 'fw-semibold fs-6 text-danger';
                }
            });
        }

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ __('admin.swal_fix_errors') }}',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    backdrop: false,
                    customClass: {
                        container: 'swal-top-toast'
                    }
                });
            });
        @endif
    </script>
@endpush

