@extends('layouts.app')

@section('title', 'Create Supplier')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Supplier</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add a new electronics and inventory supplier.</p>
    </div>

    <!-- Form Wrapper -->
    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('suppliers.store') }}">
                @include('suppliers.form')
            </form>
        </x-card>
    </div>
@endsection
