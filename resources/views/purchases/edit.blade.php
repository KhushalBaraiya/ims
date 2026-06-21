@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Purchase Order</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Update the purchase details, modify quantities, and re-synchronize inventory stock.</p>
    </div>
    <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" novalidate>
        @method('PUT')
        @include('purchases.form')
    </form>
@endsection
