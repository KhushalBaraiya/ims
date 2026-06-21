<!doctype html>
<html lang="{{ app()->getLocale() }}" class="layout-wide customizer-hide">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('admin.login_title') }}</title>

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

                        <h4 class="mb-1">{{ __('admin.login_welcome') }}</h4>
                        <p class="mb-6">{{ __('admin.login_subtitle') }}</p>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        {{-- General Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="form-label">{{ __('admin.email_label') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" required autofocus
                                    autocomplete="username" placeholder="{{ __('admin.email_placeholder') }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="password" class="form-label">{{ __('admin.password_label') }}</label>
                                <div style="position:relative; display:flex; align-items:center;">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" required
                                        autocomplete="current-password" placeholder="············"
                                        style="padding-right: 2.5rem;" />
                                    <button type="button" id="togglePassword"
                                        style="position:absolute; right:10px; background:none; border:none; cursor:pointer; padding:0; line-height:1; z-index:10;">
                                        <svg id="eyeOff" xmlns="http://www.w3.org/2000/svg" width="20"
                                            height="20" viewBox="0 0 24 24" fill="none" stroke="#888"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                                            <path
                                                d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                                            <line x1="1" y1="1" x2="23" y2="23" />
                                        </svg>
                                        <svg id="eyeOn" xmlns="http://www.w3.org/2000/svg" width="20"
                                            height="20" viewBox="0 0 24 24" fill="none" stroke="#888"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="display:none;">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-6 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                    <label class="form-check-label"
                                        for="remember_me">{{ __('admin.remember_me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">{{ __('admin.forgot_password') }}</a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <div class="mb-6">
                                <button type="submit"
                                    class="btn btn-primary w-100">{{ __('admin.login_btn') }}</button>
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
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var input = document.getElementById('password');
            var eyeOff = document.getElementById('eyeOff');
            var eyeOn = document.getElementById('eyeOn');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOff.style.display = 'none';
                eyeOn.style.display = 'inline';
            } else {
                input.type = 'password';
                eyeOff.style.display = 'inline';
                eyeOn.style.display = 'none';
            }
        });
    </script>

</body>

</html>
