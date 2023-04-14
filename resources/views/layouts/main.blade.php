<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>RKD | Dashboard </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_90x90.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/loader.js') }}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.css') }}">

    <link href="{{ asset('css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/animate/animate.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->

    @yield('css')

</head>

<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    @include('layouts.header')

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>

        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('layouts.menu')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            {{-- BEGIN MAIN CONTENT --}}
            @yield('konten')
            {{-- BEGIN FOOTER CONTENT --}}
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © 2021 <a target="_blank" href="https://itsk-soepraoen.ac.id/">ITSK
                            Soepraoen</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class=""></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    {{-- <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>

    <script src="{{ asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/datatables.select.min.js') }}"></script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN THEME GLOBAL STYLE -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.all.js') }}"></script>
    <!-- END THEME GLOBAL STYLE -->

    {{-- DATETIMEPICKER --}}
    <script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    <script>
        $(document).ready(function() {

            // Init App Js
            App.init();

            // Setup Header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @yield('script')

</body>

</html>
