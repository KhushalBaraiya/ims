@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Edit User</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Modify settings and role parameters for user: {{ $user->name }}.</p>
    </div>

    <!-- Form Card -->
    <x-card title="Account Settings" subtitle="Modify properties. Password is optional.">
        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('users.form')
        </form>
    </x-card>
@endsection
