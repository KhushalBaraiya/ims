@extends('layouts.admin')
@section('title', 'Edit Template — ' . $template->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="bx bxl-whatsapp text-success" style="font-size:1.5rem;"></i>
                Edit Template
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('whatsapp.index') }}">WhatsApp</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('whatsapp.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-edit text-primary me-2"></i>Edit: {{ $template->name }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('whatsapp.update', $template->id) }}" method="POST">
                        @csrf @method('PUT')
                        @include('whatsapp.form')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
