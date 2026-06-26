@extends('layouts.admin')
@section('title', __('messages.edit_product') . ' — ' . $product->name)

@push('styles')
    <style>
        .form-section {
            border-radius: 12px;
            border: 1px solid rgba(105, 108, 255, .12);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .form-section-header {
            background: linear-gradient(135deg, #f8f9ff, #f0f1ff);
            padding: .75rem 1.25rem;
            border-bottom: 1px solid rgba(105, 108, 255, .12);
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .form-section-header .sec-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .form-section-header .sec-title {
            font-weight: 700;
            font-size: .9rem;
        }

        .form-section-body {
            padding: 1.25rem;
            background: #fff;
        }

        [data-bs-theme="dark"] .form-section-header {
            background: linear-gradient(135deg, #25264a, #2b2c40);
        }

        [data-bs-theme="dark"] .form-section-body {
            background: #2b2c40;
        }

        [data-bs-theme="dark"] .form-section {
            border-color: rgba(255, 255, 255, .08);
        }

        .img-drop-zone {
            border: 2px dashed rgba(105, 108, 255, .3);
            border-radius: 10px;
            background: rgba(105, 108, 255, .03);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }

        .img-drop-zone:hover {
            border-color: #696cff;
            background: rgba(105, 108, 255, .07);
        }

        .img-preview-box {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid rgba(105, 108, 255, .2);
            background: #f8f9ff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .img-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_product') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('products.show', $product->id) }}">{{ Str::limit($product->name, 30) }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info btn-sm">
                <i class="bx bx-show me-1"></i> View
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Product identity ribbon --}}
    <div class="card shadow-sm border-0 mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
            @if ($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}"
                    class="rounded-circle border border-white border-2 flex-shrink-0"
                    style="width:46px;height:46px;object-fit:cover;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2 flex-shrink-0"
                    style="width:46px;height:46px;background:rgba(255,255,255,.2);">
                    <i class="bx bx-package text-white" style="font-size:1.3rem;"></i>
                </div>
            @endif
            <div>
                <div class="text-white fw-bold">{{ $product->name }}</div>
                <div class="text-white opacity-75 small">SKU: {{ $product->code }} &nbsp;·&nbsp;
                    {{ $product->mainCategory->name ?? 'Uncategorized' }}</div>
            </div>
            <span class="badge bg-white text-primary ms-auto">{{ ucfirst($product->status) }}</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $e)
                    <li class="small">{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('products.form')
    </form>

@endsection
