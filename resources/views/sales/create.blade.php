@extends('layouts.admin')

@section('title', 'Create Sales Invoice')

@section('content')
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4">Create Sales Invoice</h2>
        <p class="text-muted small">Generate a new transaction bill, allocate items, and manage customer payments.</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sales.store') }}" novalidate>
        @include('sales.form')
    </form>
@endsection
