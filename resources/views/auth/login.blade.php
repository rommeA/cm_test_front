@extends('layouts.app')
@section('title')
    Login — Crew Master
@endsection
<div id="auth">
    @push('scripts')
        <link href="{{asset('fonts/Poppins.css')}}" rel="stylesheet">
        <!--Stylesheet-->
        <style media="screen">
            *,
            *:before,
            *:after{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
            .background{
                width: 430px;
                height: 520px;
                position: absolute;
                transform: translate(-50%,-50%);
                left: 50%;
                top: 50%;
            }
            .background .shape{
                height: 200px;
                width: 200px;
                position: absolute;
                border-radius: 50%;
            }
            .shape:first-child{
                background: linear-gradient(
                    #1845ad,
                    #23a2f6
                );
                left: -80px;
                top: -80px;
            }
            .shape:last-child{
                background: linear-gradient(
                    to right,
                    #ff512f,
                    #f09819
                );
                right: -30px;
                bottom: -80px;
            }
            form{
                height: 520px;
                width: 400px;
                background-color: rgba(255,255,255,0.13);
                position: absolute;
                transform: translate(-50%,-50%);
                top: 50%;
                left: 50%;
                border-radius: 10px;
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255,255,255,0.1);
                box-shadow: 0 0 40px rgba(8,7,16,0.6);
                padding: 50px 35px;
            }
            form *{
                font-family: 'Poppins',sans-serif;
                color: #ffffff;
                letter-spacing: 0.5px;
                outline: none;
                border: none;
            }
            form h3{
                font-size: 32px;
                font-weight: 500;
                line-height: 42px;
                text-align: center;
            }

            label{
                display: block;
                margin-top: 30px;
                font-size: 16px;
                font-weight: 500;
            }
            .form-control {
                background-color: rgba(255,255,255,0.3) !important;
                border: none !important;
                color: #ffffff !important;
            }
            input{
                display: block;
                height: 50px;
                width: 100%;
                background-color: rgba(255,255,255,0.3);
                border-radius: 3px;
                padding: 0 10px;
                margin-top: 8px;
                font-size: 14px;
                font-weight: 300;
            }
            ::placeholder{
                color: #e5e5e5 !important;
            }
            button{
                margin-top: 50px;
                width: 100%;
                background-color: #ffffff;
                color: #080710;
                padding: 15px 0;
                font-size: 18px;
                font-weight: 600;
                border-radius: 5px;
                cursor: pointer;
            }
            .social{
                margin-top: 30px;
                display: flex;
            }
            .social div{
                background: red;
                width: 150px;
                border-radius: 3px;
                padding: 5px 10px 10px 5px;
                background-color: rgba(255,255,255,0.27);
                color: #eaf0fb;
                text-align: center;
            }
            .social div:hover{
                background-color: rgba(255,255,255,0.47);
            }
            .social .fb{
                margin-left: 25px;
            }
            .social i{
                margin-right: 4px;
            }

        </style>

        <style>
            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active{
                -webkit-box-shadow: 0 0 0 30px rgba(255,255,255) inset !important;
                -webkit-text-fill-color: black !important;

            }

            .remember-me  {
                margin-top: 5px;
                margin-left: 5px;
                color: #fdfdfd!important;
            }
            .form-check-input {
                width: 0.5em !important;
                height: 1.5em!important;
            }

            .button {
                text-align: center;
                display:inline-block;
                text-decoration: none !important;
                margin:0 auto;

                -webkit-transition: all 0.2s ease-in-out;
                -moz-transition: all 0.2s ease-in-out;
                -ms-transition: all 0.2s ease-in-out;
                -o-transition: all 0.2s ease-in-out;
                transition: all 0.2s ease-in-out;
            }
            a:hover{
                text-decoration:underline!important;
                text-decoration-color: #fdfdfd;
            }

           small:hover{
                text-decoration:underline!important;
                text-decoration-color: #fdfdfd;
            }
            button:hover {
                background-color: rgba(255,255,255,0.9)!important;
            }

        </style>
    @endpush
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

        <h3 style="color: #fdfdfd">Crew Master</h3>
            @if (session('status'))
                <div class="alert alert-danger alert-dismissible fade show" style="font-size: 10px;">
                    {{ session('status') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-x"></i> </span>
                    </button>

                </div>
            @endif
        <label for="username" style="margin-top: 15px;">{{ __('Email or Login') }}</label>
        <input type="text" class="@error('email') is-invalid @enderror" placeholder="Email or Login" id="username" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" placeholder="Password" id="password" class="input form-control"  name="password" required autocomplete="current-password">


            <div class="input-group-append">
                <span class="input-group-text" onclick="password_show_hide();" style="height: 50px;background-color: rgba(255,255,255,0.7)">
                    <i class="fas fa-eye d-none" style="color: black;" id="show_eye"></i>
                    <i class="fas fa-eye-slash " style="color: black;" id="hide_eye"></i>
                </span>
            </div>

        </div>
        @if (Route::has('password.request'))
            <div class="clearfix">
                <a tabindex=-1 href="{{ route('password.request') }}" class='float-end'>
                    <small>{{ __('Forgot Your Password?') }}</small>
                </a>
            </div>
        @endif

            <div class='form-check  my-4 form-group'>

                <div class="checkbox float-start">

                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="remember-me">{{ __('Remember Me') }}</label>

                </div>

            </div>

            <div class="social">
                <button type="submit" style="background-color: rgba(255,255,255,0.7)">{{ __('Login') }}</button>
            </div>
{{--            <div class="social" style="text-align: center;">--}}
{{--                <a class="button" style="color: #fdfdfd; text-align: center;" class="text-center" href="{{ route('register') }}">{{ __('Register as seaman') }} </a>--}}
{{--            </div>--}}
    </form>
</div>

<script>
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("hide_eye");
        var hide_eye = document.getElementById("show_eye");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }
</script>

