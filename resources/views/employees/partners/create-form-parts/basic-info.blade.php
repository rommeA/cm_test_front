{{------------------------------- Basic info -------------------------------}}

<div class="divider">
    <div class="divider-text">
        <span class="badge bg-info mb-3">
        {{ __('BASIC INFORMATION') }}
        </span>
    </div>
</div>

<div class="col-12">
    <div class="form-group">
        <label for="partner-firstname">{{ __('Firstname') }} (en) <b class="text-danger">*</b></label>

        <input id="partner-firstname"
               oninput="this.value = this.value.replace(/[^A-z\-\s]/g, '');"
               onfocus="this.placeholder='{{ __('Only in English') }}'"
               onblur="this.placeholder='';"
               type="text"
               class="form-control is-required @error('firstname') is-invalid @enderror"
               name="firstname"
               value="{{ $user->firstname ?? '' }}"
               data-percentage="{{ config('enums.application_fields_percentage.firstname_en') }}"
               data-field-name="{{ __("Firstname") }}"
               required autocomplete="firstname" autofocus>

        @error('firstname')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="col-12">
    <div class="form-group">
        <label for="lastname">{{ __('Lastname') }} (en)<b class="text-danger">*</b></label>

        <input id="partner-lastname"
               oninput="this.value = this.value.replace(/[^A-z\-\s]/g, '');"
               onfocus="this.placeholder='{{ __('Only in English') }}'"
               onblur="this.placeholder='';"
               type="text"
               class="form-control is-required @error('lastname') is-invalid @enderror"
               name="lastname"
               value="{{ $user->lastname ?? '' }}"
               data-percentage="{{ config('enums.application_fields_percentage.lastname_en') }}"
               data-field-name="{{ __("Lastname") }}"
               required autocomplete="lastname" autofocus>

        @error('lastname')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="divider"></div>

<div class="col-12">
    <div class="form-group">
        <label>{{ __("Firstname")  }} (ru): <b class="text-danger">*</b></label>

        <input id="partner-firstname_ru"
               oninput="this.value = this.value.replace(/[^А-яЁё\-\s]/g, '');"
               onfocus="this.placeholder='{{ __('Only in Russian') }}'"
               onblur="this.placeholder='';"
               type="text" class="form-control is-required @error('firstname_ru') is-invalid @enderror"
               name="firstname_ru"
               value="{{ $user->firstname_ru ?? '' }}"
               data-percentage="{{ config('enums.application_fields_percentage.firstname_ru') }}"
               data-field-name="{{ __("Firstname") }} (ru)"
               autocomplete="lastname" autofocus>

        @error('firstname_ru')
        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
        @enderror
    </div>
</div>

<div class="col-12">
    <div class="form-group">
        <label>{{ __("Patronymic")  }} (ru): </label>

        <input id="partner-patronymic"
               oninput="this.value = this.value.replace(/[^А-яЁё\-\s]/g, '');"
               onfocus="this.placeholder='{{ __('Only in Russian') }}'"
               onblur="this.placeholder='';"
               data-field-name="{{ __("Patronymic") }}"
               data-percentage="{{ config('enums.application_fields_percentage.patronymic_ru') }}"
               type="text"
               class="form-control @error('patronymic') is-invalid @enderror"
               name="patronymic"
               value="{{ $user->patronymic_ru ?? '' }}"
               autocomplete="lastname" autofocus>

        @error('patronymic')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="col-12">
    <div class="form-group">
        <label>{{ __("Lastname")  }} (ru): <b class="text-danger">*</b></label>

        <input id="partner-lastname_ru"
               oninput="this.value = this.value.replace(/[^А-яЁё\-\s]/g, '');"
               onfocus="this.placeholder='{{ __('Only in Russian') }}'"
               onblur="this.placeholder='';"
               data-field-name="{{ __("Lastname") }} (ru)"
               data-percentage="{{ config('enums.application_fields_percentage.lastname_ru') }}"
               type="text"
               class="form-control is-required @error('lastname_ru') is-invalid @enderror"
               name="lastname_ru"
               value="{{ $user->lastname_ru ?? '' }}"
               autocomplete="lastname" autofocus>

        @error('lastname_ru')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="divider"></div>

<div class="row">
    {{--     Gender    --}}
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __("Gender")  }}: </label>

            <select class="form-select is-required" name="sex" id="partner-sex" data-field-name="{{ __("Gender") }}"
                    data-percentage="{{ config('enums.application_fields_percentage.gender') }}">
                <option value=""></option>
                @foreach(config('enums.sex') as $sex)
                    <option value="{{ $sex }}"
                        {{ isset($user) && $sex === $user->sex  ? "selected" : ''}}>
                        {{ __($sex) }}</option>
                @endforeach
            </select>
            @error('sex')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
            @enderror
        </div>
    </div>

    {{--     Birthday    --}}
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="date_birth">{{ __('Birthday') }}</label>

            <input id="partner-date_birth"
                   data-field-name="{{ __("Birthday") }}"
                   data-percentage="{{ config('enums.application_fields_percentage.birthday') }}" type="text"
                   class="form-control @error('date_birth') is-invalid @enderror"
                   name="date_birth"
                   value="{{ isset($user) && $user->date_birth ? $user->date_birth->format('d.m.Y') : '' }}"
                   min="{{ date('Y-m-d',strtotime("-100 years")) }}"
                   max="{{ date('Y-m-d',strtotime("-18 years")) }}"
                   required autocomplete="date_birth" autofocus>
            @error('date_birth')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __("Country")  }}: </label>
            <div class="form-group">
                <select class="choices form-select" name="country_id" id="country_id">
                    <option value="{{ $russia->id }}">{{ __($russia->name) }}</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ isset($user) && $country->id  === $user->infoPartner?->country_id  ? "selected" : ''}}>
                            {{ __($country->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __("Position")  }}: </label>
            <div class="form-group">
                <input type="text"
                       class="form-control"
                       id="position_partner"
                       name="position_partner"
                       required
                       value="{{ $user->infoPartner?->position ?? '' }}"
                       autocomplete="off"
                >
            </div>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $("#partner-date_birth").datepicker({
            maxDate: "-18Y",
            minDate: "-100Y",
            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:-18",
            beforeShow: function (input, inst) {
                // Handle calendar position before showing it.
                // It's not supported by Datepicker itself (for now) so we need to use its internal variables.
                var calendar = inst.dpDiv;

                // Dirty hack, but we can't do anything without it (for now, in jQuery UI 1.8.20)
                setTimeout(function () {
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
