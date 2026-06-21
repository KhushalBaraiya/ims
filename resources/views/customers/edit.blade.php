@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Customer</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify the settings and properties of the customer.</p>
    </div>

    <!-- Form Wrapper -->
    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                @method('PUT')
                @include('customers.form')
            </form>
        </x-card>
    </div>
@endsection
