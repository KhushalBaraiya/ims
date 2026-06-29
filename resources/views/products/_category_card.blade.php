@php
    $qty = (float) ($product->stock->quantity ?? 0);
    $alert = (float) ($product->minimum_stock_alert ?? 0);
    $isOut = $qty <= 0;
    $isLow = !$isOut && $qty <= $alert;
    $inactive = $product->status !== 'active';
    $profit = $product->selling_price - $product->purchase_price;
    $pct = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;

    $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
    $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
    $sLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');
@endphp

<div class="col pc-item" data-subcat="{{ $product->sub_category_id ?? '' }}">
    <div class="card pc-card shadow-sm {{ $inactive ? 'opacity-75' : '' }}"
        onclick="window.location='{{ route('products.show', $product->id) }}'">

        {{-- Image --}}
        <div class="pc-img-wrap">
            @if ($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy"
                    onerror="this.parentElement.innerHTML='<div class=\'pc-no-img\'><i class=\'bx bx-package\'></i><span>No Image</span></div>'">
            @else
                <div class="pc-no-img">
                    <i class="bx bx-package"></i>
                    <span>No Image</span>
                </div>
            @endif

            {{-- Stock badge top-left --}}
            <div class="pc-tl">
                <span class="badge {{ $sBadge }}" style="font-size:.6rem;">{{ $sLabel }}</span>
            </div>

            {{-- Inactive badge top-right --}}
            @if ($inactive)
                <div class="pc-tr">
                    <span class="badge bg-secondary" style="font-size:.58rem;">Inactive</span>
                </div>
            @endif
        </div>

        {{-- Card Body --}}
        <div class="pc-body">
            {{-- Sub-category chip --}}
            <div class="mb-1" style="min-height:16px;">
                @if ($product->subCategory)
                    <span class="badge bg-label-secondary" style="font-size:.55rem;padding:.18em .42em;">
                        {{ $product->subCategory->name }}
                    </span>
                @endif
                @if ($product->brand)
                    <span class="badge bg-label-primary" style="font-size:.55rem;padding:.18em .42em;">
                        {{ $product->brand->name }}
                    </span>
                @endif
            </div>

            {{-- Name --}}
            <h6 class="fw-bold mb-0 lh-sm" title="{{ $product->name }}"
                style="font-size:.8rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $product->name }}
            </h6>
            <p class="text-muted mb-2" style="font-size:.65rem;">
                <code style="font-size:.65rem;">{{ $product->code }}</code>
            </p>

            {{-- Price box --}}
            <div class="pc-price-box">
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <div
                            style="font-size:.57rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                            Sell
                        </div>
                        <div class="fw-bold text-primary lh-1" style="font-size:.92rem;">
                            {{ format_currency($product->selling_price) }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div
                            style="font-size:.57rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                            Cost
                        </div>
                        <div class="text-muted fw-semibold lh-1" style="font-size:.73rem;">
                            {{ format_currency($product->purchase_price) }}
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1 pt-1"
                    style="border-top:1px solid rgba(0,0,0,.07);">
                    <span style="font-size:.57rem;color:#bbb;font-weight:600;">Profit</span>
                    <span class="fw-bold {{ $pct >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.67rem;">
                        {{ format_currency($profit) }}
                        <span class="badge {{ $pct >= 0 ? 'bg-success' : 'bg-danger' }}"
                            style="font-size:.52rem;padding:.14em .36em;">
                            {{ $pct >= 0 ? '+' : '' }}{{ $pct }}%
                        </span>
                    </span>
                </div>
            </div>

            {{-- Stock row --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-1">
                    <i class="bx bx-cube {{ $sCls }}" style="font-size:.82rem;"></i>
                    <span class="fw-bold {{ $sCls }}" style="font-size:.72rem;">
                        {{ number_format($qty, 0) }} {{ $product->unit_code ?? 'pcs' }}
                    </span>
                </div>
                @if ($product->tax_percentage)
                    <span class="badge bg-label-warning" style="font-size:.54rem;padding:.16em .4em;">
                        GST {{ $product->tax_percentage }}%
                    </span>
                @endif
            </div>

            {{-- Action buttons --}}
            <div class="d-flex gap-1 mt-auto" onclick="event.stopPropagation()">
                @can('products.view')
                    <a href="{{ route('products.show', $product->id) }}"
                        class="btn btn-sm btn-outline-info rounded-pill flex-fill py-1" style="font-size:.67rem;">
                        <i class="bx bx-show me-1"></i>View
                    </a>
                @endcan
                @can('products.update')
                    <a href="{{ route('products.edit', $product->id) }}"
                        class="btn btn-sm btn-outline-primary rounded-pill flex-fill py-1" style="font-size:.67rem;">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
