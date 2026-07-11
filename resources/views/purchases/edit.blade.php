@extends('layouts.admin')
@section('title', 'Edit Purchase Order — ' . $purchase->purchase_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Edit Purchase Order</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchases.index') }}">{{ __('messages.purchase_orders') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchases.show', $purchase->id) }}">{{ $purchase->purchase_no }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-outline-secondary">
                <i class="bx bx-show me-1"></i> View
            </a>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    @if ($purchase->returns->isNotEmpty())
        <div class="alert alert-warning border border-warning d-flex align-items-center gap-2 mb-4">
            <i class="bx bx-error-circle fs-4 text-warning"></i>
            <div>
                <strong>{{ __('messages.notice') }}:</strong> {{ __('messages.already_returned') }}
            </div>
        </div>
    @endif

    {{-- Identity ribbon --}}
    <div class="card shadow-sm border-0 mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2 flex-shrink-0"
                style="width:42px;height:42px;background:rgba(255,255,255,.2);">
                <i class="bx bx-cart text-white" style="font-size:1.2rem;"></i>
            </div>
            <div>
                <div class="text-white fw-bold">{{ $purchase->purchase_no }}</div>
                <div class="text-white opacity-75 small">
                    {{ $purchase->supplier->name ?? '-' }} &nbsp;·&nbsp; {{ $purchase->purchase_date }}
                </div>
            </div>
            <span
                class="badge bg-white ms-auto
                {{ $purchase->status === 'received' ? 'text-success' : ($purchase->status === 'pending' ? 'text-warning' : ($purchase->status === 'ordered' ? 'text-primary' : 'text-secondary')) }}">
                {{ ucfirst($purchase->status) }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" novalidate>
        @method('PUT')
        @include('purchases.form')
    </form>

@endsection

