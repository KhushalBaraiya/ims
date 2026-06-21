<!doctype html>
<html lang="{{ app()->getLocale() }}" class="layout-wide customizer-hide">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('admin.reset_title') }}</title>

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

                        <h4 class="mb-1">{{ __('admin.reset_heading') }}</h4>
                        <p class="mb-6">{{ __('admin.reset_subtitle') }}</p>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('admin.email_label') }}</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $request->email) }}"
                                    class="form-control @error('email') is-invalid @enderror" required autofocus
                                    placeholder="{{ __('admin.email_placeholder') }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('admin.new_password') }}</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" required
                                        placeholder="{{ __('admin.new_password_ph') }}" />
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password', this)">
                                        <i class="bx bx-hide"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('admin.confirm_password') }}</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" required
                                        placeholder="{{ __('admin.confirm_password_ph') }}" />
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password_confirmation', this)">
                                        <i class="bx bx-hide"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('admin.reset_btn') }}
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
    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon.classList.replace('bx-show', 'bx-hide');
            }
        }
    </script>
</body>

</html>
