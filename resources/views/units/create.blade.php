@extends('layouts.app')

@section('title', 'Create Unit')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Unit</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add a new unit of measurement for inventory products.</p>
    </div>

    <!-- Form Wrapper -->
    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('units.store') }}">
                @include('units.form')
            </form>
        </x-card>
    </div>
@endsection
