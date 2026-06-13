<!DOCTYPE html>
<html lang="en" data-topbar-color="dark">

    <head>
        <meta charset="utf-8" />
        <title>Log In | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets') }}/images/logo-dark-3.png">

		<!-- Theme Config Js -->
		<script src="{{ asset('assets') }}/js/head.js"></script>

		<!-- Bootstrap css -->
		<link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="app-style" />

		<!-- App css -->
		<link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />

		<!-- Icons css -->
		<link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="auth-fluid-pages pb-0">

        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="p-3">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-start">
                            <div class="auth-brand">
                                <a href="{{ url('login') }}" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets') }}/images/logo-dark-3.png" width="150px" height="auto" alt="" >
                                    </span>
                                </a>

                                <a href="{{ url('login') }}" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets') }}/images/logo-dark-3.png" width="60px" height="auto" alt="" >
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- title-->
                        <h4 class="mt-0">Log In</h4>
                        <p class="text-muted mb-4">Enter your email address and password to access account.</p>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- form -->
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Email address</label>
                                <input class="form-control @error('email')
                                    is-invalid
                                @enderror" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{-- <a href="auth-recoverpw-2.html" class="text-muted float-end"><small>Forgot your password?</small></a> --}}
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" id="password" class="form-control @error('password')
                                        is-invalid
                                    @enderror" placeholder="Enter your password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-primary" type="submit">Log In </button>
                            </div>
                        </form>
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt mb-3" style="display: block;">
                            <p class="text-muted">Don't have an account? <a href="{{ url('register') }}" class="text-muted ms-1"><b>Sign Up</b></a></p>
                            <p class="text-uppercase">Portal Orang Tua / Wali <a href="{{ url('ortu/login') }}" class="text-muted ms-1"><b>Sign In</b></a></p>
                        </footer>

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>
            <!-- end auth-fluid-form-box-->

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3 text-white">Welcome Back to App SIAKAD SMP IT Darussalam!</h2>
                    <p class="lead"><i class="mdi mdi-format-quote-open"></i> Every great achievement begins with the courage to learn and grow. Keep believing in yourself, because your future starts today. <i class="mdi mdi-format-quote-close"></i>
                    </p>
                    <h5 class="text-white">
                        - Muhammad Ibrahim Al Ayubi (Developer SIAKAD) -
                    </h5>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->
        </div>
        <!-- end auth-fluid-->

        <!-- Authentication js -->
        <script src="{{ asset('assets') }}/js/pages/authentication.init.js"></script>

    </body>
</html>
