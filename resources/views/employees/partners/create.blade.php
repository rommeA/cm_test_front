<div class="row" id="partner-create-form" style="display: none">
    <div class="card col-xl-8 col-md-10 col-sm-12 offset-xl-2 offset-md-1 offset-sm-0">
        <div class="card-header">
            <div class="row">
                <div class="col-11">
                    <h4 class="card-title"> {{ __('Create partner form') }}</h4>
                </div>
                <div class="col-1">
                    <i class="fa-solid fa-xmark text-secondary clickable" id="partner-create-close-btn"></i>
                </div>
            </div>
        </div>

        <div class="card-content">
            <div class="card-body">
                <form method="POST" class="form form-vertical" id="partner-create-form-form"
                      action=" {{ route('employees.store' ) }}">
                    @method('POST')
                    @csrf
                    <input hidden name="employee_type" id="partner-employee-type"
                           value="{{ config('enums.employee_type.partners') }}">
                    <div class="modal-body">
                        @include('employees.partners.create-form-parts.basic-info')
                        @include('employees.partners.create-form-parts.addresses-and-contacts')
                        @include('employees.partners.create-form-parts.photo-and-comment')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" id="cancel-create-partner"
                                data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1" id="btn-store-partner">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $("#application-date_from").datepicker({
            maxDate: "+1Y",
            minDate: "-1Y",
            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-1:+1",
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

    <script>
        jQuery.extend(jQuery.expr[':'], {
            focusable: function (el, index, selector) {
                return $(el).is('a, :input, [tabindex]');
            }
        });

        $(document).on('keypress', 'input,select', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                // Get all focusable elements on the page
                var $canfocus = $(':focusable').not('button').not('a').not('[type="checkbox"]');
                var index = $canfocus.index(this) + 1;
                if (index >= $canfocus.length) index = 0;
                $canfocus.eq(index).focus();
            }
        });


        $('#partner-create-form-form :input').on('change', function (e) {
            $(this).removeClass('is-invalid').removeClass('is-valid');
        })

        loadingModalShouldBeHiddenEmployees = true;

        $('#loadingModal').on('shown.bs.modal', function (e) {
            if (loadingModalShouldBeHiddenEmployees) {
                $('#loadingModal').modal('hide');
            }
        })

        $('#btn-store-partner').on('click', function (e) {
            $('#partner-create-form-form :input').removeClass('is-valid').removeClass('is-invalid');

            $('.invalid-feedback').remove()
            $('#loadingModal').modal('show');
            loadingModalShouldBeHiddenEmployees = false;
            let myform = document.getElementById('partner-create-form-form');
            let fd = new FormData(myform);

            let fileName = $("#upload-partner-photo").val();
            if (fileName) {
                $uploadCropEditPartner.croppie('result', 'blob').then(function(blob) {
                    fd.set('photo_file', blob);
                    $.ajax({
                        url: "{{ route('partners.store') }}",
                        data: fd,
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            $('#loadingModal').modal('hide', 0);
                            loadingModalShouldBeHiddenEmployees = true;

                            window.location.href = '/partners/' + data['slug']

                        },
                        error: function (err) {
                            console.log(err)
                            loadingModalShouldBeHiddenEmployees = true;
                            $('#loadingModal').modal('hide', 0);

                            if (err.status === 422) { // when status code is 422, it's a validation issue
                                // console.log(err.responseJSON);
                                // $('#success_message').fadeIn().html(err.responseJSON.message);

                                // you can loop through the errors object and show it to the user
                                // console.warn(err.responseJSON.errors);
                                // display errors on each form field
                                let scrolled_to_input = false;

                                $.each(err.responseJSON.errors, function (input, error) {


                                    let [i, index] = input.split('.');
                                    let el = $(document).find('[name="' + i + '"]');
                                    if (index >= 0) {
                                        el = $(document).find('[name="' + i + '[' + index + ']"]');
                                    }
                                    el.addClass('is-invalid');
                                    el.after($('<div class="invalid-feedback"><i class="bx bx-radio-circle"></i>' +
                                        error[0] +
                                        '</div>'));

                                    if (!scrolled_to_input) {
                                        scrolled_to_input = true;
                                        $('html, body').animate({
                                            scrollTop: $('#' + el.attr('id')).offset().top - 500
                                        }, 0);
                                    }

                                });


                                $('#partner-create-form-form :input').filter(function () {
                                    return $.trim($(this).val()).length > 0
                                }).addClass('is-valid');

                            } else if (err.status === 401) {
                                window.location.reload();
                            }
                        }
                    });
                });
            } else {
                $.ajax({
                    url: "{{ route('partners.store') }}",
                    data: fd,
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#loadingModal').modal('hide', 0);
                        loadingModalShouldBeHiddenEmployees = true;

                        window.location.href = '/partners/' + data['slug']

                    },
                    error: function (err) {
                        console.log(err)
                        loadingModalShouldBeHiddenEmployees = true;
                        $('#loadingModal').modal('hide', 0);

                        if (err.status === 422) { // when status code is 422, it's a validation issue
                            // console.log(err.responseJSON);
                            // $('#success_message').fadeIn().html(err.responseJSON.message);

                            // you can loop through the errors object and show it to the user
                            // console.warn(err.responseJSON.errors);
                            // display errors on each form field
                            let scrolled_to_input = false;

                            $.each(err.responseJSON.errors, function (input, error) {


                                let [i, index] = input.split('.');
                                let el = $(document).find('[name="' + i + '"]');
                                if (index >= 0) {
                                    el = $(document).find('[name="' + i + '[' + index + ']"]');
                                }
                                el.addClass('is-invalid');
                                el.after($('<div class="invalid-feedback"><i class="bx bx-radio-circle"></i>' +
                                    error[0] +
                                    '</div>'));

                                if (!scrolled_to_input) {
                                    scrolled_to_input = true;
                                    $('html, body').animate({
                                        scrollTop: $('#' + el.attr('id')).offset().top - 500
                                    }, 0);
                                }

                            });


                            $('#partner-create-form-form :input').filter(function () {
                                return $.trim($(this).val()).length > 0
                            }).addClass('is-valid');

                        } else if (err.status === 401) {
                            window.location.reload();
                        }
                    }
                });
            }
        })

        $('#reset-create-partner-form, #partner-create-close-btn, #cancel-create-partner').on('click', function (e) {
            e.preventDefault();
            $('#header-create-employee').hide();
            $('#btn-create-save').hide();
            $('#btn-create-save-open-docs').hide();
            $('#partner-create-form').hide();
            $('#application-progress').hide();

            $('#btn-save').show();
            $('#header-edit-employee').show();
            $('#mainContent').show();
        })
    </script>

    <script>
        $("#country_id").change(function () {
            turnOnDadata()
        })
        turnOnDadata()

        function turnOnDadata()
        {
            if ($("#country_id").val() === '{{ $russia->id }}') {
                $("#partner-actual_address").suggestions({
                    token: "9ab2f4cd6166203eebaec3bce6f157e1590b52f1",
                    type: "ADDRESS",
                    /* Вызывается, когда пользователь выбирает одну из подсказок */
                    onSelect: function(suggestion) {
                        $(this).val(suggestion['unrestricted_value'])
                    }
                });
            } else {
                $("#partner-actual_address").suggestions({});
            }
        }
    </script>


@endpush
