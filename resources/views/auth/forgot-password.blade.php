<!doctype html>
<html lang="{{ app()->getLocale() }}" class="layout-wide customizer-hide">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('admin.forgot_title') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
</head>

<body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <div class="card px-sm-6 px-0">
                    <div class="card-body">

                        <h4 class="mb-1">{{ __('admin.forgot_heading') }}</h4>
                        <p class="mb-6">{{ __('admin.forgot_subtitle') }}</p>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success mb-4">{{ session('status') }}</div>
                        @endif

                        {{-- Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-6">
                                <label for="email" class="form-label">{{ __('admin.email_label') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" required autofocus
                                    placeholder="{{ __('admin.email_placeholder') }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('admin.send_reset_link') }}
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-muted">
                                    <i class="bx bx-chevron-left"></i> {{ __('admin.back_to_login') }}
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
