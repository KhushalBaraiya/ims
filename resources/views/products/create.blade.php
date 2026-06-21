@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Product</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add a new electronics hardware or accessory product to the catalog.</p>
    </div>

    <!-- Form Wrapper -->
    <x-card>
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @include('products.form')
        </form>
    </x-card>
@endsection
