@extends('layouts.admin')
@section('title', 'Add Customer')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add Customer</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i>
            Back</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-user-circle me-2 text-primary"></i>Customer Details</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('customers.store') }}">@include('customers.form')</form>
        </div>
    </div>
@endsection
