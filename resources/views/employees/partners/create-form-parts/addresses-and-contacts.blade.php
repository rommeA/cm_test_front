{{------------------------------- Citizenship, addresses & contacts  -------------------------------}}

<div class="divider">
    <div class="divider-text">
        <span class="badge bg-info mb-3">{{ __('ADDRESSES & CONTACTS') }}</span>
    </div>
</div>

<div class="row">
    {{--     Email    --}}
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="email">{{ __('Email Address') }} <b class="text-danger">*</b></label>

            <input id="application-email" type="email"
                   class="form-control is-required @error('email') is-invalid @enderror"
                   data-field-name="{{ __("Email Address") }}"
                   name="email"
                   value="{{ $user->email ?? '' }}"
                   data-percentage="{{ config('enums.application_fields_percentage.email') }}"
                   required autocomplete="email">

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    {{--     Mobile phone    --}}
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __("Mobile phone")  }}:</label>
            <input type="tel"
                   class="form-control is-required"
                   id="partner-mobile_phone"
                   name="phone"
                   value="{{ $user->phone ?? '' }}"
                   data-percentage="{{ config('enums.application_fields_percentage.phone') }}"
                   data-field-name="{{ __("Mobile phone") }}"
                   oninput="this.value = this.value.replace(/[^+0-9\(\)]/g, '');"
                   onfocus="this.placeholder='{{ __('Only numbers, "+" and "()"') }}'"
                   onblur="this.placeholder='';"
                   onclick="phoneChange(this.id, true);"
                   onfocusout="phoneChange(this.id);"
            >
            @error('phone')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>
</div>

<div class="col-12">
    <div class="form-group">
        <label class="mb-1">{{ __("Actual address")  }}:
        </label>
        <input type="text"
               class="form-control is-required @error('actual_address') is-invalid @enderror"
               id="partner-actual_address"
               name="actual_address"
               value="{{ $user->registration_address ?? '' }}"
               data-percentage="{{ config('enums.application_fields_percentage.address_actual') }}"
               data-field-name="{{ __("Actual address") }}"

        >
        @error('actual_address')
        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
        @enderror
        <br>

    </div>
</div>

<div class="row">
    <label>{{ __("Contractor")  }}: </label>
    <div class="col-md-11 col-11">
        <div class="form-group">
            <div class="form-group">
                <select class="form-select" name="contractor_id" id="contractor_id">
                    <option value="{{ $user->infoPartner?->contractor_id ?? '' }}" selected>
                        {{ $user->infoPartner?->contractor?->realName ?? ''}}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-1">
        <a class="btn btn-outline-primary icon icon-left" href="{{ route('contractors.create') }}" target="_blank"><i class="fa-solid fa-plus"></i> {{ __('') }}</a>

    </div>
</div>

@push('scripts-body')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        const contractorsChoices = new Choices(document.getElementById('contractor_id'), {
            itemSelectText: 'fsa',
            removeItems: true,
            removeItemButton: true,
            searchEnabled: true,
            searchFields: ['label', 'value'],
            position: 'bottom',
            searchPlaceholderValue: '',
        });

        let contractor_elem= document.getElementById('contractor_id');
        contractor_elem.addEventListener(
            'showDropdown',
            function(event) {
                $.ajax({
                    url: '/datatable/contractors?draw=2&columns%5B0%5D%5Bdata%5D=avatar&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=false&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=name&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=UTR&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=false&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=director&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=false&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=phone&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=email&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=false&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=status&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=false&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=1&order%5B0%5D%5Bdir%5D=desc&start=0&length=10000&search%5Bvalue%5D=&search%5Bregex%5D=false&_=1679914258077',
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        contractorsChoices.setChoices(data.aaData, 'id', 'name', true);
                    },
                    error: function (e) {
                        console.log(e)
                    }
                });
            },
            false,
        );
    </script>
@endpush
