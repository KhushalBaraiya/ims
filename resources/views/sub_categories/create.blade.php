@extends('layouts.admin')
@section('title', 'Add Sub Category')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add Sub Category</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sub-categories.index') }}">Sub Categories</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i>
            Back</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-category me-2 text-primary"></i>Sub Category Details</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('sub-categories.store') }}">@include('sub_categories.form')</form>
        </div>
    </div>
@endsection
