@extends('layouts.app')

@section('title', 'Edit Sales Invoice')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Sales Invoice</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify line items, quantities, or statuses of invoice: <span class="font-mono font-bold">{{ $sale->invoice_no }}</span>.</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sales.update', $sale->id) }}" novalidate>
        @method('PUT')
        @include('sales.form')
    </form>
@endsection
