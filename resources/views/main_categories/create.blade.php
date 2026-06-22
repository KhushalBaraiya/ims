@extends('layouts.admin')
@section('title', 'Add Main Category')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add Main Category</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('main-categories.index') }}">Main Categories</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('main-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-category me-2 text-primary"></i>Category Details</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('main-categories.store') }}">
                @include('main_categories.form')
            </form>
        </div>
    </div>

@endsection
