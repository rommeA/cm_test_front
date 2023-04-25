@extends('layouts.app-no-sidebar')
@section('title')
    Verify — Crew Master
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="header">

                        {{ __('Verify Your Email Address') }}
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                        <br>
                    Before proceeding, please check your email <b>({{ auth()->user()->email }})</b> for a verification link.
                        <br>
                        <div id="timer-div">
                            {{ __('You can request new link in ') }} <b id="seconds">90</b> {{ __('seconds') }}.
                            <br>
                        </div>



                        <div id="request-new-email-div" style="display: none">
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button id="request-new-email" disabled type="submit" class="btn btn-primary p-0 m-0 "> {{ __('click here to request another') }} </button>.
                            </form>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        function showRequestNewEmailButton() {
            $('#request-new-email').removeAttr('disabled');
            $('#request-new-email-div').show();
            $('#timer-div').hide();
        }


        function changeSecondsLabel()
        {
            startSeconds = startSeconds - 1;
            $('#seconds').html(startSeconds);
            if (startSeconds > 0) {
                setTimeout(changeSecondsLabel, 1000);
            }
        }

        let startSeconds = 90;
        @if(\jdavidbakr\MailTracker\Model\SentEmail::where('recipient_email', auth()->user()->email)->whereBetween('created_at', [now()->subMinutes(2), now()])->get()->first())
            setTimeout(changeSecondsLabel, 1000);
            setTimeout(showRequestNewEmailButton, 1000 * startSeconds);
        @else
            showRequestNewEmailButton()
        @endif


    </script>
@endsection


