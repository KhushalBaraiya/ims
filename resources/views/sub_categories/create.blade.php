@extends('layouts.app')

@section('title', 'Create Sub Category')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Sub Category</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add a new product subcategory under a main category.</p>
    </div>

    <!-- Form Wrapper -->
    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('sub-categories.store') }}">
                @include('sub_categories.form')
            </form>
        </x-card>
    </div>
@endsection
