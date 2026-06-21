@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Product</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify information and specifications of the electronics hardware.</p>
    </div>

    <!-- Form Wrapper -->
    <x-card>
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('products.form')
        </form>
    </x-card>
@endsection
