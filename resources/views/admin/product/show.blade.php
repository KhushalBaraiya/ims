@extends('layouts.admin')
@section('title', 'Product — ' . $product->name)
@section('content')

    @php
        $colors = $product->colors;
        $sizes = $product->sizes;
        $detailRows = $product->attributes->where('attribute_key', 'Detail');
        $variations = $product->attributes->where('attribute_key', 'Variation');
        $hasClothing =
            $product->fabric ||
            $product->age_group ||
            $product->care_instructions ||
            $product->clothing_features ||
            $product->neckline ||
            $product->sleeve_length_type;
    @endphp

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.products') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.product.index') }}">{{ __('admin.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.product.attributes.edit', $product->id) }}" class="btn btn-warning btn-lg">
                <i class="bx bx-tag-alt me-1"></i>{{ __('admin.product_attributes') }}
            </a>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-lg">
                <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ════ LEFT col-lg-8 ════ --}}
        <div class="col-lg-8">

            {{-- Hero Card: image + name + badges --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-4">
                        {{-- Main image --}}
                        @if (!empty($product->images) && count($product->images))
                            <img src="{{ asset('uploads/products/' . $product->images[0]) }}" id="mainProductImg"
                                class="rounded-3 shadow-sm flex-shrink-0" style="width:110px;height:110px;object-fit:cover;"
                                onerror="imgError(this)">
                        @else
                            <div class="rounded-3 d-flex align-items-center justify-content-center bg-label-secondary flex-shrink-0"
                                style="width:110px;height:110px;">
                                <i class="bx bx-package text-secondary" style="font-size:3rem;"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0">{{ $product->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                    {{ $product->status }}
                                </span>
                                @if ($product->is_favourite)
                                    <span class="badge bg-danger px-2 py-1"><i
                                            class="bx bxs-heart me-1"></i>{{ __('admin.yes') }}</span>
                                @endif
                            </div>
                            @if ($product->sku)
                                <p class="text-muted mb-1 small"><i class="bx bx-barcode me-1"></i>SKU: {{ $product->sku }}
                                </p>
                            @endif
                            @if ($product->slug)
                                <p class="text-muted mb-1 small"><i class="bx bx-link me-1"></i>{{ $product->slug }}</p>
                            @endif
                            <p class="text-muted mb-0 small">
                                <i class="bx bx-category me-1"></i>{{ $product->category->name ?? '—' }}
                                @if ($product->subcategory)
                                    › {{ $product->subcategory->name }}
                                @endif
                                @if ($product->subInCategory)
                                    › {{ $product->subInCategory->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Pricing stat boxes --}}
                    <div class="row g-3 mt-3">
                        <div class="col-6 col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f8f9fa;">
                                <div class="fw-bold fs-5">₹{{ number_format($product->original_price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.mrp') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-5 text-success">₹{{ number_format($product->price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.selling_price') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-5 text-warning">{{ $product->discount_percent }}%</div>
                                <div class="text-muted small">{{ __('admin.discount') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f9ff;">
                                <div class="fw-bold fs-5 text-primary">{{ $product->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.quantity') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Images Gallery --}}
            @if (!empty($product->images) && count($product->images) > 1)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-images me-2 text-info"></i>{{ __('admin.images') }}
                            <span class="badge bg-label-info ms-2">{{ count($product->images) }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($product->images as $idx => $img)
                                <img src="{{ asset('uploads/products/' . $img) }}"
                                    onclick="document.getElementById('mainProductImg').src=this.src"
                                    class="rounded-3 shadow-sm"
                                    style="width:80px;height:80px;object-fit:cover;cursor:pointer;border:2px solid #dee2e6;transition:border-color .2s;"
                                    onmouseover="this.style.borderColor='#696cff'"
                                    onmouseout="this.style.borderColor='#dee2e6'" onerror="imgError(this)">
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Description / Details --}}
            @if ($product->description || $product->product_details)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-detail me-2 text-primary"></i>{{ __('admin.details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if ($product->description)
                            <div class="mb-3">
                                <div class="text-muted small fw-semibold mb-1">{{ __('admin.description') }}</div>
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        @endif
                        @if ($product->product_details)
                            @if ($product->description)
                                <hr>
                            @endif
                            <div>
                                <div class="text-muted small fw-semibold mb-1">{{ __('admin.product_details_label') }}
                                </div>
                                <div class="small lh-lg">{!! nl2br(e($product->product_details)) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Clothing Details --}}
            @if ($hasClothing)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-list-ul me-2 text-warning"></i>Clothing Details
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach ([
            'fabric' => 'Fabric',
            'age_group' => 'Age Group',
            'care_instructions' => 'Care Instructions',
            'clothing_features' => 'Features',
            'neckline' => 'Neckline',
            'sleeve_length_type' => 'Sleeve Length',
        ] as $field => $label)
                                @if ($product->$field)
                                    <div class="col-sm-6">
                                        <div class="p-3 rounded-3 bg-light">
                                            <div class="text-muted small mb-1">{{ $label }}</div>
                                            <div class="fw-bold">{{ $product->$field }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Colors --}}
            @if ($colors->count())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-palette me-2 text-info"></i>{{ __('admin.color') }}
                            <span class="badge bg-label-info ms-2">{{ $colors->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach ($colors as $colorAttr)
                                @php
                                    $cv = is_array($colorAttr->attribute_value) ? $colorAttr->attribute_value : [];
                                    $colorName = $cv['color_name'] ?? ($cv['name'] ?? 'Color #' . $loop->iteration);
                                    $colorCode = $cv['color_code'] ?? ($cv['hex'] ?? '#cccccc');
                                    $colorQty = $cv['quantity'] ?? null;
                                    $colorImgs = $cv['images'] ?? [];
                                @endphp
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="rounded flex-shrink-0 mt-1"
                                            style="width:32px;height:32px;background:{{ $colorCode }};border:2px solid #dee2e6;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="fw-semibold">{{ $colorName }}</span>
                                                @if ($colorQty !== null)
                                                    <span class="badge bg-label-secondary">{{ $colorQty }} qty</span>
                                                @endif
                                            </div>
                                            @if (!empty($colorImgs) && is_array($colorImgs))
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($colorImgs as $cImg)
                                                        <img src="{{ asset('uploads/product-colors/' . $cImg) }}"
                                                            class="rounded-2"
                                                            style="width:60px;height:60px;object-fit:cover;border:1px solid #dee2e6;cursor:pointer;"
                                                            onclick="document.getElementById('mainProductImg').src=this.src"
                                                            onerror="imgError(this)">
                                                    @endforeach
                                                </div>
                                            @else
                                                <small class="text-muted">{{ __('admin.no_images_for_color') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Sizes --}}
            @if ($sizes->count())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-ruler me-2 text-primary"></i>{{ __('admin.sizes') }}
                            <span class="badge bg-label-primary ms-2">{{ $sizes->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($sizes as $sizeAttr)
                                @php
                                    $sv = is_array($sizeAttr->attribute_value) ? $sizeAttr->attribute_value : [];
                                    $sLabel = $sv['size_value'] ?? ($sv['size'] ?? ($sv['name'] ?? '?'));
                                    $sQty = $sv['quantity'] ?? ($sv['stock'] ?? null);
                                    $sGender = $sv['gender'] ?? null;
                                @endphp
                                <div class="text-center">
                                    <div class="border rounded-2 px-3 py-2 fw-bold"
                                        style="min-width:52px;background:#f8f9fa;">
                                        {{ $sLabel }}
                                    </div>
                                    @if ($sQty !== null)
                                        <div class="text-muted" style="font-size:.72rem;">{{ $sQty }} left</div>
                                    @endif
                                    @if ($sGender)
                                        <div class="text-muted" style="font-size:.7rem;">{{ $sGender }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Extra Attributes (Detail / Variation) --}}
            @if ($detailRows->count() || $variations->count())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-tag-alt me-2 text-success"></i>{{ __('admin.attribute') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if ($detailRows->count())
                            <div class="mb-4">
                                <div class="text-muted small fw-semibold mb-2">Additional Details</div>
                                <div class="row g-2">
                                    @foreach ($detailRows as $d)
                                        @php $dv = is_array($d->attribute_value) ? $d->attribute_value : []; @endphp
                                        @if (!empty($dv['key']) || !empty($dv['value']))
                                            <div class="col-sm-6">
                                                <div class="p-3 rounded-3 bg-light">
                                                    <div class="text-muted small mb-1">{{ $dv['key'] ?? '—' }}</div>
                                                    <div class="fw-bold">{{ $dv['value'] ?? '—' }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if ($variations->count())
                            <div>
                                <div class="text-muted small fw-semibold mb-2">Variations</div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($variations as $var)
                                                @php $vv = is_array($var->attribute_value) ? $var->attribute_value : []; @endphp
                                                <tr>
                                                    <td><strong>{{ $vv['variation_name'] ?? '—' }}</strong></td>
                                                    <td>{{ !empty($vv['variation_price']) ? '₹' . number_format($vv['variation_price'], 0) : '—' }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($vv['image']))
                                                            <img src="{{ asset('uploads/product-variations/' . $vv['image']) }}"
                                                                class="tbl-img" onerror="imgError(this)">
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-star me-2 text-warning"></i>{{ __('admin.reviews') }}
                        <span class="badge bg-label-warning ms-2">{{ $product->reviews->count() }}</span>
                    </h6>
                    @if ($product->reviews->count())
                        <a href="{{ route('admin.reviews.index') }}?product_id={{ $product->id }}"
                            class="btn btn-sm btn-outline-warning">{{ __('admin.view_all') }}</a>
                    @endif
                </div>
                <div class="card-body p-4">
                    @forelse($product->reviews->take(5) as $review)
                        <div class="d-flex align-items-start gap-3 {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                            @if ($review->user && $review->user->image)
                                <img src="{{ asset($review->user->image) }}" class="rounded-circle flex-shrink-0"
                                    style="width:42px;height:42px;object-fit:cover;" onerror="imgError(this)">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                    style="width:42px;height:42px;font-size:1rem;font-weight:700;color:#696cff;">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="fw-semibold">{{ $review->user->name ?? __('admin.na') }}</span>
                                    <span
                                        class="badge rounded-pill {{ $review->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $review->status }}</span>
                                    <div class="d-flex align-items-center gap-1 ms-auto">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="bx {{ $s <= $review->rating ? 'bxs-star text-warning' : 'bx-star text-muted' }}"
                                                style="font-size:.9rem;"></i>
                                        @endfor
                                        <small class="text-muted ms-1">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                                @if ($review->title)
                                    <div class="fw-semibold small mb-1">{{ $review->title }}</div>
                                @endif
                                @if ($review->comment)
                                    <p class="text-muted small mb-2">{{ $review->comment }}</p>
                                @endif
                                @if (!empty($review->images) && is_array($review->images))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($review->images as $rImg)
                                            <img src="{{ asset('uploads/reviews/' . $rImg) }}" class="rounded-3"
                                                style="width:60px;height:60px;object-fit:cover;" onerror="imgError(this)">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-comment-x" style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2">{{ __('admin.no_reviews') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>{{-- /col-lg-8 --}}

        {{-- ════ RIGHT col-lg-4 ════ --}}
        <div class="col-lg-4">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $product->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.status') }}</span>
                            <span
                                class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->status }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.brand') }}</span>
                            <span class="small">{{ $product->brand->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.category_col') }}</span>
                            <span class="small">{{ $product->category->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.sub_category_col') }}</span>
                            <span class="small">{{ $product->subcategory->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.rating') }}</span>
                            <span class="fw-bold">⭐ {{ $product->rating }}/5</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.is_favourite') }}</span>
                            @if ($product->is_favourite)
                                <span class="text-danger fw-semibold"><i
                                        class="bx bxs-heart me-1"></i>{{ __('admin.yes') }}</span>
                            @else
                                <span class="text-muted small"><i
                                        class="bx bx-heart me-1"></i>{{ __('admin.no') }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.images') }}</span>
                            <span class="badge bg-label-secondary">{{ count($product->images ?? []) }}
                                {{ __('admin.photos') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.reviews') }}</span>
                            <span class="badge bg-label-warning">{{ $product->reviews->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.created') }}</span>
                            <span class="small">{{ $product->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('admin.updated') }}</span>
                            <span class="small">{{ $product->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.product.attributes.edit', $product->id) }}" class="btn btn-warning btn-lg">
                        <i class="bx bx-tag-alt me-1"></i>{{ __('admin.product_attributes') }}
                    </a>
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
                    </a>
                    <a href="{{ route('admin.reviews.index') }}?product_id={{ $product->id }}"
                        class="btn btn-outline-info btn-lg">
                        <i class="bx bx-star me-1"></i>{{ __('admin.reviews') }}
                    </a>
                    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                        class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete">
                            <i class="bx bx-trash me-1"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>{{-- /col-lg-4 --}}
    </div>{{-- /row --}}

@endsection

@push('scripts')
    <script>
        $(document).on('click', '.btn-delete', function() {
            const form = $(this).closest('.delete-form');
            Swal.fire({
                title: '{{ __('admin.swal_are_you_sure') }}',
                text: '{{ __('admin.swal_delete_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('admin.swal_yes_delete') }}',
                cancelButtonText: '{{ __('admin.swal_cancel') }}'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        });
    </script>
@endpush
