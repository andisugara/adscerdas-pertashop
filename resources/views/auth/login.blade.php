<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Pertashop Management</title>
        <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body id="kt_body" class="app-blank">
        <div class="d-flex flex-column flex-root" id="kt_app_root">
            <div class="d-flex flex-column flex-lg-row flex-column-fluid">
                <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                        <div class="w-lg-500px p-10">
                            <form class="form w-100" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="text-center mb-11">
                                    <h1 class="text-gray-900 fw-bolder mb-3">Login</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Sistem Manajemen Pertashop</div>
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

                                <div class="fv-row mb-8">
                                    <input type="email" placeholder="Email" name="email" autocomplete="off"
                                        class="form-control bg-transparent @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required autofocus />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="fv-row mb-3">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent @error('password') is-invalid @enderror"
                                        required />
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid mb-10">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Login</span>
                                    </button>
                                </div>

                                <div class="text-gray-500 text-center fw-semibold fs-6">
                                    <p class="mb-1"><strong>Demo Accounts:</strong></p>
                                    <p class="mb-1">Owner: owner@pertashop.com / password</p>
                                    <p class="mb-0">Operator: operator1@pertashop.com / password</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                    style="background-image: url({{ asset('assets/media/misc/auth-bg.png') }})">
                    <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                        <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">
                            Pertashop Management System
                        </h1>
                        <div class="d-none d-lg-block text-white fs-base text-center">
                            Sistem manajemen pengeluaran dan pemasukan pertashop<br>
                            dengan fitur laporan harian, bulanan, dan tahunan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    </body>

</html>
