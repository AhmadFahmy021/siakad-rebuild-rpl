<!DOCTYPE html>
<html lang="en" data-topbar-color="dark">

    <head>
        <meta charset="utf-8" />
        <title>Register | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">

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
                                <a href="index.html" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets') }}/images/logo-dark.png" alt="" height="22">
                                    </span>
                                </a>

                                <a href="index.html" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- title-->
                        <h4 class="mt-0">Create Account</h4>
                        <p class="text-muted mb-3">Get started by creating your account and stay connected with your academic activities.</p>

                        <!-- form -->
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" required="" placeholder="Enter your name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" id="username" required="" placeholder="Enter your username">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Email address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{-- <a href="auth-recoverpw-2.html" class="text-muted float-end"><small>Forgot your password?</small></a> --}}
                                <label for="password" class="form-label">Password</label>
                                {{-- <div class="input-group input-group-merge"> --}}
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
                                    {{-- <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div> --}}
                                {{-- </div> --}}
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-primary" type="submit">Create Account</button>
                            </div>
                        </form>
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted">Already have an account? <a href="{{ url('login') }}" class="text-muted ms-1"><b>Log In</b></a></p>
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
