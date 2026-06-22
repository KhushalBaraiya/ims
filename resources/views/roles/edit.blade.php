@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Edit Role</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Modify the name and permissions assigned to this role.
        </p>
    </div>

    <!-- Form Card -->
    <x-card>
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @method('PUT')
            @include('roles.form')
        </form>
    </x-card>

@endsection

@push('scripts')
    @include('roles.scripts')
@endpush
