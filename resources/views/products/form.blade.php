@csrf

{{-- ── Section 1: Basic Information ── --}}
<h6 class="fw-semibold text-primary mb-3"><i class="bx bx-info-circle me-2"></i>{{ __('messages.information') }}</h6>
<div class="row g-3 pb-4 mb-4 border-bottom">
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.product_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Core i9 Processor" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">{{ __('messages.sku') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $product->code ?? '') }}" placeholder="e.g. LPT-LAT-5440" required>
        <div class="form-text">Unique identifier for this product.</div>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Barcode (ISBN/EAN)</label>
        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
            value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="e.g. 8901234567890">
        @error('barcode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.brand') }} <span class="text-danger">*</span></label>
        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
            <option value="">{{ __('messages.all_brands') }}</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}"
                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Unit Name <span class="text-danger">*</span></label>
        <input type="text" name="unit_name" class="form-control @error('unit_name') is-invalid @enderror"
            value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}" placeholder="e.g. Piece, Box" required>
        @error('unit_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Unit Code <span class="text-danger">*</span></label>
        <input type="text" name="unit_code" class="form-control @error('unit_code') is-invalid @enderror"
            value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}" placeholder="e.g. PCS, BOX" required>
        @error('unit_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.category') }} <span class="text-danger">*</span></label>
        <select name="main_category_id" id="main_category_id"
            class="form-select @error('main_category_id') is-invalid @enderror" required>
            <option value="">{{ __('messages.select_main_category') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('main_category_id', $product->main_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
        @error('main_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.sub_category') }} <span
                class="text-danger">*</span></label>
        <select name="sub_category_id" id="sub_category_id"
            class="form-select @error('sub_category_id') is-invalid @enderror">
            <option value="">{{ __('messages.select_sub_category') }}</option>
        </select>
        @error('sub_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('messages.status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>
                Active</option>
            <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>
                Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- ── Section 2: Pricing ── --}}
