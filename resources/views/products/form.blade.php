@csrf

{{-- ══ SECTION 1 : Basic Information ══════════════════════════════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-primary"><i class="bx bx-info-circle text-primary"></i></div>
        <div class="sec-title">Basic Information</div>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            {{-- Product Name --}}
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label>
                <input class="form-control @error('name') is-invalid @enderror" name="name"
                    placeholder="e.g. Core i9 Processor" required type="text"
                    value="{{ old('name', $product->name ?? '') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- SKU --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold small">
                    SKU / Code <span class="text-danger">*</span>
                    <span class="text-muted fw-normal">(auto or manual)</span>
                </label>
                <div class="input-group">
                    <input class="form-control fw-semibold @error('code') is-invalid @enderror" id="product_code"
                        name="code" placeholder="e.g. PRD-001-GPAN" required type="text"
                        value="{{ old('code', $product->code ?? '') }}">
                    @if (!isset($product) || !$product->exists)
                        <button class="btn btn-outline-primary" id="generateSkuBtn" title="Auto-generate SKU"
                            type="button">
                            <i class="bx bx-revision"></i>
                        </button>
                    @endif
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Barcode --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Barcode (EAN/ISBN)</label>
                <input class="form-control @error('barcode') is-invalid @enderror" name="barcode"
                    placeholder="e.g. 8901234567890" type="text"
                    value="{{ old('barcode', $product->barcode ?? '') }}">
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Brand --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Brand <span class="text-danger">*</span></label>
                <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" required>
                    <option value="">{{ __('messages.select_brand') }}</option>
                    @foreach ($brands as $b)
                        <option {{ old('brand_id', $product->brand_id ?? '') == $b->id ? 'selected' : '' }}
                            value="{{ $b->id }}">
                            {{ $b->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Main Category --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Main Category <span class="text-danger">*</span></label>
                <select class="form-select @error('main_category_id') is-invalid @enderror" id="main_category_id"
                    name="main_category_id" required>
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach ($categories as $c)
                        <option
                            {{ old('main_category_id', $product->main_category_id ?? '') == $c->id ? 'selected' : '' }}
                            value="{{ $c->id }}">
                            {{ $c->name }}</option>
                    @endforeach
                </select>
                @error('main_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Sub Category --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Sub Category</label>
                <select class="form-select @error('sub_category_id') is-invalid @enderror" id="sub_category_id"
                    name="sub_category_id">
                    <option value="">{{ __('messages.select_sub_category') }}</option>
                </select>
                @error('sub_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Name --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Name <span class="text-danger">*</span></label>
                <input class="form-control @error('unit_name') is-invalid @enderror" name="unit_name"
                    placeholder="Piece" required type="text"
                    value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}">
                @error('unit_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Code --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Code <span class="text-danger">*</span></label>
                <input class="form-control @error('unit_code') is-invalid @enderror" name="unit_code" placeholder="PCS"
                    required type="text" value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}">
                @error('unit_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                    <option {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}
                        value="active">Active
                    </option>
                    <option {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}
                        value="inactive">Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- ══ SECTION 2 : Pricing & Stock Alert ══════════════════════════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-success"><i class="bx bx-money text-success"></i></div>
        <div class="sec-title">Pricing & Stock Alert</div>
        <span class="badge bg-label-primary small ms-auto" id="profitBadge">Profit: —</span>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            {{-- Purchase Price --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Purchase Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price"
                        name="purchase_price" required step="0.01" type="number"
                        value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}">
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Selling Price --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Selling Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input class="form-control @error('selling_price') is-invalid @enderror" id="selling_price"
                        name="selling_price" required step="0.01" type="number"
                        value="{{ old('selling_price', $product->selling_price ?? '0.00') }}">
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Discount Amount --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Discount Amount</label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input class="form-control @error('discount_price_amount') is-invalid @enderror"
                        id="discount_price_amount" min="0" name="discount_price_amount" step="0.01"
                        type="number"
                        value="{{ old('discount_price_amount', $product->discount_price_amount ?? '0.00') }}">
                    @error('discount_price_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tax % --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Selling Tax %</label>
                <div class="input-group">
                    <input class="form-control @error('tax_percentage') is-invalid @enderror" id="tax_percentage"
                        max="100" min="0" name="tax_percentage"
                        oninput="if(parseFloat(this.value)>100){this.value=100;}if(parseFloat(this.value)<0){this.value=0;}"
                        step="0.01" type="number"
                        value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}">
                    <span class="input-group-text">%</span>
                    @error('tax_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Max 100%</div>
            </div>

            {{-- Min Stock Alert --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Min Stock Alert</label>
                <input class="form-control @error('minimum_stock_alert') is-invalid @enderror"
                    name="minimum_stock_alert" placeholder="e.g. 5" step="0.01" type="number"
                    value="{{ old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00') }}">
                @error('minimum_stock_alert')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- ══ SECTION 3 : Opening Stock (CREATE / COPY only) ═════════════════════════ --}}
@if (!$product->exists || isset($isCopy))
    <div class="form-section">
        <div class="form-section-header">
            <div class="sec-icon bg-label-warning"><i class="bx bx-box text-warning"></i></div>
            <div class="sec-title">Opening Stock</div>
            <span class="badge bg-label-warning small ms-auto">Create only</span>
        </div>
        <div class="form-section-body">
            <div class="form-check form-switch mb-0">
                <input {{ old('add_opening_stock') ? 'checked' : '' }} class="form-check-input" id="addOpeningStock"
                    name="add_opening_stock" role="switch" type="checkbox" value="1">
                <label class="form-check-label fw-semibold" for="addOpeningStock">
                    <i class="bx bx-plus-circle text-success me-1"></i> {{ __('messages.add_initial_stock_hint') }}
                </label>
                <div class="form-text">When enabled, a Purchase record will be created automatically for traceability.
                </div>
            </div>

            <div class="{{ old('add_opening_stock') ? '' : 'd-none' }} mt-3" id="openingStockBox">
                <div class="row g-3 align-items-end">
                    {{-- Supplier --}}
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small" for="supplier_id">
                            Supplier <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                            name="supplier_id">
                            <option value="">{{ __('messages.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                    value="{{ $supplier->id }}">
                                    {{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Opening Qty --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small" for="initial_qty">
                            Opening Qty <span class="text-danger">*</span>
                        </label>
                        <input class="form-control @error('initial_qty') is-invalid @enderror" id="initial_qty"
                            min="0.01" name="initial_qty" placeholder="e.g. 10" step="0.01" type="number"
                            value="{{ old('initial_qty', '') }}">
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
                                <div class="fw-bold" id="openingStockTotal">—</div>
                                <div class="form-text mb-0">Qty × Purchase Price. A Purchase record is auto-created.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- ══ SECTION 4 : Technical Specifications ═══════════════════════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-info"><i class="bx bx-chip text-info"></i></div>
        <div class="sec-title">Technical Specifications <span
                class="badge bg-label-secondary small fw-normal ms-2">Optional</span></div>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Manufacturer</label>
                <input class="form-control" name="manufacturer" placeholder="e.g. Intel, Asus" type="text"
                    value="{{ old('manufacturer', $product->manufacturer ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Model Number</label>
                <input class="form-control" name="model_number" placeholder="e.g. ROG-STRIX-Z790" type="text"
                    value="{{ old('model_number', $product->model_number ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Part / Serial Number</label>
                <input class="form-control" name="part_number" placeholder="e.g. 90MB1CS0-M0EAY0" type="text"
                    value="{{ old('part_number', $product->part_number ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Warranty</label>
                <input class="form-control" name="warranty" placeholder="e.g. 3 Years" type="text"
                    value="{{ old('warranty', $product->warranty ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Color</label>
                <input class="form-control" name="color" placeholder="e.g. Space Grey" type="text"
                    value="{{ old('color', $product->color ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Weight</label>
                <input class="form-control" name="weight" placeholder="e.g. 1.2 kg" type="text"
                    value="{{ old('weight', $product->weight ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Country of Origin</label>
                <input class="form-control" name="country_of_origin" placeholder="e.g. Taiwan" type="text"
                    value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}">
            </div>
        </div>
    </div>
</div>

{{-- ══ SECTION 5 : Media & Description ════════════════════════════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-warning"><i class="bx bx-image text-warning"></i></div>
        <div class="sec-title">Media & Description</div>
    </div>
    <div class="form-section-body">
        <div class="row g-4">

            {{-- Left: Images --}}
            <div class="col-md-6">
                {{-- Primary Image --}}
                <div class="card bg-light mb-3 border">
                    <div class="card-body p-3">
                        <label class="form-label fw-semibold small">Primary Image</label>
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <img class="rounded border" id="imagePreview"
                                src="{{ isset($product) && $product->image
                                    ? asset('uploads/products/' . $product->image)
                                    : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=No+Image' }}"
                                style="width:80px;height:80px;object-fit:contain;background:#fff;">
                            <div>
                                <button class="btn btn-sm btn-outline-secondary mb-1" id="triggerImageBtn"
                                    type="button">
                                    <i class="bx bx-upload me-1"></i> Upload
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-danger {{ isset($product) && $product->image ? '' : 'd-none' }}"
                                    id="removeImageBtn" type="button">
                                    <i class="bx bx-trash me-1"></i> Remove
                                </button>
                                <div class="form-text">PNG, JPG, WEBP. Max 2MB.</div>
                            </div>
                        </div>
                        <input accept="image/*" class="d-none" id="imageInput" name="image" type="file">
                        <input id="remove_image" name="remove_image" type="hidden" value="0">
                    </div>
                </div>

                {{-- Gallery --}}
                <div class="card border">
                    <div class="card-body p-3">
                        <label class="form-label fw-semibold small d-flex align-items-center gap-2">
                            <i class="bx bx-images text-primary"></i> Gallery Images
                            <span class="badge bg-label-secondary fw-normal">Multi-select</span>
                        </label>

                        {{-- Drag-drop zone --}}
                        <div id="galleryDropZone"
                            style="border:2px dashed var(--bs-border-color);border-radius:10px;padding:18px 12px;
                                    text-align:center;cursor:pointer;transition:border-color .2s,background .2s;
                                    background:var(--bs-body-bg);">
                            <i class="bx bx-cloud-upload d-block mb-1 text-muted" style="font-size:2rem;"></i>
                            <p class="mb-1 small fw-semibold text-muted">Drag & drop images here</p>
                            <p class="mb-2 text-muted" style="font-size:.72rem;">or click to browse — PNG, JPG, WEBP ·
                                Max 2MB each</p>
                            <button class="btn btn-sm btn-outline-primary" type="button" id="galleryBrowseBtn">
                                <i class="bx bx-folder-open me-1"></i> Browse Files
                            </button>
                        </div>
                        <input accept="image/*" class="d-none" id="galleryInput" multiple name="gallery[]"
                            type="file">
                        <input id="clear_gallery" name="clear_gallery" type="hidden" value="0">
                        <input id="remove_gallery_images" name="remove_gallery_images" type="hidden"
                            value="">

                        {{-- New upload preview tiles --}}
                        <div id="newGalleryPreviews" class="d-flex flex-wrap gap-2 mt-3"></div>

                        {{-- Existing gallery --}}
                        @if (isset($product) && $product->gallery && count($product->gallery) > 0)
                            <div class="mt-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted fw-semibold">
                                        <i class="bx bx-check-circle text-success me-1"></i>
                                        Saved Gallery ({{ count($product->gallery) }} photos)
                                    </small>
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2" id="clearAllGalleryBtn"
                                        type="button" style="font-size:.72rem;">
                                        <i class="bx bx-trash me-1"></i> Clear All
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-2" id="galleryPreviewContainer">
                                    @foreach ($product->gallery as $galImg)
                                        <div class="position-relative gallery-existing-tile"
                                            data-image="{{ $galImg }}"
                                            style="width:72px;height:72px;border-radius:8px;overflow:hidden;
                                                    border:2px solid var(--bs-border-color);flex-shrink:0;">
                                            <img src="{{ asset('uploads/products/' . $galImg) }}"
                                                style="width:100%;height:100%;object-fit:cover;"
                                                onerror="this.closest('.gallery-existing-tile').remove()">
                                            <button class="remove-gallery-img-btn" type="button" title="Remove"
                                                style="position:absolute;top:2px;right:2px;
                                                       width:18px;height:18px;border-radius:50%;border:none;
                                                       background:rgba(220,53,69,.9);color:#fff;
                                                       font-size:11px;line-height:1;cursor:pointer;
                                                       display:flex;align-items:center;justify-content:center;">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Descriptions --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Short Description</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" name="short_description"
                        placeholder="Key features summary..." rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold small">Full Description / Specifications</label>
                    <textarea class="form-control @error('full_description') is-invalid @enderror" name="full_description"
                        placeholder="Complete specifications, box contents..." rows="9">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                    @error('full_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══ Form Actions ════════════════════════════════════════════════════════════ --}}
<div class="d-flex justify-content-end gap-2 pt-2">
    <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button class="btn btn-primary" type="submit">
        <i class="bx bx-save me-1"></i>
        {{ isset($isCopy) ? 'Save Copied Product' : (isset($product) && $product->exists ? 'Update Product' : 'Save Product') }}
    </button>
</div>

@push('scripts')
    <style>
        @keyframes fadeInTile {
            from {
                opacity: 0;
                transform: scale(.85);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #galleryDropZone:focus-within {
            border-color: var(--bs-primary) !important;
        }
    </style>
    <script>
        $(document).ready(function() {

            // ── Auto-generate SKU on page load ────────────────────────────────
            @if (!isset($product) || !$product->exists)
                function autoFillSku() {
                    $.ajax({
                        url: "{{ route('products.generate-sku') }}",
                        type: 'GET',
                        success: function(res) {
                            if (!$('#product_code').val()) {
                                $('#product_code').val(res.sku);
                            }
                        }
                    });
                }
                autoFillSku();
            @endif

            // ── Refresh button ────────────────────────────────────────────────
            $('#generateSkuBtn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>');
                $.ajax({
                    url: "{{ route('products.generate-sku') }}",
                    type: 'GET',
                    success: function(res) {
                        $('#product_code').val(res.sku).focus();
                        btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                    },
                    error: function() {
                        showAdminToast('Could not generate SKU.', 'error');
                        btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                    }
                });
            });

            // ── Sub-category dynamic load ──────────────────────────────────────────────
            const subCategories = @json($subCategories);
            const selectedSubCategoryId = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";

            function loadSubcategories(mainCategoryId, preselectedId) {
                const subSelect = $('#sub_category_id');

                // Destroy select2 if active before rebuilding options
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

            // Pre-load on edit / validation fail
            const initialMainCatId = $('#main_category_id').val();
            if (initialMainCatId) {
                loadSubcategories(initialMainCatId, selectedSubCategoryId);
            }

            // ── Profit badge ──────────────────────────────────────────────────────────
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
            updateProfitBadge(); // run on load for edit form

            // ── Tax % — enforce max 100 client-side ──────────────────────────────────
            $('input[name="tax_percentage"]').on('input change', function() {
                let val = parseFloat($(this).val());
                if (!isNaN(val) && val > 100) {
                    $(this).val(100);
                    if (typeof showAdminToast === 'function') {
                        showAdminToast('Tax % cannot exceed 100%.', 'warning');
                    }
                }
                if (!isNaN(val) && val < 0) {
                    $(this).val(0);
                }
            });

            // ── Opening-stock toggle ──────────────────────────────────────────────────
            function syncOpeningStockRequired(checked) {
                $('#supplier_id').prop('required', checked);
                $('#initial_qty').prop('required', checked);
                if (!checked) {
                    $('#supplier_id').val('');
                    $('#initial_qty').val('');
                }
            }

            $('#addOpeningStock').on('change', function() {
                const checked = $(this).is(':checked');
                $('#openingStockBox').toggleClass('d-none', !checked);
                syncOpeningStockRequired(checked);
            });

            // Set required state on page load (e.g. old() after validation fail)
            if ($('#addOpeningStock').is(':checked')) {
                syncOpeningStockRequired(true);
            }

            // ── Opening stock live total preview ─────────────────────────────────────
            function updateOpeningTotal() {
                const qty = parseFloat($('#initial_qty').val()) || 0;
                const price = parseFloat($('#purchase_price').val()) || 0;
                if (qty > 0 && price > 0) {
                    const total = (qty * price).toFixed(2);
                    const sym = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';
                    $('#openingStockTotal').text(sym + parseFloat(total).toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }));
                } else {
                    $('#openingStockTotal').text('—');
                }
            }

            $('#initial_qty, #purchase_price').on('input', updateOpeningTotal);
            updateOpeningTotal();

            // ── Primary image upload ──────────────────────────────────────────────────
            $('#triggerImageBtn').on('click', () => $('#imageInput').trigger('click'));

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

            // ── Gallery management ────────────────────────────────────────────────────
            let removedGalleryImages = [];

            // Drag-drop zone
            const $dropZone = $('#galleryDropZone');
            $('#galleryBrowseBtn').on('click', function(e) {
                e.stopPropagation();
                $('#galleryInput').trigger('click');
            });
            $dropZone.on('click', function() {
                $('#galleryInput').trigger('click');
            });

            $dropZone.on('dragover dragenter', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': 'var(--bs-primary)',
                    'background': 'rgba(105,108,255,.05)'
                });
            });
            $dropZone.on('dragleave drop', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': 'var(--bs-border-color)',
                    'background': 'var(--bs-body-bg)'
                });
                if (e.type === 'drop') {
                    const dt = e.originalEvent.dataTransfer;
                    if (dt && dt.files.length) handleGalleryFiles(dt.files);
                }
            });

            $('#galleryInput').on('change', function(e) {
                handleGalleryFiles(e.target.files);
            });

            function handleGalleryFiles(files) {
                const $container = $('#newGalleryPreviews');
                Array.from(files).forEach(function(file) {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const src = e.target.result;
                        const $tile = $(`
                            <div class="position-relative new-gallery-tile"
                                 style="width:72px;height:72px;border-radius:8px;overflow:hidden;
                                        border:2px solid var(--bs-primary);flex-shrink:0;animation:fadeInTile .25s ease;">
                                <img src="${src}" style="width:100%;height:100%;object-fit:cover;">
                                <button type="button" class="remove-new-tile"
                                    style="position:absolute;top:2px;right:2px;
                                           width:18px;height:18px;border-radius:50%;border:none;
                                           background:rgba(220,53,69,.9);color:#fff;
                                           font-size:11px;line-height:1;cursor:pointer;
                                           display:flex;align-items:center;justify-content:center;">×</button>
                            </div>`);
                        $container.append($tile);
                    };
                    reader.readAsDataURL(file);
                });
            }

            $(document).on('click', '.remove-new-tile', function(e) {
                e.preventDefault();
                $(this).closest('.new-gallery-tile').remove();
                // Reset input so same files can be re-added
                $('#galleryInput').val('');
            });

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
