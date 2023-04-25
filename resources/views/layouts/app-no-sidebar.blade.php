<!doctype html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- Scripts -->

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome.js') }}"></script>

    @stack('scripts')
    <link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/chartjs/Chart.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/ship.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/extensions/carousel.css') }}">


    @stack('styles')
</head>
<body>

    <div id="loadingModal"  class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent shadow-none">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="app">
        <div id="main" style="margin-left: 0;">
        @auth
            @include('layouts.navbar')
        @endauth
                <div class="main-content container-fluid">
                    <div class="page-title">
                        <h3>@yield('page-title')</h3>
                        <p class="text-subtitle text-muted">@yield('page-subtitle')</p>
                    </div>
                    <section class="section">
                        @yield('content')
                    </section>
                </div>

            </div>
    </div>
    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>

    <script src=" {{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src=" {{ asset('js/datatables.min.js') }}"></script>
    <script src=" {{ asset('js/custom/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    <script>


        $( document ).ajaxError(function( event, jqxhr, settings, exception ) {
            if ( jqxhr.status === 401 ) {
                jQuery.noConflict();
                (function( $ ) {
                    $(function() {
                        // More code using $ as alias to jQuery

                        $('#loadingModal').modal('show');

                    });
                })(jQuery);
                window.location.reload();
            }
        });
    </script>
    @stack('scripts-body')

</body>

</html>
