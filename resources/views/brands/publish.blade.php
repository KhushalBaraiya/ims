<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bx bx-send me-2 text-primary"></i>{{ __('messages.publish') }}</h6>
    </div>
    <div class="card-body p-4">
        <div class="mb-4">
            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
            <div class="d-flex align-items-center gap-3">
                <input type="hidden" name="status" value="inactive">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="statusToggle" name="status"
                        value="active" {{ old('status', $brand->status ?? 'active') == 'active' ? 'checked' : '' }}>
                </div>
                <span id="statusLabel"
                    class="fw-semibold {{ old('status', $brand->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                    {{ old('status', $brand->status ?? 'active') == 'active' ? __('messages.active') : __('messages.inactive') }}
                </span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>
                {{ isset($brand) ? __('messages.edit_brand') : __('messages.add_brand') }}
            </button>
            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                lbl.textContent = this.checked ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}';
                lbl.className = 'fw-semibold ' + (this.checked ? 'text-success' : 'text-danger');
            });
        }
    </script>
@endpush