<h6 class="fw-semibold text-primary mb-3"><i class="bx bx-money me-2"></i>Pricing</h6>
<div class="row g-3 pb-4 mb-4 border-bottom">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Purchase Price <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="purchase_price"
            class="form-control @error('purchase_price') is-invalid @enderror"
            value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}" required>
        @error('purchase_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Selling Price <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="selling_price"
            class="form-control @error('selling_price') is-invalid @enderror"
            value="{{ old('selling_price', $product->selling_price ?? '0.00') }}" required>
        @error('selling_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Min Stock Alert Level</label>
        <input type="number" step="0.01" name="minimum_stock_alert"
            class="form-control @error('minimum_stock_alert') is-invalid @enderror"
            value="{{ old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00') }}">
        @error('minimum_stock_alert')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Tax %</label>
        <input type="number" step="0.01" name="tax_percentage"
            class="form-control @error('tax_percentage') is-invalid @enderror"
            value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}">
        @error('tax_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Discount %</label>
        <input type="number" step="0.01" name="discount_percentage"
            class="form-control @error('discount_percentage') is-invalid @enderror"
            value="{{ old('discount_percentage', $product->discount_percentage ?? '0.00') }}">
        @error('discount_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- ── Section 3: Opening Stock (CREATE only) ── --}}
@if (!isset($product) || isset($isCopy))
    <div class="pb-4 mb-4 border-bottom">
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch" name="add_opening_stock"
                id="addOpeningStock" value="1" {{ old('add_opening_stock') ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="addOpeningStock">
                <i class="bx bx-box me-1 text-success"></i>Add stock while creating product
            </label>
            <div class="form-text">Check this to add an initial stock quantity. A Purchase record will be created
                automatically for traceability.</div>
        </div>

        <div id="openingStockBox" class="{{ old('add_opening_stock') ? '' : 'd-none' }} mt-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold" for="supplier_id">Supplier <span
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold" for="initial_qty">Opening Qty <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="initial_qty" id="initial_qty"
                        class="form-control @error('initial_qty') is-invalid @enderror"
                        value="{{ old('initial_qty', '') }}" placeholder="e.g. 10">
                    <div class="form-text">Stock quantity to add. A purchase entry will be created automatically.</div>
                    @error('initial_qty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

{{-- ── Section 4: Technical Specifications ── --}}
<h6 class="fw-semibold text-success mb-3"><i class="bx bx-chip me-2"></i>Technical Specifications (Optional)</h6>
<div class="row g-3 pb-4 mb-4 border-bottom">
    <div class="col-md-3">
        <label class="form-label fw-semibold">Manufacturer</label>
        <input type="text" name="manufacturer" class="form-control"
            value="{{ old('manufacturer', $product->manufacturer ?? '') }}" placeholder="e.g. Intel, Asus">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Model Number</label>
        <input type="text" name="model_number" class="form-control"
            value="{{ old('model_number', $product->model_number ?? '') }}" placeholder="e.g. ROG-STRIX-Z790">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Part / Serial Number</label>
        <input type="text" name="part_number" class="form-control"
            value="{{ old('part_number', $product->part_number ?? '') }}" placeholder="e.g. 90MB1CS0-M0EAY0">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Warranty</label>
        <input type="text" name="warranty" class="form-control"
            value="{{ old('warranty', $product->warranty ?? '') }}" placeholder="e.g. 3 Years">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Color</label>
        <input type="text" name="color" class="form-control" value="{{ old('color', $product->color ?? '') }}"
            placeholder="e.g. Space Grey">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Weight</label>
        <input type="text" name="weight" class="form-control"
            value="{{ old('weight', $product->weight ?? '') }}" placeholder="e.g. 1.2 kg">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Country of Origin</label>
        <input type="text" name="country_of_origin" class="form-control"
            value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}" placeholder="e.g. Taiwan">
    </div>
</div>

{{-- ── Section 5: Media & Descriptions ── --}}
<h6 class="fw-semibold text-warning mb-3"><i class="bx bx-image me-2"></i>Media & Description</h6>
<div class="row g-4">
    <div class="col-md-6">
        {{-- Primary Image --}}
        <div class="card border bg-light mb-3">
            <div class="card-body p-3">
                <label class="form-label fw-semibold">Primary Image</label>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img id="imagePreview"
                        src="{{ isset($product) && $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=No+Image' }}"
                        class="rounded border" style="width:80px;height:80px;object-fit:contain;background:#fff;">
                    <div>
                        <button type="button" id="triggerImageBtn" class="btn btn-sm btn-outline-secondary mb-1">
                            <i class="bx bx-upload me-1"></i> Upload
                        </button>
                        <button type="button" id="removeImageBtn"
                            class="btn btn-sm btn-outline-danger {{ isset($product) && $product->image ? '' : 'd-none' }}">
                            <i class="bx bx-trash me-1"></i> Remove
                        </button>
                        <div class="form-text">PNG, JPG, WEBP. Max 2MB.</div>
                    </div>
                </div>
                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                <input type="hidden" name="remove_image" id="remove_image" value="0">
            </div>
        </div>
        {{-- Gallery --}}
        <div class="card border">
            <div class="card-body p-3">
                <label class="form-label fw-semibold">Gallery Images</label>
                <input type="file" name="gallery[]" id="galleryInput" multiple
                    class="form-control form-control-sm" accept="image/*">
                <input type="hidden" name="clear_gallery" id="clear_gallery" value="0">
                <input type="hidden" name="remove_gallery_images" id="remove_gallery_images" value="">
                @if (isset($product) && $product->gallery && count($product->gallery) > 0)
                    <div class="mt-3">
                        <small class="text-muted fw-semibold">Current Gallery:</small>
                        <div class="d-flex flex-wrap gap-2 mt-2" id="galleryPreviewContainer">
                            @foreach ($product->gallery as $galImg)
                                <div class="position-relative" data-image="{{ $galImg }}">
                                    <img src="{{ asset('uploads/products/' . $galImg) }}" class="rounded border"
                                        style="width:60px;height:60px;object-fit:cover;">
                                    <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 remove-gallery-img-btn"
                                        style="width:18px;height:18px;font-size:10px;line-height:1;">×</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="clearAllGalleryBtn"
                            class="btn btn-sm btn-link text-danger mt-2 p-0">Clear All Gallery</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Short Description</label>
            <textarea name="short_description" rows="3"
                class="form-control @error('short_description') is-invalid @enderror" placeholder="Key features summary...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
            @error('short_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
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

<div class="d-flex justify-content-end gap-2 pt-4 mt-2 border-top">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i>
        {{ isset($isCopy) ? 'Save Copied Product' : (isset($product) && $product->exists ? 'Update Product' : 'Save Product') }}
    </button>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── Sub-category dynamic load ──
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
            if (initialMainCatId) loadSubcategories(initialMainCatId, selectedSubCategoryId);

            // ── Opening-stock toggle ──
            $('#addOpeningStock').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#openingStockBox').removeClass('d-none');
                    $('#supplier_id').prop('required', true);
                    $('#initial_qty').prop('required', true);
                } else {
                    $('#openingStockBox').addClass('d-none');
                    $('#supplier_id').prop('required', false).val('');
                    $('#initial_qty').prop('required', false).val('');
                }
            });

            // Set required state on page load if checkbox is already checked (e.g. old input)
            if ($('#addOpeningStock').is(':checked')) {
                $('#supplier_id').prop('required', true);
                $('#initial_qty').prop('required', true);
            }

            // ── Primary image ──
            $('#triggerImageBtn').on('click', () => $('#imageInput').click());

            $('#imageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $('#imagePreview').attr('src', e.target.result);
                        $('#removeImageBtn').removeClass('d-none');
                        $('#remove_image').val('0');
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#removeImageBtn').on('click', function() {
                $('#imagePreview').attr('src', 'https://placehold.co/100x100/e2e8f0/94a3b8?text=No+Image');
                $('#imageInput').val('');
                $(this).addClass('d-none');
                $('#remove_image').val('1');
            });

            // ── Gallery ──
            let removedGalleryImages = [];

            $(document).on('click', '.remove-gallery-img-btn', function(e) {
                e.preventDefault();
                const container = $(this).closest('[data-image]');
                removedGalleryImages.push(container.data('image'));
                $('#remove_gallery_images').val(removedGalleryImages.join(','));
                container.remove();
            });

            $('#clearAllGalleryBtn').on('click', function(e) {
                e.preventDefault();
                $('#galleryPreviewContainer').empty();
                $('#clear_gallery').val('1');
                $(this).addClass('d-none');
            });
        });
    </script>
@endpush
