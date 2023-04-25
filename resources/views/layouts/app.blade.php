<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
{{--    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">--}}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

{{--    @yield('title')--}}

    <title>@yield('title')</title>

    <!-- Scripts -->

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome.js') }}"></script>

    @stack('scripts')
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>

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
    <div id="loadingModal"  class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent shadow-none">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span id="loadingModalLabel" class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(! Str::contains(Request::url(), '/login'))
    <div id="app">
    @auth
        @include('layouts.sidebar')
    @endauth

        <div id="main">
        @auth
            @include('layouts.navbar')
        @endauth
                <div class="main-content container-fluid">
                    @yield('main-top-section')
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
    @endif
    <script>
        if(window.innerWidth >= 900) {
            if (localStorage.getItem("sidebar_hidden") === null) {
                localStorage.setItem('sidebar_hidden', 'false');
            }else{
                if (localStorage.getItem("sidebar_hidden") == 'true'){
                    $('#sidebar').removeClass('active')

                } else {
                    $('#sidebar').addClass('active')
                }
            }
        } else {
            $('#sidebar').removeClass('active')
        }

        $('.sidebar-toggler').on('click', function (e){


            if ($('#sidebar').hasClass('active')) {
                localStorage.setItem('sidebar_hidden', 'false');
            } else {
                localStorage.setItem('sidebar_hidden', 'true');
            }
        })
        $(":input").on("keyup change", function(e) {
            $(this).removeClass('is-valid').removeClass('is-invalid');
        })
    </script>
    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>

    <script src=" {{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src=" {{ asset('js/datatables.min.js') }}"></script>
    <script src=" {{ asset('js/custom/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    <script src="{{ asset("js/custom/files-upload.js") }}"></script>
    <script src="{{ asset("js/extensions/filepond.js") }}"></script>
    <script src="{{ asset("js/extensions/filepond-plugin-image-preview.js") }}"></script>

    <script src="{{ asset("js/extensions/filepond4.js") }}"></script>
    <script src="{{ asset("js/extensions/filepond.jquery.js") }}"></script>

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

        //! If uncomment, dadata breaks
        // $.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
        //     options.xhrFields = {
        //         withCredentials: true
        //     };
        // });


        $(document).ready(function () {

            // refresh token to avoid 419 error when using ajax
            setInterval(keepTokenAlive, 1000 * 60 * 15); // every 15 mins

            function keepTokenAlive() {
                $.ajax({
                    url: '/keep-token-alive', //https://stackoverflow.com/q/31449434/470749
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {

                        console.log(new Date() + ' ' + data + ' ' + $('meta[name="csrf-token"]').attr('content'));
                    },
                    error: function (err) {
                        window.location.reload();
                    }
                })
            }

        });
    </script>
    @stack('scripts-body')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>

</html>
