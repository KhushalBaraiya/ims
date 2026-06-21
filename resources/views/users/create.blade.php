@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Create User</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Register a new user account with Spatie role assignments.</p>
    </div>

    <!-- Form Card -->
    <x-card title="Account Details" subtitle="Provide the credentials and details for the new user.">
        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @include('users.form')
        </form>
    </x-card>
@endsection
