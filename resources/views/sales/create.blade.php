@extends('layouts.app')

@section('title', 'Create Sales Invoice')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Sales Invoice</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Generate a new transaction bill, allocate items, and manage customer payments.</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sales.store') }}" novalidate>
        @include('sales.form')
    </form>
@endsection
