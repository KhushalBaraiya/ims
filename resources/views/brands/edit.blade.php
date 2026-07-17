@extends('layouts.admin')
@section('title', __('messages.edit_brand') . ' — ' . $brand->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_brand') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">{{ __('messages.brands') }}</a></li>
                    <li class="breadcrumb-item active">{{ $brand->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form id="brandForm" method="POST" action="{{ route('brands.update', $brand->id) }}" enctype="multipart/form-data"
        data-validate="true">
        @csrf @method('PUT')
        <div class="row g-4">

            {{-- Left: Brand Details --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-award me-2 text-primary"></i>{{ __('messages.brand_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('messages.brand_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $brand->name) }}" placeholder="{{ __('messages.ph_brand_name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('messages.brand_code') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="slug" id="brandSlug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $brand->slug) }}" placeholder="e.g. NIKE" required>
                            <div class="form-text">{{ __('messages.slug_hint') }}</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">{{ __('messages.description_label') }}</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                placeholder="{{ __('messages.ph_brand_description') }}">{{ old('description', $brand->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4 d-flex flex-column gap-4">

                {{-- Image Upload Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-image me-2 text-primary"></i>{{ __('messages.brand_image') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Preview Area --}}
                        <div class="text-center mb-3">
                            <div id="imgPreviewWrap"
                                style="width:120px;height:120px;margin:0 auto;border-radius:50%;overflow:hidden;border:3px {{ $brand->image ? 'solid #696cff' : 'dashed #dee2e6' }};display:flex;align-items:center;justify-content:center;background:#f8f9fa;cursor:pointer;"
                                onclick="document.getElementById('imageInput').click()">
                                @if ($brand->image)
                                    <img id="previewImg" src="{{ asset('uploads/brands/' . $brand->image) }}"
                                        alt="{{ $brand->name }}"
                                        style="width:100%;height:100%;object-fit:cover;display:block;"
                                        onerror="this.style.display='none';document.getElementById('previewPlaceholder').style.display='block';">
                                    <span id="previewPlaceholder" style="display:none;">
                                        <i class="bx bx-camera text-muted" style="font-size:2.5rem;"></i>
                                    </span>
                                @else
                                    <img id="previewImg" src="" alt="Preview"
                                        style="width:100%;height:100%;object-fit:cover;display:none;">
                                    <span id="previewPlaceholder">
                                        <i class="bx bx-camera text-muted" style="font-size:2.5rem;"></i>
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted small mt-2 mb-0">{{ __('messages.image_hint') }}</p>
                        </div>

                        <input type="file" name="image" id="imageInput"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($brand->image)
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <input type="checkbox" name="remove_image" id="removeImage" value="1"
                                    class="form-check-input">
                                <label for="removeImage" class="form-check-label text-danger small fw-semibold">
                                    <i class="bx bx-trash me-1"></i>{{ __('messages.remove_image') }}
                                </label>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Publish Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">{{ __('messages.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="statusToggle"
                                        name="status" value="active"
                                        {{ old('status', $brand->status) === 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold {{ old('status', $brand->status) === 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $brand->status) === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> {{ __('messages.update') }}
                            </button>
                            <a href="{{ route('brands.show', $brand->id) }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-secondary"></i>{{ __('messages.information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted fw-semibold">ID</span>
                                <span class="fw-bold">#{{ $brand->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                                <span>{{ $brand->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                                <span>{{ $brand->updated_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>{{-- /Right Sidebar --}}

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // Status toggle label
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = '{{ __('messages.active') }}';
                    lbl.className = 'fw-semibold text-success';
                } else {
                    lbl.textContent = '{{ __('messages.inactive') }}';
                    lbl.className = 'fw-semibold text-danger';
                }
            });
        }

        // Slug uppercase
        document.getElementById('brandSlug').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
        });

        // Image preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const img = document.getElementById('previewImg');
                    const placeholder = document.getElementById('previewPlaceholder');
                    img.src = evt.target.result;
                    img.style.display = 'block';
                    placeholder.style.display = 'none';
                    document.getElementById('imgPreviewWrap').style.border = '3px solid #696cff';
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image checkbox — hide preview
        const removeChk = document.getElementById('removeImage');
        if (removeChk) {
            removeChk.addEventListener('change', function() {
                const img = document.getElementById('previewImg');
                const placeholder = document.getElementById('previewPlaceholder');
                const wrap = document.getElementById('imgPreviewWrap');
                if (this.checked) {
                    img.style.display = 'none';
                    placeholder.style.display = 'block';
                    wrap.style.border = '3px dashed #dee2e6';
                } else {
                    img.style.display = 'block';
                    placeholder.style.display = 'none';
                    wrap.style.border = '3px solid #696cff';
                }
            });
        }
    </script>
@endpush
