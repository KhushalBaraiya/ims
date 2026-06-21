@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($review) ? __('admin.edit_review') : __('admin.add_review') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">{{ __('admin.reviews') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($review) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="reviewForm"
        action="{{ isset($review) ? route('admin.reviews.update', $review->id) : route('admin.reviews.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($review))
            @method('PUT')
        @endif

        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- REVIEW DETAILS --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-star me-2 text-primary"></i>{{ __('admin.review_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- USER --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="user_id"
                                        class="select2-user form-select form-select-lg w-100 @error('user_id') is-invalid @elseif(old('user_id')) is-valid @enderror">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $review->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PRODUCT --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.product') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="product_id" id="productSelect"
                                        class="select2-product form-select form-select-lg @error('product_id') is-invalid @elseif(old('product_id')) is-valid @enderror w-100">
                                        <option value="">{{ __('admin.select_product') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-images='@json($product->images ?? [])'
                                                {{ old('product_id', $review->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_id')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div id="productImagePreview" class="mt-2 d-none">
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-light"></div>
                                </div>
                            </div>

                            {{-- TITLE --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">{{ __('admin.title') }}</label>
                                <input type="text" name="title"
                                    class="form-control form-control-lg @error('title') is-invalid @elseif(old('title')) is-valid @enderror"
                                    placeholder="{{ __('admin.ph_review_title') }}"
                                    value="{{ old('title', $review->title ?? '') }}">
                                @error('title')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- RATING --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.rating') }} (1–5) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="rating" min="1" max="5"
                                        class="form-control @error('rating') is-invalid @elseif(old('rating')) is-valid @enderror"
                                        placeholder="{{ __('admin.ph_review_rating') }}"
                                        value="{{ old('rating', $review->rating ?? '') }}">
                                    <span class="input-group-text bg-light"><i class="bx bxs-star text-warning"></i></span>
                                </div>
                                @error('rating')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- COMMENT --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.comment') }}</label>
                                <textarea name="comment" rows="4"
                                    class="form-control form-control-lg @error('comment') is-invalid @elseif(old('comment')) is-valid @enderror"
                                    placeholder="{{ __('admin.ph_review_comment') }}">{{ old('comment', $review->comment ?? '') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- REVIEW IMAGES --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-image-alt me-2 text-info"></i>{{ __('admin.review_image') }}
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <input type="file" name="image" id="reviewImgInput" class="form-control form-control-lg"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            title="{{ __('admin.upload_review_image_title') }}">
                        <div class="form-text">{!! isset($review) ? '' : '<span class="text-danger">*</span> ' !!}{{ __('admin.jpg_png_webp') }}</div>
                        <div class="mt-3" id="reviewImgWrapper"
                            style="display:{{ isset($review) && $review->image ? 'block' : 'none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="reviewImgPreview"
                                    src="{{ isset($review) && $review->image ? asset('uploads/review/' . $review->image) : '' }}"
                                    class="rounded border img-preview-contain">
                                <button type="button" id="reviewImgRemove"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center img-remove-btn"
                                    title="{{ __('admin.remove') }}">
                                    <i class="bx bx-x icon-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- PUBLISH --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- STATUS TOGGLE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $review->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $review->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $review->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($review) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-lg"><i
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

            $('.select2-product').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_product') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-user').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_user') }}',
                allowClear: true,
                width: '100%'
            });

            // Product image preview function
            function showProductImages(val) {
                const opt = $('#productSelect option[value="' + val + '"]');
                let images = [];
                try {
                    images = JSON.parse(opt.attr('data-images') || '[]');
                } catch (e) {}
                const wrap = document.querySelector('#productImagePreview div');
                const preview = document.getElementById('productImagePreview');
                wrap.innerHTML = '';
                if (images.length) {
                    images.forEach(function(img) {
                        const el = document.createElement('img');
                        el.src = '{{ asset('uploads/products') }}/' + img;
                        el.style =
                            'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;';
                        wrap.appendChild(el);
                    });
                    preview.classList.remove('d-none');
                } else {
                    preview.classList.add('d-none');
                }
            }

            // Trigger image preview on product selection change
            $('.select2-product').on('change', function() {
                showProductImages($(this).val());
            });

            // Show images if product is already selected (edit mode)
            if ($('.select2-product').val()) {
                showProductImages($('.select2-product').val());
            }

            $('#reviewForm').validate({
                rules: {
                    user_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    rating: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 5
                    },
                    comment: {
                        required: true,
                    },
                    image: {
                        @if (!isset($review))
                            required: true,
                        @endif
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    }
                },
                messages: {
                    user_id: {
                        required: "{{ __('admin.val_user_required') }}"
                    },
                    product_id: {
                        required: "{{ __('admin.val_product_required') }}"
                    },
                    title: {
                        required: "{{ __('admin.val_title_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    rating: {
                        required: "{{ __('admin.val_rating_required') }}",
                        number: "{{ __('admin.val_rating_number') }}",
                        min: "{{ __('admin.val_rating_min_1') }}",
                        max: "{{ __('admin.val_rating_max_5') }}"
                    },
                    comment: {
                        required: "{{ __('admin.val_comment_required') }}",
                        maxlength: "{{ __('admin.val_max_1000') }}"
                    },
                    image: {
                        required: "{{ __('admin.val_review_image_required') }}",
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

        // Review image preview
        document.getElementById('reviewImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('reviewImgWrapper');
            const preview = document.getElementById('reviewImgPreview');
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
        document.getElementById('reviewImgRemove').addEventListener('click', function() {
            document.getElementById('reviewImgInput').value = '';
            document.getElementById('reviewImgPreview').src = '';
            document.getElementById('reviewImgWrapper').style.display = 'none';
        });
    </script>
@endpush
