@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-slate-900 tracking-tight">Edit Category</h2>
        <p class="mt-1 text-sm text-slate-500">Modify the settings and properties of the category.</p>
    </div>

    <!-- Card Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-4xl">
        <form method="POST" action="{{ route('main-categories.update', $mainCategory->id) }}">
            @method('PUT')
            @include('main_categories.form')
        </form>
    </div>
@endsection
