@csrf

{{-- Section 1: Basic Information --}}
<h6 class="fw-semibold text-primary mb-3"><i class="bx bx-info-circle me-2"></i>Basic Information</h6>
<div class="row g-3 pb-4 mb-4 border-bottom">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Core i9 Processor" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Product Code (SKU) <span class="text-danger">*</span></label>
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
        <label class="form-label fw-semibold">Brand <span class="text-danger">*</span></label>
        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
            <option value="">Select Brand</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}"
                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Unit Name <span class="text-danger">*</span></label>
        <input type="text" name="unit_name" class="form-control @error('unit_name') is-invalid @enderror"
            value="{{ old('unit_name', $product->unit_name ?? '') }}" placeholder="e.g. Piece, Box" required>
        @error('unit_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Unit Code <span class="text-danger">*</span></label>
        <input type="text" name="unit_code" class="form-control @error('unit_code') is-invalid @enderror"
            value="{{ old('unit_code', $product->unit_code ?? '') }}" placeholder="e.g. PCS, BOX" required>
        @error('unit_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Base Unit</label>
        <input type="text" name="base_unit" class="form-control @error('base_unit') is-invalid @enderror"
            value="{{ old('base_unit', $product->base_unit ?? '') }}" placeholder="e.g. unit, kg">
        @error('base_unit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Main Category <span class="text-danger">*</span></label>
        <select name="main_category_id" id="main_category_id"
            class="form-select @error('main_category_id') is-invalid @enderror" required>
            <option value="">Select Main Category</option>
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
    <div class="col-md-3">
        <label class="form-label fw-semibold">Sub Category <span class="text-danger">*</span></label>
        <select name="sub_category_id" id="sub_category_id"
            class="form-select @error('sub_category_id') is-invalid @enderror" required>
            <option value="">Select Sub Category</option>
        </select>
        @error('sub_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Section 2: Pricing & Inventory --}}
<h6 class="fw-semibold text-primary mb-3"><i class="bx bx-money me-2"></i>Pricing & Inventory</h6>
<div class="row g-3 pb-4 mb-4 border-bottom">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
            <option value="">Select Supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}</option>
            @endforeach
        </select>
        @error('supplier_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">MRP</label>
        <input type="number" step="0.01" name="mrp" class="form-control @error('mrp') is-invalid @enderror"
            value="{{ old('mrp', $product->mrp ?? '0.00') }}">
        @error('mrp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
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
    <div class="col-md-2">
        <label class="form-label fw-semibold">Tax %</label>
        <input type="number" step="0.01" name="tax_percentage"
            class="form-control @error('tax_percentage') is-invalid @enderror"
            value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}">
        @error('tax_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Discount %</label>
        <input type="number" step="0.01" name="discount_percentage"
            class="form-control @error('discount_percentage') is-invalid @enderror"
            value="{{ old('discount_percentage', $product->discount_percentage ?? '0.00') }}">
        @error('discount_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Opening Stock Qty</label>
        <input type="number" step="0.01" name="opening_stock"
            class="form-control @error('opening_stock') is-invalid @enderror"
            value="{{ old('opening_stock', $product->stock->quantity ?? ($product->opening_stock ?? '0.00')) }}">
        @error('opening_stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Min Stock Alert Level</label>
        <input type="number" step="0.01" name="minimum_stock_alert"
            class="form-control @error('minimum_stock_alert') is-invalid @enderror"
            value="{{ old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00') }}">
        @error('minimum_stock_alert')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>
                Active</option>
            <option value="inactive"
                {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Section 3: Technical Specifications --}}
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
    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" name="is_featured" value="1"
                id="isFeatured" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="isFeatured">Featured Product</label>
        </div>
    </div>
</div>

{{-- Section 4: Media & Descriptions --}}
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
        <i class="bx bx-save me-1"></i> {{ isset($product) ? 'Update Product' : 'Save Product' }}
    </button>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            const subCategories = @json($subCategories);
            const selectedSubCategoryId = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";

            function loadSubcategories(mainCategoryId, preselectedId = '') {
                const subSelect = $('#sub_category_id');
                subSelect.html('<option value="">Select Sub Category</option>');
                subCategories.filter(s => s.main_category_id == mainCategoryId).forEach(s => {
                    subSelect.append(
                        `<option value="${s.id}" ${s.id == preselectedId ? 'selected' : ''}>${s.name}</option>`
                        );
                });
            }

            $('#main_category_id').on('change', function() {
                loadSubcategories($(this).val());
            });

            const initialMainCatId = $('#main_category_id').val();
            if (initialMainCatId) loadSubcategories(initialMainCatId, selectedSubCategoryId);

            // Primary image
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

            // Gallery
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
