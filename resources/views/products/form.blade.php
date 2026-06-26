@csrf

{{-- ══ SECTION 1 : Basic Information ══════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-primary"><i class="bx bx-info-circle text-primary"></i></div>
        <div class="sec-title">Basic Information</div>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Core i9 Processor" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">SKU / Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                    value="{{ old('code', $product->code ?? '') }}" placeholder="e.g. LPT-i9-001" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Barcode (EAN/ISBN)</label>
                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                    value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="e.g. 8901234567890">
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                    <option value="">Select Brand</option>
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
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Main Category <span class="text-danger">*</span></label>
                <select name="main_category_id" id="main_category_id"
                    class="form-select @error('main_category_id') is-invalid @enderror" required>
                    <option value="">Select Category</option>
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
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Sub Category</label>
                <select name="sub_category_id" id="sub_category_id"
                    class="form-select @error('sub_category_id') is-invalid @enderror">
                    <option value="">Select Sub Category</option>
                </select>
                @error('sub_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Name <span class="text-danger">*</span></label>
                <input type="text" name="unit_name" class="form-control @error('unit_name') is-invalid @enderror"
                    value="{{ old('unit_name', $product->unit_name ?? 'Piece') }}" placeholder="Piece" required>
                @error('unit_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Unit Code <span class="text-danger">*</span></label>
                <input type="text" name="unit_code" class="form-control @error('unit_code') is-invalid @enderror"
                    value="{{ old('unit_code', $product->unit_code ?? 'PCS') }}" placeholder="PCS" required>
                @error('unit_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
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

{{-- ══ SECTION 2 : Pricing ════════════════════════════ --}}
<div class="form-section">
    <div class="form-section-header">
        <div class="sec-icon bg-label-success"><i class="bx bx-money text-success"></i></div>
        <div class="sec-title">Pricing & Stock Alert</div>
        <span class="ms-auto badge bg-label-primary small" id="profitBadge">Profit: —</span>
    </div>
    <div class="form-section-body">
        <div class="row g-3">
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
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Tax %</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="tax_percentage"
                        class="form-control @error('tax_percentage') is-invalid @enderror"
                        value="{{ old('tax_percentage', $product->tax_percentage ?? '0.00') }}">
                    <span class="input-group-text">%</span>
                    @error('tax_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Discount %</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="discount_percentage"
                        class="form-control @error('discount_percentage') is-invalid @enderror"
                        value="{{ old('discount_percentage', $product->discount_percentage ?? '0.00') }}">
                    <span class="input-group-text">%</span>
                    @error('discount_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
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
