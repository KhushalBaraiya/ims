@extends('layouts.app')

@section('title', 'Create Brand')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-slate-900 tracking-tight">Create Brand</h2>
        <p class="mt-1 text-sm text-slate-500">Define a new electronics manufacturer/brand.</p>
    </div>

    <!-- Card Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-4xl">
        <form method="POST" action="{{ route('brands.store') }}">
            @include('brands.form')
        </form>
    </div>
@endsection
