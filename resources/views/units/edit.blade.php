@extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Unit</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify details of the unit of measurement.</p>
    </div>

    <!-- Form Wrapper -->
    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('units.update', $unit->id) }}">
                @method('PUT')
                @include('units.form')
            </form>
        </x-card>
    </div>
@endsection
