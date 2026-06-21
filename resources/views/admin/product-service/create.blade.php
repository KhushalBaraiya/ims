@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ isset($service) ? __('admin.edit_product_service') : __('admin.add_product_service') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.product-service.index') }}">{{ __('admin.product_service_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($service) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product-service.index') }}" class="btn btn-outline-secondary btn-lg">
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
    <form id="serviceForm"
        action="{{ isset($service) ? route('admin.product-service.update', $service->id) : route('admin.product-service.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($service))
            @method('PUT')
        @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-briefcase me-2 text-primary"></i>{{ __('admin.service_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.service_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_service_name') }}"
                                value="{{ old('name', $service->name ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.description') }}</label>
                            <textarea name="description" rows="4" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_service_desc') }}">{{ old('description', $service->description ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.price') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="price" class="form-control"
                                    placeholder="{{ __('admin.ph_price') }}"
                                    value="{{ old('price', $service->price ?? '') }}">
                            </div>
                            <div class="form-text">{{ __('admin.enter_0_free_service') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-image-alt me-2 text-info"></i>{{ __('admin.service_image') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <input type="file" name="image" id="serviceImgInput" class="form-control form-control-lg"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">{{ __('admin.jpg_png_webp') }}</div>
                        <div class="mt-3" id="serviceImgWrapper"
                            style="display:{{ isset($service) && $service->image ? 'block' : 'none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="serviceImgPreview"
                                    src="{{ isset($service) && $service->image ? asset('uploads/product-services/' . $service->image) : '' }}"
                                    class="rounded border img-preview-service">
                                <button type="button" id="serviceImgRemove"
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
                                        {{ old('status', $service->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $service->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $service->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bx bx-save me-1"></i>
                                {{ isset($service) ? __('admin.update') : __('admin.save') }}</button>
                            <a href="{{ route('admin.product-service.index') }}"
                                class="btn btn-outline-secondary btn-lg"><i
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

            $('#serviceForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    image: {
                        {{ isset($brand) ? '' : 'required: true,' }}
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    }
                },
                messages: {
                    name: {
                        required: "{{ __('admin.val_service_name_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}"
                    },
                    description: {
                        required: "{{ __('admin.val_description_required') }}",
                        minlength: "{{ __('admin.val_min_10') }}"
                    },
                    price: {
                        required: "{{ __('admin.val_price_required') }}",
                        number: "{{ __('admin.val_price_number') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    image: {
                        required: "{{ __('admin.val_service_image_required') }}",
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
        // Image preview with validation
        document.getElementById('serviceImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('serviceImgWrapper');
            const preview = document.getElementById('serviceImgPreview');
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
        document.getElementById('serviceImgRemove').addEventListener('click', function() {
            document.getElementById('serviceImgInput').value = '';
            document.getElementById('serviceImgPreview').src = '';
            document.getElementById('serviceImgWrapper').style.display = 'none';
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
