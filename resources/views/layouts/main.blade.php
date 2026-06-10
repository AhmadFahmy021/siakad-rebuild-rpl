<!DOCTYPE html>
<html lang="en" data-topbar-color="dark">

    <head>
        <meta charset="utf-8" />
        <title>Dashboard | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">

        <!-- third party css -->
        <link href="{{ asset('assets') }}/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->

		<!-- Theme Config Js -->
		<script src="{{ asset('assets') }}/js/head.js"></script>

		<!-- Bootstrap css -->
		<link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="app-style" />

		<!-- App css -->
		<link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />

		<!-- Icons css -->
		<link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />

        {{-- <!-- Sweet Alert-->
        <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" /> --}}

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </head>

    <body>

        <!-- Begin page -->
        <div id="wrapper">


            <!-- ========== Menu ========== -->
            @include('layouts.sidebar')
            <!-- ========== Left menu End ========== -->





            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">

                <!-- ========== Topbar Start ========== -->
                @include('layouts.topbar')
                <!-- ========== Topbar End ========== -->

                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        @yield('main')

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div>2025-<script>document.write(new Date().getFullYear())</script> © Developer A3 - All rights reserved.</div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Vendor js -->
        <script src="{{ asset('assets') }}/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('assets') }}/js/app.min.js"></script>

        <!-- third party js -->
        <script src="{{ asset('assets') }}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="{{ asset('assets') }}/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="{{ asset('assets') }}/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="{{ asset('assets') }}/libs/pdfmake/build/vfs_fonts.js"></script>
        <!-- third party js ends -->

        <!-- Datatables init -->
        <script src="{{ asset('assets') }}/js/pages/datatables.init.js"></script>

        <!-- Sweet Alerts js -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('click', function(event) {

                var target = event.target;
                var confirmDeleteElement = target.closest('[data-confirm-delete2]');

                if (confirmDeleteElement) {

                    event.preventDefault();

                    let nama = confirmDeleteElement.dataset.name;
                    let customText = confirmDeleteElement.dataset.text || 'Apakah yakin ingin menghapus "' + nama + '" ?';

                    Swal.fire({
                        title: 'Hapus Data?',
                        text: customText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then(function(result) {

                        if (result.isConfirmed) {

                            var form = document.createElement('form');

                            form.action = confirmDeleteElement.href;

                            form.method = 'POST';

                            form.innerHTML = `
                                @csrf
                                @method('DELETE')
                            `;

                            document.body.appendChild(form);

                            form.submit();
                        }

                    });

                }

            });
        </script>

         @include('sweetalert::alert')
         @yield('js')
    </body>
</html>
