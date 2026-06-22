@extends('layouts.admin')

@section('title', 'Edit Purchase Order')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4">Edit Purchase Order</h2>
        <p class="text-muted small">Update the purchase details, modify quantities, and re-synchronize inventory stock.</p>
    </div>
    <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" novalidate>
        @method('PUT')
        @include('purchases.form')
    </form>
@endsection
