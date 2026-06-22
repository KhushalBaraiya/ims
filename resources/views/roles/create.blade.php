.@extends('layouts.app')

@section('title', 'Create Role')

@section('content')

    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Create Role</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Define a new role and assign the permissions it should
            carry.</p>
    </div>

    <!-- Form Card -->
    <x-card>
        <form method="POST" action="{{ route('roles.store') }}">
            @include('roles.form')
        </form>
    </x-card>

@endsection

@push('scripts')
    @include('roles.scripts')
@endpush
