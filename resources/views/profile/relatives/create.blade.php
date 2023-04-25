<div class="modal fade text-left" id="create-relative-modal" tabindex="-1" aria-labelledby="create-relative-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title white" id="create-relative-modal-label">{{ __('Add new relative') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="" id="create-relative-form">
                @csrf
                @method('POST')
                <input type="hidden" name="user_id" value="{{$user->id}}" id="relative-create-user_id">


                <div class="modal-body">

                    <label>{{ trans_choice('Relation', 1) }}: </label>

                    <fieldset class="form-group">
                        <select class="form-select" name="relative_type_id" id="relative-create-relative_type_id">
                            <option value=""></option>
                            @foreach(\App\Models\RelativeType::all()->sortBy('number') as $relative_type)

                                <option value="{{$relative_type->id}}">{{ $relative_type->displayName }}</option>

                            @endforeach
                        </select>

                    </fieldset>

                    <label>{{ __('Full name') }}: </label>
                    <div class="form-group">
                        <input name="full_name" class="form-control relative-create-full_name" value="" id="relative-create-full_name" autocomplete="off">
                    </div>

                    <label>{{ __('Birthday') }}: </label>
                    <div class="form-group">
                        <input name="date_birth" class="form-control datepicker relative-create-date_birth" value="" id="relative-create-date_birth" autocomplete="off">
                    </div>

                    <label>{{ __('Address') }}: </label>
                    <div class="form-group">
                        <input name="address" class="form-control relative-create-address" value="" id="relative-create-address" autocomplete="off">
                        <input name="zip_code" hidden value="" id="relative-create-zip_code" autocomplete="off">
                        <input name="country" hidden value="" id="relative-create-country" autocomplete="off">
                        <input name="region" hidden value="" id="relative-create-region" autocomplete="off">
                        <input name="city" hidden value="" id="relative-create-city" autocomplete="off">
                        <input name="street" hidden value="" id="relative-create-street" autocomplete="off">
                        <input name="building" hidden value="" id="relative-create-building" autocomplete="off">
                        <input name="apartment" hidden value="" id="relative-create-apartment" autocomplete="off">

                    </div>

                    <label>{{ __('Home phone') }}: </label>
                    <div class="form-group">
                        <input name="home_phone" class="form-control relative-create-home_phone" value="" id="relative-create-home_phone" autocomplete="off">

                    </div>

                    <label>{{ __('Mobile phone') }}: </label>
                    <div class="form-group">
                        <input name="mobile_phone" class="form-control relative-create-mobile_phone" value="" id="relative-create-mobile_phone" autocomplete="off">

                    </div>

                    <label>{{ __('Email') }}: </label>
                    <div class="form-group">
                        <input name="email" class="form-control relative-create-email" value="" id="relative-create-email" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="form-check-input relative-create-is_beneficiary"  name="is_beneficiary" id="relative-create-is_beneficiary">
                            <label class="form-check-label" for="relative-create-is_beneficiary">{{ __('Is beneficiary') }}</label>
                        </div>
                    </div>



                    <div id="relative-create-beneficiary" style="display: none">
                        <label>{{ __('Passport series') }}: </label>
                        <div class="form-group">
                            <input name="passport_series" class="form-control relative-passport_series" value="" id="relative-passport_series" autocomplete="off">
                        </div>

                        <label>{{ __('Passport number') }}: </label>
                        <div class="form-group">
                            <input name="passport_number" class="form-control relative-passport_number" value="" id="relative-passport_number" autocomplete="off">
                        </div>

                        <label>{{ __('Passport issued by') }}: </label>
                        <div class="form-group">
                            <input name="passport_place" class="form-control relative-passport_place" value="" id="relative-passport_place" autocomplete="off">
                        </div>

                        <label>{{ __('Passport date of issue') }}: </label>
                        <div class="form-group">
                            <input name="passport_date_issue" class="form-control datepicker relative-create-date_issue" value="" id="relative-create-date_issue" autocomplete="off">

                        </div>
                    </div>


                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <label class="form-check-label" id="relative-create-agreement-label" for="relative-edit-agreement">{{ __("Give permission to process relative's personal information") }}</label>
                            <input type="checkbox" class="form-check-input" id="relative-create-agreement">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                    </button>
                    <a type="submit" class="btn btn-success ml-1"  id="relative-save-create">
                        <i class="fa-solid fa-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('Save') }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $('#create-relative-modal').on('shown.bs.modal', function() {
            $(".relative-create-date_issue").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });

            $(".relative-create-date_birth").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });

        })

        $('#relative-create-is_beneficiary').on('change', function(e){
            if ($(this).is(":checked")) {
                $('#relative-create-beneficiary').show();
            } else {
                $('#relative-create-beneficiary').hide();
            }
        });



        $('#relative-save-create').on('click', function (e){

            if (! $("#relative-create-agreement").is(":checked")) {
                $("#relative-create-agreement").addClass('is-invalid');
                $("#relative-create-agreement-label").addClass('text-danger');
                return;
            }
            $("#relative-create-agreement").removeClass('is-invalid');
            $("#relative-create-agreement-label").removeClass('text-danger');

            // removing validation messages & indicators
            $('.invalid-feedback').remove()
            $('.is-invalid').removeClass('is-invalid')
            $('.is-valid').removeClass('is-valid')
            // -----------------------------------------

            // sending the data
            let myform = document.getElementById('create-relative-form');
            let fd = new FormData(myform);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            if ($("#relative-create-agreement").is(":checked")) {
                fd.set('consent_personal_data', true);
            }
            $.ajax({
                url: '/relatives',
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#create-relative-modal').hide();
                    window.location.reload();
                },
                error: function (err) {

                    if (err.status === 422) { // when status code is 422, it's a validation issue

                        $.each(err.responseJSON.errors, function (input, error) {
                            let [i, index] = input.split('.');
                            let el = $(document).find('[name="'+i+'"]');
                            if (index >= 0) {
                                el = $(document).find('[name="'+i+'['+index+']"]');
                            }
                            el.addClass('is-invalid');
                            el.after($('<div class="invalid-feedback"><i class="bx bx-radio-circle"></i>' +
                                error[0]  +
                                '</div>'));
                        });
                        $('#create-relative-modal :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    }
                }
            });
        })
    </script>



    <script>
        $("#relative-create-address").suggestions({
            token: "9ab2f4cd6166203eebaec3bce6f157e1590b52f1",
            type: "ADDRESS",
            /* Вызывается, когда пользователь выбирает одну из подсказок */
            onSelect: function(suggestion) {
                $(this).val(suggestion['unrestricted_value'])
                $("#relative-create-zip_code").val(suggestion['data']['postal_code'])
                $("#relative-create-country").val(suggestion['data']['country'])
                $("#relative-create-region").val(suggestion['data']['region_with_type'])
                $("#relative-create-city").val(suggestion['data']['city_with_type'] ?? suggestion['data']['settlement_with_type'])
                $("#relative-create-street").val(suggestion['data']['street'] ?? suggestion['data']['settlement_with_type'])
                $("#relative-create-building").val(suggestion['data']['house'])
                $("#relative-create-apartment").val(suggestion['data']['flat'])
            }
        });


    </script>
@endpush
