@extends('layouts.app')
@section('title')
    Reset password — Crew Master
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

            form h6{
                text-align: center;
            }

            label{
                display: block;
                margin-top: 10px;
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
                height: 40px;
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


        </style>
    @endpush
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <h3 style="color: #fdfdfd">Crew Master</h3>
        <h6 style="color: #fdfdfd">Reset password</h6>

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">{{ __('Email Address') }}</label>

        <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <label for="password" class="">{{ __('Password') }}</label>

        <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror


        <label for="password-confirm" class="">{{ __('Confirm Password') }}</label>

        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

        <div class="social">
            <button type="submit" style="background-color: rgba(255,255,255,0.7)">{{ __('Reset Password') }}</button>
        </div>
    </form>

</div>
