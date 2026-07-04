@extends('layouts.admin')

@section('title', 'Edit Sales Invoice')

@section('content')
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4">Edit Sales Invoice</h2>
        <p class="text-muted small">Modify line items, quantities, or statuses of invoice: <code>{{ $sale->invoice_no }}</code>.</p>
    </div>

    @if ($sale->returns->isNotEmpty())
        <div class="alert alert-warning border border-warning d-flex align-items-center gap-2 mb-4">
            <i class="bx bx-error-circle fs-4 text-warning"></i>
            <div>
                This sale has already been returned and cannot be edited.
            </div>
        </div>
    @endif

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sales.update', $sale->id) }}" novalidate>
        @method('PUT')
        @include('sales.form')
    </form>
@endsection
