@csrf

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.unit_name') }} <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $unit->name ?? '') }}" placeholder="e.g. Piece, Box, Kilogram" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.short_name') }} <span class="text-danger">*</span></label>
    <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror"
        value="{{ old('short_name', $unit->short_name ?? '') }}" placeholder="e.g. Pcs, Box, Kg" required>
    <div class="form-text">{{ __('messages.short_name_hint') }}</div>
    @error('short_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
