<div class="row" id="edit-partner-form" style="display: none">
    <div class="card col-xl-6 col-md-10 col-sm-12 offset-xl-3 offset-md-1 offset-sm-0">
        <div class="card-header">
            <div class="row">
                <div class="col-11">
                    <h4 class="card-title"> {{ __('Edit partner form') }}</h4>
                </div>
                <div class="col-1">
                    <i class="fa-solid fa-xmark text-secondary clickable" id="partner-edit-close-btn"></i>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" class="form form-vertical" id="edit-partner-form-form"
                      action=" {{ route('employees.update', ['employee' => $user] ) }}">
                    <input hidden name="employee_type" id="partner-employee-type"
                           value="{{ config('enums.employee_type.partners') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                       @include('employees.partners.create-form-parts.basic-info')
                        @include('employees.partners.create-form-parts.addresses-and-contacts')
                        @include('employees.partners.create-form-parts.photo-and-comment')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                                data-bs-dismiss="modal" id="cancel-edit-partner">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1" id="btn-save-partner">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--@include('contractors.confirm-archive')--}}
{{--@include('contractors.confirm-restore')--}}

@push('scripts-body')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            $(document).on('click', "#edit-item", function () {
                $(this).addClass('edit-item-trigger-clicked');
            })
        });

        $('#editPartnerModal')
            .on('hidden.bs.modal', function () {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked');
                $('.extra-contacts').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
                $('.upload-demo').removeClass('ready');
                $('#upload-edit').val('');
                $('#upload-div-edit').show();
                $('#photo').attr('src', '');
                $('#modal-save-footer').show();
            })

        $('#editPartnerModal :input').on('change', function (e) {
            $(this).removeClass('is-invalid');
            $(this).removeClass('is-valid');
        });

        $("#btn-save-partner").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.invalid-feedback').remove()

            let myform = document.getElementById('edit-partner-form-form');
            let fd = new FormData(myform);
            fd.set('consent_personal_data', fd.get('consent_personal_data_new') === 'on' ? 1 : 0)

            let fileName = $("#upload-partner-photo").val();
            if (fileName) {
                $uploadCropEditPartner.croppie('result', 'blob').then(function(blob) {
                    fd.set('photo_file', blob);
                    $.ajax({
                        url: "",
                        data: fd,
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (err) {
                            if (err.status === 422) {
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
                                });
                            }
                        }
                    });
                });
            } else {
                $.ajax({
                    url: "",
                    data: fd,
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (err) {
                        if (err.status === 422) {
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
                            });
                        }
                    }
                });
            }
        });

        function phoneChange(id, isClick = false) {
            let input = document.getElementById(id);
            let phone = input.value
            const firstChar = phone.charAt(0);
            if (firstChar !== "+") {
                phone = "+" + phone
            }
            if (phone === '+' && !isClick) {
                phone = ''
            }

            input.value = phone
        }

        $('#country_id').on('change', function (e) {
            if ($(this).val() !== '') {
                $("#russian-fields").hide();
                $("#KPP").removeAttr('required');
                $("#OGRN").removeAttr('required');

            } else {
                $("#russian-fields").show();
                $("#KPP").prop('required', true);
                $("#OGRN").prop('required', true);
            }
        });

    </script>
@endpush
