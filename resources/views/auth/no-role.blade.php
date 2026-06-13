<!DOCTYPE html>
<html lang="en" data-topbar-color="dark">

    <head>
        <meta charset="utf-8" />
        <title>Akses Terbatas | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                    <div class="p-3 w-100">

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
                        <div class="text-center mt-4">
                            <h4 class="mt-0 text-danger">Anda Belum Memiliki Role!</h4>
                            <p class="text-muted mb-4">
                                Akun Anda berhasil didaftarkan dan Anda sedang login, namun akun ini belum diberikan hak akses (role) ke dalam sistem SIAKAD. <br><br>
                                <strong>Silakan hubungi Administrator</strong> untuk mengaktifkan role akun Anda agar dapat mengakses dashboard.
                            </p>

                            <a href="{{ route('logout') }}" 
                               class="btn btn-primary mt-3"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Kembali ke Halaman Login (Logout)
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>

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
