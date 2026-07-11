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
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Core i9 Processor" required>
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
                    <input type="text" name="code" id="product_code"
                        class="form-control fw-semibold @error('code') is-invalid @enderror"
                        value="{{ old('code', $product->code ?? '') }}" placeholder="e.g. PRD-001-GPAN" required>
                    @if (!isset($product) || !$product->exists)
                        <button type="button" class="btn btn-outline-primary" id="generateSkuBtn"
                            title="Auto-generate SKU">
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
                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                    value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="e.g. 8901234567890">
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Brand --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                    <option value="">{{ __('messages.select_brand') }}</option>
                    @foreach ($brands as $b)
                        <option value="{{ $b->id }}"
                            {{ old('brand_id', $product->brand_id ?? '') == $b->id ? 'selected' : '' }}>
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
                <select name="main_category_id" id="main_category_id"
                    class="form-select @error('main_category_id') is-invalid @enderror" required>
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}"
                            {{ old('main_category_id', $product->main_category_id ?? '') == $c->id ? 'selected' : '' }}>
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
                <select name="sub_category_id" id="sub_category_id"
                    class="form-select @error('sub_category_id') is-invalid @enderror">
                    <option value="">{{ __('messages.select_sub_category') }}</option>
                </select>
                @error('sub_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Name --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Name <span class="text-danger">*</span></label>
                <input type="text" name="unit_name" class="form-control @error('unit_name') is-invalid @enderror"
                    value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}" placeholder="Piece" required>
                @error('unit_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Code --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Code <span class="text-danger">*</span></label>
                <input type="text" name="unit_code" class="form-control @error('unit_code') is-invalid @enderror"
                    value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}" placeholder="PCS" required>
                @error('unit_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active"
                        {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active
                    </option>
                    <option value="inactive"
                        {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
        <span class="ms-auto badge bg-label-primary small" id="profitBadge">Profit: —</span>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            {{-- Purchase Price --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Purchase Price <span class="text-danger">*</span></label>
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
                <label class="form-label fw-semibold small">Selling Price <span class="text-danger">*</span></label>
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
                <label class="form-label fw-semibold small">Tax %</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" max="100" name="tax_percentage"
                        id="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror"
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
                <label class="form-label fw-semibold small">Min Stock Alert</label>
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

{{-- ══ SECTION 3 : Opening Stock (CREATE / COPY only) ═════════════════════════ --}}
@if (!$product->exists || isset($isCopy))
    <div class="form-section">
        <div class="form-section-header">
            <div class="sec-icon bg-label-warning"><i class="bx bx-box text-warning"></i></div>
            <div class="sec-title">Opening Stock</div>
            <span class="ms-auto badge bg-label-warning small">Create only</span>
        </div>
        <div class="form-section-body">
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch" name="add_opening_stock"
                    id="addOpeningStock" value="1" {{ old('add_opening_stock') ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="addOpeningStock">
                    <i class="bx bx-plus-circle me-1 text-success"></i> {{ __(\'messages.add_initial_stock_hint\') }}
                </label>
                <div class="form-text">When enabled, a Purchase record will be created automatically for traceability.
                </div>
            </div>

            <div id="openingStockBox" class="{{ old('add_opening_stock') ? '' : 'd-none' }} mt-3">
                <div class="row g-3 align-items-end">
                    {{-- Supplier --}}
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small" for="supplier_id">
                            Supplier <span class="text-danger">*</span>
                        </label>
                        <select name="supplier_id" id="supplier_id"
                            class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="">{{ __('messages.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                        <input type="number" step="0.01" min="0.01" name="initial_qty" id="initial_qty"
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
                class="ms-2 badge bg-label-secondary small fw-normal">Optional</span></div>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Manufacturer</label>
                <input type="text" name="manufacturer" class="form-control"
                    value="{{ old('manufacturer', $product->manufacturer ?? '') }}" placeholder="e.g. Intel, Asus">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Model Number</label>
                <input type="text" name="model_number" class="form-control"
                    value="{{ old('model_number', $product->model_number ?? '') }}"
                    placeholder="e.g. ROG-STRIX-Z790">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Part / Serial Number</label>
                <input type="text" name="part_number" class="form-control"
                    value="{{ old('part_number', $product->part_number ?? '') }}" placeholder="e.g. 90MB1CS0-M0EAY0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Warranty</label>
                <input type="text" name="warranty" class="form-control"
                    value="{{ old('warranty', $product->warranty ?? '') }}" placeholder="e.g. 3 Years">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Color</label>
                <input type="text" name="color" class="form-control"
                    value="{{ old('color', $product->color ?? '') }}" placeholder="e.g. Space Grey">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Weight</label>
                <input type="text" name="weight" class="form-control"
                    value="{{ old('weight', $product->weight ?? '') }}" placeholder="e.g. 1.2 kg">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Country of Origin</label>
                <input type="text" name="country_of_origin" class="form-control"
                    value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}"
                    placeholder="e.g. Taiwan">
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
                <div class="card border bg-light mb-3">
                    <div class="card-body p-3">
                        <label class="form-label fw-semibold small">Primary Image</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img id="imagePreview"
                                src="{{ isset($product) && $product->image
                                    ? asset('uploads/products/' . $product->image)
                                    : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=No+Image' }}"
                                class="rounded border"
                                style="width:80px;height:80px;object-fit:contain;background:#fff;">
                            <div>
                                <button type="button" id="triggerImageBtn"
                                    class="btn btn-sm btn-outline-secondary mb-1">
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
                        <label class="form-label fw-semibold small">Gallery Images</label>
                        <input type="file" name="gallery[]" id="galleryInput" multiple
                            class="form-control form-control-sm" accept="image/*">
                        <input type="hidden" name="clear_gallery" id="clear_gallery" value="0">
                        <input type="hidden" name="remove_gallery_images" id="remove_gallery_images"
                            value="">

                        @if (isset($product) && $product->gallery && count($product->gallery) > 0)
                            <div class="mt-3">
                                <small class="text-muted fw-semibold">Current Gallery:</small>
                                <div class="d-flex flex-wrap gap-2 mt-2" id="galleryPreviewContainer">
                                    @foreach ($product->gallery as $galImg)
                                        <div class="position-relative" data-image="{{ $galImg }}">
                                            <img src="{{ asset('uploads/products/' . $galImg) }}"
                                                class="rounded border"
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

            {{-- Right: Descriptions --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Short Description</label>
                    <textarea name="short_description" rows="3"
                        class="form-control @error('short_description') is-invalid @enderror" placeholder="Key features summary...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold small">Full Description / Specifications</label>
                    <textarea name="full_description" rows="9"
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

{{-- ══ Form Actions ════════════════════════════════════════════════════════════ --}}
<div class="d-flex justify-content-end gap-2 pt-2">
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

