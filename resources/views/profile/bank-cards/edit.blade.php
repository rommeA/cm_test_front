<div class="modal fade text-left" id="edit-bank-card-modal" tabindex="-1" aria-labelledby="edit-bank-card-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title white" id="edit-bank-card-modal-label">{{ __('Edit bank card') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="" id="edit-bank-card-form">
                @csrf
                <input hidden name="id" id="bank-card-edit-id" value="">
                <input type="hidden" name="_method" value="PATCH" id="bank-card-edit-form-method">
                <input type="hidden" name="user_id" value="{{$user->id}}" id="bank-card-edit-user_id">


                <div class="modal-body">

                    <label>{{ __('Number') }}: </label>
                    <div class="form-group">
                        <input name="number" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" class="form-control" value="" id="bank-card-edit-number" autocomplete="off">

                    </div>

                    <label>{{ __('Date issue') }}: </label>
                    <div class="form-group">
                        <input name="date_issue" class="form-control datepicker bank-card-edit-date_issue" value="" id="bank-card-edit-date_issue" autocomplete="off">

                    </div>

                    <label>{{ __('Date valid') }}: </label>
                    <div class="form-group">
                        <input name="date_valid" class="form-control datepicker bank-card-edit-date_valid" value="" id="bank-card-edit-date_valid" autocomplete="off">

                    </div>

                    <label>{{ trans_choice('Offices', 1) }}: </label>

                    <fieldset class="form-group">
                        <select class="form-select" name="company_id" id="bank-card-edit-company_id">
                            <option value=""></option>
                            @foreach(\App\Models\Company::where('is_archive', false)->get()->sortBy('displayName') as $company)

                                <option value="{{$company->id}}">{{ $company->displayName }}</option>

                            @endforeach
                        </select>

                    </fieldset>

                    <label>{{ __('Currency') }}: </label>

                    <fieldset class="form-group">
                        <select class="form-select" name="currency_id" id="bank-card-edit-currency_id">
                            <option value=""></option>
                            @foreach(\App\Models\Currency::all() as $currency)
                                <option value="{{$currency->id}}">{{ $currency->short_name . ', ' . $currency->displayName }}</option>
                            @endforeach
                        </select>

                    </fieldset>

                    <label>{{ __('Comment') }}: </label>
                    <div class="form-group">
                        <input type="text" name="comment" class="form-control" value="" id="bank-card-edit-comment" autocomplete="off">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                    </button>
                    <a type="submit" class="btn btn-success ml-1"  id="bank-card-save-edits">
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
        $('#edit-bank-card-modal').on('shown.bs.modal', function() {
            $(".bank-card-edit-date_issue").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });
            $(".bank-card-edit-date_valid").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });
        })

        $('#edit-bank-card-modal').on('hidden.bs.modal', function() {
            $('#bank-card-edit-number').val('')
            $('#bank-card-edit-date_issue').val('')
            $('#bank-card-edit-date_valid').val('')
            $('#bank-card-edit-comment').val('')
            $('#bank-card-edit-currency_id').val('')
            $('#bank-card-edit-company_id').val('')
        })

        $('#bank-card-save-edits').on('click', function (e){

            // removing validation messages & indicators
            $('.invalid-feedback').remove()
            $('.is-invalid').removeClass('is-invalid')
            $('.is-valid').removeClass('is-valid')
            // -----------------------------------------

            // sending the data
            let bankCardID = $('#bank-card-edit-id').val();
            let myform = document.getElementById('edit-bank-card-form');
            let fd = new FormData(myform);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/bankCards/' + bankCardID,
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    let bankCardLink = $('[data-id="'+bankCardID+'"]');

                    // updating view with the new data
                    $('.card-number[data-id="'+bankCardID+'"]').text(data['number'])
                    bankCardLink.data('number', data['number'])

                    $('.card-office[data-id="'+bankCardID+'"]').text(data['companyDisplayName'])
                    bankCardLink.data('company_id', data['company_id'])

                    $('.card-date_issue[data-id="'+bankCardID+'"]').text(data['date_issue'])
                    bankCardLink.data('date_issue', data['date_issue'])

                    $('.card-date_valid[data-id="'+bankCardID+'"]').text(data['date_valid'])
                    bankCardLink.data('date_valid', data['date_valid'])

                    $('.card-currency[data-id="'+bankCardID+'"]').text(data['currencyDisplayName'])
                    bankCardLink.data('currency_id', data['currency_id'])

                    $('.card-comment[data-id="'+bankCardID+'"]').text(data['comment'])
                    bankCardLink.data('comment', data['comment'])

                    // ------------------------------------------------------------------------

                    $('#edit-bank-card-modal').modal('hide');
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
                        $('#edit-bank-card-modal :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    }
                }
            });
        })
    </script>
@endpush
