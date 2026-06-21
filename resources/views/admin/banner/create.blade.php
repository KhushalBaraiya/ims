@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($banner) ? __('admin.edit_banner') : __('admin.add_banner') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.banner.index') }}">{{ __('admin.banners') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($banner) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="bannerForm"
        action="{{ isset($banner) ? route('admin.banner.update', $banner->id) : route('admin.banner.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($banner))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-detail me-2 text-primary"></i>{{ __('admin.banner_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_banner_title') }}"
                                value="{{ old('title', $banner->title ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.sub_title_label') }}</label>
                            <input type="text" name="sub_title" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_banner_subtitle') }}"
                                value="{{ old('sub_title', $banner->sub_title ?? '') }}">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.btn_text_label') }}</label>
                                <input type="text" name="btn_text" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_banner_btn') }}"
                                    value="{{ old('btn_text', $banner->btn_text ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.offer_discount_label') }}</label>
                                <input type="text" name="offer_discountLabel" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_banner_offer_label') }}"
                                    value="{{ old('offer_discountLabel', $banner->offer_discountLabel ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-image-alt me-2 text-info"></i>{{ __('admin.banner_image') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <input type="file" name="image" id="bannerImgInput" class="form-control form-control-lg"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">{{ __('admin.jpg_png_webp') }}</div>
                        <div class="mt-3" id="bannerImgWrapper"
                            style="display:{{ isset($banner) && $banner->image ? 'block' : 'none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="bannerImgPreview"
                                    src="{{ isset($banner) && $banner->image ? asset('uploads/banner/' . $banner->image) : '' }}"
                                    class="rounded border img-preview-contain">
                                <button type="button" id="bannerImgRemove"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center img-remove-btn"
                                    title="{{ __('admin.remove') }}">
                                    <i class="bx bx-x icon-sm"></i>
                                </button>
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
                                        {{ old('status', $banner->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $banner->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $banner->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($banner) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary btn-lg"><i
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

            $('#bannerForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    sub_title: {
                        required: true,
                        maxlength: 255
                    },
                    btn_text: {
                        required: true,
                        maxlength: 255
                    },
                    offer_discountLabel: {
                        required: true,
                        maxlength: 255
                    },
                    image: {
                        required: {{ isset($banner) ? 'false' : 'true' }},
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    }
                },
                messages: {
                    title: {
                        required: "{{ __('admin.val_banner_title_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    sub_title: {
                        required: "{{ __('admin.val_sub_title_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    btn_text: {
                        required: "{{ __('admin.val_button_text_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    offer_discountLabel: {
                        required: "{{ __('admin.val_offer_discount_label_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    image: {
                        required: '{{ __('admin.val_image_required') }}',
                        accept: 'Only JPG, PNG, WEBP allowed (max 2MB)',
                        filesize: 'Image must be less than 2MB'
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                errorPlacement: function(error, element) {
                    element.after(error);
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
        // Image preview with validation
        document.getElementById('bannerImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('bannerImgWrapper');
            const preview = document.getElementById('bannerImgPreview');
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
        document.getElementById('bannerImgRemove').addEventListener('click', function() {
            document.getElementById('bannerImgInput').value = '';
            document.getElementById('bannerImgPreview').src = '';
            document.getElementById('bannerImgWrapper').style.display = 'none';
        });
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
    </script>
@endpush
