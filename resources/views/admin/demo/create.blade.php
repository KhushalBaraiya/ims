<!-- resources/views/demo/create.blade.php -->

<!DOCTYPE html>
<html>
<head>
    {{-- {{ __('admin.dashboard') }} --}}
    <title>
        {{ isset($demo) ? __('admin.edit_demo') : __('admin.create_demo') }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                {{ isset($demo) ? __('admin.edit_demo') : __('admin.create_demo') }}
            </h2>
            <a href="{{ route('admin.demo.index') }}" class="btn btn-dark">
                {{ __('admin.back') }}
            </a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ isset($demo) ? route('admin.demo.update', $demo->id) : route('admin.demo.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($demo))
                @method('PUT')
            @endif
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.name') }}</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $demo->name ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.email') }}</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $demo->email ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                {{ __('admin.password') }}
                            </label>
                            <input type="password" name="password" class="form-control">
                            @if (isset($demo))
                                <small class="text-muted">
                                    {{ __('admin.demo_password_blank') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.phone') }}</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $demo->phone ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.image') }}</label>
                            <input type="file" name="image" class="form-control">
                            @if (isset($demo) && $demo->image)
                                <img src="{{ asset('uploads/demo/' . $demo->image) }}" width="80"
                                    class="mt-2 rounded">
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.gender') }}</label>
                            <select name="gender" class="form-control">
                                <option value="">{{ __('admin.select_gender') }}</option>
                                <option value="male"
                                    {{ old('gender', $demo->gender ?? '') == 'male' ? 'selected' : '' }}>
                                    {{ __('admin.gender_male') }}
                                </option>
                                <option value="female"
                                    {{ old('gender', $demo->gender ?? '') == 'female' ? 'selected' : '' }}>
                                    {{ __('admin.gender_female') }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('admin.address') }}</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $demo->address ?? '') }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $demo->status ?? '') == 'active' ? 'selected' : '' }}>
                                    {{ __('admin.active') }}
                                </option>
                                <option value="inactive"
                                    {{ old('status', $demo->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    {{ __('admin.inactive') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        {{ isset($demo) ? __('admin.update') : __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

