@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ isset($subInCategory) ? __('admin.edit_sub_in_category') : __('admin.add_sub_in_category') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.subincategory.index') }}">{{ __('admin.sub_in_category_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($subInCategory) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.subincategory.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="subInCategoryForm"
        action="{{ isset($subInCategory) ? route('admin.subincategory.update', $subInCategory->id) : route('admin.subincategory.store') }}"
        method="POST">
        @csrf
        @if (isset($subInCategory))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-category me-2 text-primary"></i>{{ __('admin.sub_in_category_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.main_category_label') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="category_id" id="mainCategorySelect"
                                    class="select2-category form-select form-select-lg w-100">
                                    <option value="">{{ __('admin.select_main_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $subInCategory->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.sub_category_label') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="subcategory_id" id="subCategorySelect"
                                    class="select2-subcategory form-select form-select-lg w-100">
                                    <option value="">{{ __('admin.select_sub_category') }}</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ old('subcategory_id', $subInCategory->subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.sub_in_category_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                placeholder="{{ __('admin.ph_subincategory_name') }}"
                                value="{{ old('name', $subInCategory->name ?? '') }}">
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
                                        {{ old('status', $subInCategory->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $subInCategory->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $subInCategory->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($subInCategory) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.subincategory.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            // Initialize Select2 for category and subcategory
            $('.select2-category, .select2-subcategory').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });

            // Dynamic subcategory load
            const selectedSubId = '{{ old('subcategory_id', $subInCategory->subcategory_id ?? '') }}';

            function loadSubcategories(categoryId, selectedId) {
                const $sub = $('#subCategorySelect');
                $sub.html('<option value="">{{ __('admin.select_option') }}</option>').prop('disabled', true);
                if (!categoryId) {
                    $sub.html('<option value="">{{ __('admin.select_sub_category') }}</option>').prop('disabled',
                        false);
                    return;
                }
                $.get('/admin/subcategories-by-category/' + categoryId, function(data) {
                    let opts = '<option value="">{{ __('admin.select_sub_category') }}</option>';
                    $.each(data, function(i, sub) {
                        const sel = (sub.id == selectedId) ? 'selected' : '';
                        opts += `<option value="${sub.id}" ${sel}>${sub.name}</option>`;
                    });
                    $sub.html(opts).prop('disabled', false);
                });
            }

            // On page load — if category already selected (edit mode)
            const initCat = $('#mainCategorySelect').val();
            if (initCat) loadSubcategories(initCat, selectedSubId);

            // On change
            $('#mainCategorySelect').on('change', function() {
                loadSubcategories($(this).val(), '');
            });

            $('#subInCategoryForm').validate({
                rules: {
                    category_id: {
                        required: true
                    },
                    subcategory_id: {
                        required: true
                    },
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    }
                },
                messages: {
                    category_id: {
                        required: "{{ __('admin.val_main_category_required') }}"
                    },
                    subcategory_id: {
                        required: "{{ __('admin.val_sub_category_required') }}"
                    },
                    name: {
                        required: "{{ __('admin.val_name_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}"
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
    </script>
@endpush
