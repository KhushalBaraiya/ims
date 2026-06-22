@extends('layouts.admin')

@section('title', 'Create Purchase Order')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4">Create Purchase Order</h2>
        <p class="text-muted small">Record a new supplier purchase, add products, and update inventory stock automatically.</p>
    </div>
    <form method="POST" action="{{ route('purchases.store') }}" novalidate>
        @include('purchases.form')
    </form>
@endsection
