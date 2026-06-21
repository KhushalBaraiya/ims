@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Purchase Order</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Record a new supplier purchase, add products, and update inventory stock automatically.</p>
    </div>
    <form method="POST" action="{{ route('purchases.store') }}" novalidate>
        @include('purchases.form')
    </form>
@endsection
