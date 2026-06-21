@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($blog) ? __('admin.edit_blog') : __('admin.add_blog') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">{{ __('admin.blogs') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($blog) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="blogForm" action="{{ isset($blog) ? route('admin.blog.update', $blog->id) : route('admin.blog.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($blog))
            @method('PUT')
        @endif

        <div class="row g-4">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- BLOG DETAILS -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-detail me-2 text-primary"></i>{{ __('admin.blog_details') }}
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <!-- TITLE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                value="{{ old('title', $blog->title ?? '') }}"
                                placeholder="{{ __('admin.blog_title_placeholder') }}">
                        </div>

                        <!-- SHORT DESCRIPTION -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.short_description') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="short_description" class="form-control form-control-lg" rows="3"
                                placeholder="{{ __('admin.blog_short_desc_placeholder') }}">{{ old('short_description', $blog->short_description ?? '') }}</textarea>
                        </div>

                        <!-- CONTENT -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.content') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="content" class="form-control form-control-lg" rows="8"
                                placeholder="{{ __('admin.blog_content_placeholder') }}">{{ old('content', $blog->content ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- BLOG IMAGE -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-image-alt me-2 text-info"></i>{{ __('admin.blog_image') }}
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <input type="file" name="image" id="blogImgInput" class="form-control form-control-lg"
                            accept="image/jpg,image/jpeg,image/png,image/webp" title="{{ __('admin.upload_blog_image') }}">
                        <div class="form-text">{!! isset($blog) ? '' : '<span class="text-danger">*</span> ' !!}{{ __('admin.jpg_png_webp') }}</div>
                        <div class="mt-3" id="blogImgWrapper"
                            style="display:{{ isset($blog) && $blog->image ? 'block' : 'none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="blogImgPreview"
                                    src="{{ isset($blog) && $blog->image ? asset('uploads/blog/' . $blog->image) : '' }}"
                                    class="rounded border img-preview-contain">
                                <button type="button" id="blogImgRemove"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center img-remove-btn"
                                    title="{{ __('admin.remove') }}">
                                    <i class="bx bx-x icon-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <!-- PUBLISH -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <!-- STATUS TOGGLE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $blog->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6
                                    {{ old('status', $blog->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $blog->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        <!-- AUTHOR -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('admin.author') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="author" class="form-control form-control-lg"
                                value="{{ old('author', $blog->author ?? '') }}"
                                placeholder="{{ __('admin.blog_author_placeholder') }}">
                        </div>

                        <!-- BUTTONS -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($blog) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-lg"><i
                                    class="bx bx-x me-1"></i>{{ __('admin.cancel') }}</a>
                        </div>

                    </div>
                </div>

                <!-- CATEGORY -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-category me-2 text-warning"></i>{{ __('admin.classification') }}
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <label class="form-label fw-semibold">{{ __('admin.category') }} <span
                                class="text-danger">*</span></label>
                        <div class="select2-lg">
                            <select name="blog_category_id" class="select2-category form-select form-select-lg w-100">
                                <option value="">{{ __('admin.select_blog_category') }}</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('blog_category_id', $blog->blog_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
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
            // Initialize Select2 for blog category
            $('.select2-category').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });

            // Custom file size rule
            $.validator.addMethod('filesize', function(value, element, param) {
                return element.files.length === 0 || element.files[0].size <= param;
            }, '{{ __('admin.val_image_size') }}');

            $('#blogForm').validate({
                rules: {
                    blog_category_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    short_description: {
                        required: true,
                        minlength: 10,
                        maxlength: 500
                    },
                    content: {
                        required: true,
                        minlength: 20
                    },
                    author: {
                        required: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    image: {
                        @if (!isset($blog))
                            required: true,
                        @endif
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                        filesize: 2097152
                    }
                },
                messages: {
                    blog_category_id: {
                        required: "{{ __('admin.val_blog_category_required') }}"
                    },
                    title: {
                        required: "{{ __('admin.val_title_required') }}",
                        minlength: "{{ __('admin.val_min_3') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    short_description: {
                        required: "{{ __('admin.val_short_desc_required') }}",
                        minlength: "{{ __('admin.val_min_10') }}",
                        maxlength: "{{ __('admin.val_max_500') }}"
                    },
                    content: {
                        required: "{{ __('admin.val_content_required') }}",
                        minlength: "{{ __('admin.val_min_20') }}"
                    },
                    author: {
                        required: "{{ __('admin.val_author_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}"
                    },
                    image: {
                        required: "{{ __('admin.val_blog_image_required') }}",
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
                    // Select2 visual border
                    if ($(el).hasClass('select2-hidden-accessible')) {
                        $(el).closest('.select2-lg').find('.select2-selection').css('border-color',
                            '#dc3545');
                    }
                },
                unhighlight: function(el) {
                    $(el).removeClass('is-invalid');
                    if ($(el).hasClass('select2-hidden-accessible')) {
                        $(el).closest('.select2-lg').find('.select2-selection').css('border-color', '');
                    }
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
        // Image preview with validation
        document.getElementById('blogImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('blogImgWrapper');
            const preview = document.getElementById('blogImgPreview');
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
        document.getElementById('blogImgRemove').addEventListener('click', function() {
            document.getElementById('blogImgInput').value = '';
            document.getElementById('blogImgPreview').src = '';
            document.getElementById('blogImgWrapper').style.display = 'none';
        });
        // Status toggle label update
        document.getElementById('statusToggle').addEventListener('change', function() {
            const label = document.getElementById('statusLabel');
            if (this.checked) {
                label.textContent = '{{ __('admin.status_active') }}';
                label.className = 'fw-semibold fs-6 text-success';
            } else {
                label.textContent = '{{ __('admin.status_inactive') }}';
                label.className = 'fw-semibold fs-6 text-danger';
            }
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
    </script>
@endpush
