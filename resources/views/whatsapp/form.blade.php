{{-- Template Name --}}
<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.wa_template_name_label') }} <span
            class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        placeholder="{{ __('messages.wa_template_name_ph') }}" value="{{ old('name', $template->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Message --}}
<div class="mb-3">
    <label class="form-label fw-semibold">{{ __('messages.wa_message_body_label') }} <span
            class="text-danger">*</span></label>
    <textarea name="message" id="tplMessage" class="form-control @error('message') is-invalid @enderror" rows="6"
        placeholder="{{ __('messages.wa_message_body_ph') }}" required>{{ old('message', $template->message ?? '') }}</textarea>
    <div class="d-flex justify-content-between mt-1">
        <div class="form-text text-muted">{{ __('messages.wa_plain_text_hint') }}</div>
        <div class="form-text text-muted"><span id="tplCharCount">0</span> {{ __('messages.wa_chars_label') }}</div>
    </div>
    @error('message')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Quick insert emojis --}}
<div class="mb-4">
    <div class="small text-muted fw-semibold mb-2">{{ __('messages.wa_quick_insert') }}</div>
    <div class="d-flex flex-wrap gap-2">
        @foreach (['👋', '✅', '🚚', '💰', '🎉', '⚠️', '📦', '🙏', '💬', '📞'] as $emoji)
            <button type="button" class="btn btn-sm btn-outline-secondary emoji-btn" data-emoji="{{ $emoji }}"
                style="font-size:1rem;padding:2px 8px;">{{ $emoji }}</button>
        @endforeach
    </div>
</div>

{{-- Status --}}
<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('messages.wa_status_label') }} <span
            class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="active" {{ old('status', $template->status ?? 'active') === 'active' ? 'selected' : '' }}>
            {{ __('messages.wa_active_option') }}</option>
        <option value="inactive" {{ old('status', $template->status ?? '') === 'inactive' ? 'selected' : '' }}>
            {{ __('messages.wa_inactive_option') }}</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-2">
    <a href="{{ route('whatsapp.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> {{ __('messages.wa_cancel') }}
    </a>
    <button type="submit" class="btn btn-success">
        <i class="bx bx-save me-1"></i>
        {{ isset($template) && $template->exists ? __('messages.wa_update_template') : __('messages.wa_save_template') }}
    </button>
</div>

@push('scripts')
    <script>
        // Char count
        const ta = document.getElementById('tplMessage');
        const cc = document.getElementById('tplCharCount');
        if (ta && cc) {
            cc.textContent = ta.value.length;
            ta.addEventListener('input', () => cc.textContent = ta.value.length);
        }

        // Emoji insert
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const emoji = this.dataset.emoji;
                const pos = ta.selectionStart;
                const val = ta.value;
                ta.value = val.slice(0, pos) + emoji + val.slice(pos);
                ta.selectionStart = ta.selectionEnd = pos + emoji.length;
                ta.focus();
                cc.textContent = ta.value.length;
            });
        });
    </script>
@endpush
