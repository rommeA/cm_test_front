@extends('layouts.app-no-sidebar')
@section('title')
    Register — Crew Master
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register as seaman applicant') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="firstname" class="col-md-4 col-form-label text-md-end">{{ __('First name') }} (in English)</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus>

                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="lastname" class="col-md-4 col-form-label text-md-end">{{ __('Last name') }} (in English)</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus>

                                @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="date_birth" class="col-md-4 col-form-label text-md-end">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="date_birth" type="text" class="form-control @error('date_birth') is-invalid @enderror" name="date_birth" value="{{ old('date_birth') }}" required autocomplete="date_birth" autofocus>

                                @error('date_birth')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                            <div class='form-check'>
                                <div class="checkbox">
                                    <input class="form-check-input" name="consent_personal_data" type="checkbox" id="consent_personal_data" {{ old('consent_personal_data') ? 'checked' : '' }}>
                                    <label for="consent_personal_data">{{ __('I agree with the terms of the processing of my personal data unconditionally and unrestrictedly') }}</label>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" disabled class="btn btn-primary" id="btn-submit-register">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-body')
<script>
    $('#consent_personal_data').on('change', function (e){
        if ($(this).is(':checked')) {
            $('#btn-submit-register').removeAttr('disabled')
        } else {
            $('#btn-submit-register').attr('disabled', 'disabled')
        }
    })
    $("#date_birth").datepicker({
        maxDate: "-18Y",
        minDate: "-100Y",
        dateFormat: 'dd.mm.yy',
        changeYear: true,
        yearRange: "-100:-18",
        beforeShow: function(input, inst) {
            // Handle calendar position before showing it.
            // It's not supported by Datepicker itself (for now) so we need to use its internal variables.
            var calendar = inst.dpDiv;

            // Dirty hack, but we can't do anything without it (for now, in jQuery UI 1.8.20)
            setTimeout(function() {
                calendar.position({
                    my: 'center top',
                    at: 'center bottom',
                    collision: 'none',
                    of: input
                });
            }, 2);
        }
    });
</script>
@endpush
