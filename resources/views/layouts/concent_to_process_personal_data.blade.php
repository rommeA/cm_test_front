@extends('layouts.app-no-sidebar')
@section('title')
    Personal data processing — Crew Master
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="card-header">
                    <h4 class="card-header text-center">
                        {{ __('To continue, please confirm that you are agree with the terms of the processing of your personal data') }}
                    </h4>
                </div>

                <form method="POST" action="{{ route('give-permission-to-process-data') }}" id="consent_form">
                    @csrf
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-10 offset-md-1">
                                <div class='form-check'>
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" name="consent_personal_data" id="consent_personal_data" {{ old('consent_personal_data') ? 'checked' : '' }}>
                                        <label for="remember">{{ __('I agree with the terms of the processing of my personal data unconditionally and unrestrictedly') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" form="consent_form" disabled class="btn btn-primary" id="btn-submit-register">
                                    {{ __('Continue') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

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
</script>
@endpush
