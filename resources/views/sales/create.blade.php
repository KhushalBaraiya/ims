@extends('layouts.admin')

@section('title', __('messages.create_sales_invoice'))

@section('content')
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4">{{ __('messages.create_sales_invoice') }}</h2>
        <p class="text-muted small">{{ __('messages.store_desc') }}</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sales.store') }}" novalidate>
        @include('sales.form')
    </form>
@endsection
