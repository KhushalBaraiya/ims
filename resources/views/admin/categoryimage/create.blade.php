@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ isset($categoryImage) ? __('admin.edit_category_image') : __('admin.add_category_image') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.categoryimage.index') }}">{{ __('admin.category_images') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($categoryImage) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.categoryimage.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="categoryImageForm"
        action="{{ isset($categoryImage) ? route('admin.categoryimage.update', $categoryImage->id) : route('admin.categoryimage.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($categoryImage))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-image-alt me-2 text-primary"></i>{{ __('admin.category_image') }}
                            {{ __('admin.details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.main_category') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="category_id" class="select2-category form-select form-select-lg w-100">
                                    <option value="">{{ __('admin.select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $categoryImage->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_categoryimage_title') }}"
                                value="{{ old('title', $categoryImage->title ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.image') }}
                                {!! isset($categoryImage) ? '' : '<span class="text-danger">*</span>' !!}</label>
                            <input type="file" name="image" id="catImgInput" class="form-control form-control-lg"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                            <div class="form-text">{{ __('admin.jpg_png_webp') }}</div>
                            <div class="mt-3" id="catImgPreviewWrapper"
                                style="display:{{ isset($categoryImage) && $categoryImage->image ? 'block' : 'none' }}">
                                <div class="position-relative d-inline-block">
                                    <img id="catImgPreview"
                                        src="{{ isset($categoryImage) && $categoryImage->image ? asset('uploads/categoryimage/' . $categoryImage->image) : '' }}"
                                        class="rounded border img-preview-contain">
                                    <button type="button" id="catImgRemove"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center img-remove-btn"
                                        title="{{ __('admin.remove') }}">
                                        <i class="bx bx-x icon-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $categoryImage->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $categoryImage->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $categoryImage->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($categoryImage) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.categoryimage.index') }}" class="btn btn-outline-secondary btn-lg"><i
                                    class="bx bx-x me-1"></i>{{ __('admin.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Custom file size rule
            $.validator.addMethod('filesize', function(value, element, param) {
                return element.files.length === 0 || element.files[0].size <= param;
            }, '{{ __('admin.val_image_size') }}');

            // Initialize Select2 for category
            $('.select2-category').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_category') }}',
                allowClear: true,
                width: '100%'
            });

            $('#categoryImageForm').validate({
                rules: {
                    category_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    image: {
                        required: {{ isset($categoryImage) ? 'false' : 'true' }},
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    }
                },
                messages: {
                    category_id: {
                        required: "{{ __('admin.val_category_select_required') }}"
                    },
                    title: {
                        required: "{{ __('admin.val_title_required') }}"
                    },
                    image: {
                        required: '{{ __('admin.val_image_required') }}',
                        accept: "{{ __('admin.val_image_accept') }}",
                        filesize: "{{ __('admin.val_image_size') }}"
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
                highlight: function(el) {
                    $(el).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(el) {
                    $(el).removeClass('is-invalid');
                    var val = $(el).val();
                    if (val && val.length > 0) {
                        $(el).addClass('is-valid');
                    } else {
                        $(el).removeClass('is-valid');
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').prop('disabled', true).html(
                        '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.processing') }}'
                    );
                    form.submit();
                }
            });
        });
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

        // Image preview
        document.getElementById('catImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('catImgPreviewWrapper');
            const preview = document.getElementById('catImgPreview');
            if (!file) return;
            if (file.size > 2097152) {
                wrapper.style.display = 'none';
                return;
            }
            const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                wrapper.style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                wrapper.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        // Remove image
        document.getElementById('catImgRemove').addEventListener('click', function() {
            const input = document.getElementById('catImgInput');
            const wrapper = document.getElementById('catImgPreviewWrapper');
            const preview = document.getElementById('catImgPreview');
            input.value = '';
            preview.src = '';
            wrapper.style.display = 'none';
        });
    </script>
@endpush
