<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Block account</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome.js') }}"></script>

    @stack('scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/ship.png') }}" type="image/x-icon">

</head>
<body>
<div id="app">
    <div id="">
        <div class="main-content container-fluid">
            <section class="section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="px-4 py-5 my-5 text-center">
                            <h1 class="display-5 fw-bold">{{ __('Account blocking') }}</h1>
                            <div class="col-lg-6 mx-auto">
                                <p class="lead mb-4">
                                    {{--                    A suspicious login has been made into your account {{ $authInfo->login_at->format('d.m.Y') }} at {{ $authInfo->login_at->format('H:i') }}.--}}
                                    {{ __('A suspicious login has been made into your account :date at :time.', ['date'=> $authInfo->login_at->setTimezone('Europe/Moscow')->format('d.m.Y'), 'time' => $authInfo->login_at->setTimezone('Europe/Moscow')->format('H:i')]) }}
                                    ({{__('Timezone: ')}}{{ __('Moscow') }})
                                </p>
                                <p class="lead mb-4">
                                    @if(isset($authInfo->cityName ) and isset($authInfo->countryName ))
                                        {{ __('Location') }}: {{ $authInfo->cityName }}, {{ $authInfo->countryName }}.
                                    @elseif(isset($authInfo->countryName ))
                                        {{ __('Location') }}: {{ $authInfo->countryName }}.
                                    @endif
                                </p>
                                <p class="lead mb-4">
                                    {{ __('Device') }}: {{ $device }}
                                </p>
                                <p class="lead mb-4">
                                    {{ __('IP-address') }}: {{ $authInfo->ip_address }}.
                                </p>
                                <p class="lead mb-4">
                                    {!! __("If it wasn't you, please click the :button button.", ['button'=> '<mark>'.__('Block account').'</mark>']) !!}


                                    @if(! $user->is_ldap)
                                        {{ __("In this case, your account will be blocked, all active sessions will be terminated and we'll send you a link to reset your password.") }}
                                    @else
                                        {!! __("In this case, your account will be blocked and all active sessions will be terminated. To restore access you need to contact the administrator via :adminEmail.", ['adminEmail' => '<mark>'.$adminEmail.'</mark>']) !!}
                                    @endif
                                </p>
                                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                    <form method="POST" action="{{ route('block-account-confirm') }}">
                                        @csrf
                                        <input type="text" hidden name="url" value="{{ Request::fullUrl() }}">
                                        <input type="text" hidden name="user_id" value="{{ request('user_id') }}">
                                        @if(! $user->is_ldap)
                                            <input type="text" hidden name="email" value="{{ $user->email }}">
                                        @endif
                                        <button type="submit" class="btn btn-danger btn-lg px-4 gap-3">
                                            @if($user->is_ldap)
                                                {{ __('Block account') }}
                                            @else
                                                {{ __('Block account & send password reset link') }}
                                            @endif
                                        </button>
                                        <a type="button" class="btn btn-outline-secondary btn-lg px-4" href="{{ route('home') }}">{{ __('Cancel') }}</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
</body>

</html>


