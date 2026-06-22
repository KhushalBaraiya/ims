@extends('layouts.admin')
@section('title', 'Edit Supplier — ' . $supplier->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Supplier</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i>
            Back</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-group me-2 text-primary"></i>Supplier Details</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                @method('PUT')@include('suppliers.form')</form>
        </div>
    </div>
@endsection
