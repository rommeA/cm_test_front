<div class="modal fade text-left" id="create-bank-card-modal" tabindex="-1" aria-labelledby="create-bank-card-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title white" id="create-bank-card-modal-label">{{ __('Add new bank card') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="" id="create-bank-card-form">
                @csrf
                @method('POST')
                <input type="hidden" name="user_id" value="{{$user->id}}" id="bank-card-create-user_id">


                <div class="modal-body">

                    <label>{{ __('Number') }}: </label>
                    <div class="form-group">
                        <input name="number" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" class="form-control" value="" id="bank-card-create-number" autocomplete="off">

                    </div>

                    <label>{{ __('Date issue') }}: </label>
                    <div class="form-group">
                        <input name="date_issue" class="form-control datepicker bank-card-create-date_issue" value="" id="bank-card-create-date_issue" autocomplete="off">

                    </div>

                    <label>{{ __('Date valid') }}: </label>
                    <div class="form-group">
                        <input name="date_valid" class="form-control datepicker bank-card-create-date_valid" value="" id="bank-card-create-date_valid" autocomplete="off">

                    </div>

                    <label>{{ trans_choice('Offices', 1) }}: </label>

                    <fieldset class="form-group">
                        <select class="form-select" name="company_id" id="bank-card-create-company_id">
                            <option value=""></option>
                            @foreach(\App\Models\Company::where('is_archive', false)->get()->sortBy('displayName') as $company)

                                <option value="{{$company->id}}">{{ $company->displayName }}</option>

                            @endforeach
                        </select>

                    </fieldset>

                    <label>{{ __('Currency') }}: </label>

                    <fieldset class="form-group">
                        <select class="form-select" name="currency_id" id="bank-card-create-currency_id">
                            <option value=""></option>
                            @foreach(\App\Models\Currency::all() as $currency)
                                <option value="{{$currency->id}}">{{ $currency->short_name . ', ' . $currency->displayName }}</option>
                            @endforeach
                        </select>

                    </fieldset>

                    <label>{{ __('Comment') }}: </label>
                    <div class="form-group">
                        <input type="text" name="comment" class="form-control" value="" id="bank-card-create-comment" autocomplete="off">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                    </button>
                    <a type="submit" class="btn btn-success ml-1"  id="bank-card-save-create">
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
        $('#create-bank-card-modal').on('shown.bs.modal', function() {
            $(".bank-card-create-date_issue").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });
            $(".bank-card-create-date_valid").datepicker({
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'dd.mm.yy',
                changeYear: true,
                yearRange: "-100:+100"
            });
        })



        $('#bank-card-save-create').on('click', function (e){

            // removing validation messages & indicators
            $('.invalid-feedback').remove()
            $('.is-invalid').removeClass('is-invalid')
            $('.is-valid').removeClass('is-valid')
            // -----------------------------------------

            // sending the data
            let myform = document.getElementById('create-bank-card-form');
            let fd = new FormData(myform);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/bankCards',
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    let newCard = '<div class="col-xl-4 col-md-6 col-sm-12 h-100">' +
                        '<div class="card card-bank-card border-' + data['statusStyleName'] + '">'+
                        '<div class="card-header">' +
                        '<div class="card-title">' +
                        '<div class="row">' +
                        '<div class="col-10"> New bank card </div>' +
                        '<div class="col-1">' +
                        '<i class="fa fa-edit text-light clickable edit-bank-card"' +
                           ' data-id="'+ data['id']+'"'+
                           ' data-number="'+ data['number']+'"'+
                           ' data-date_issue="'+ data['date_issue']+'"' +
                           ' data-date_valid="'+ data['date_valid']+'"' +
                           ' data-company_id="'+ data['company_id']+'"' +
                           ' data-currency_id="'+ data['currency_id']+'"' +
                           ' data-comment="'+ data['comment']+'"' +
                           ' data-is_archive="'+ data['is_archive']+'"' +

                           ' data-bs-toggle="modal"' +
                           ' data-bs-target="#edit-bank-card-modal"></i></div>' +

                    '<div class="col-1">'+
                        '<i class="fa fa-trash-can text-danger clickable delete-bank-card"'+
                            ' data-id="'+ data['id']+'"'+
                            ' data-number="'+ data['number']+'"'+
                            ' data-date_issue="'+ data['date_issue']+'"' +
                            ' data-date_valid="'+ data['date_valid']+'"' +
                            ' data-company_id="'+ data['company_id']+'"' +
                            ' data-currency_id="'+ data['currency_id']+'"' +
                            'data-currency_display_name="'+ data['currencyDisplayName']+'"'+
                            ' data-comment="'+ data['comment']+'"' +
                            ' data-is_archive="'+ data['is_archive']+'"' +

                            ' data-bs-toggle="modal"' +
                            ' data-bs-target="#confirmDelete-bankCard-modal"></i></div></div></div></div>'+
                    '<div class="card-body">' +
                       ' <b>{{ __('Number') }}:</b> <u class="card-number" data-id="'+ data['id']+'">'+ data['number']+'</u>' +
                    '</div>' +
                   ' <div class="card-body">' +
                        '<b>{{ trans_choice('Office', 1) }}:</b> <u class="card-office" data-id="'+ data['id']+'">'+ data['companyDisplayName'] +'</u>' +
                   ' </div>' +
                    '<div class="card-body">' +
                        '<b>{{ __('Date issue') }}:</b> <u class="card-date_issue" data-id="'+ data['id']+'">'+ data['date_issue']+'</u>' +
                    '</div>' +
                    '<div class="card-body">' +
                        '<b>{{ __('Date valid') }}:</b> <u class="card-date_valid" data-id="'+ data['id']+'">'+ data['date_valid']+'</u>' +
                    '</div>' +
                    '<div class="card-body">' +
                        '<b>{{ __('Currency') }}:</b> <u class="card-currency" data-id="'+ data['id']+'">'+ data['currencyDisplayName']  +'</u>' +
                    '</div>' +
                    '<div class="card-body">' +
                        '<b>{{ __('Comment') }}:</b> <u class="card-comment" data-id="'+ data['id']+'">'+ data['comment']+'</u></div></div></div>';

                    $('#bank-cards-row').prepend($(newCard));

                    $('#bank-card-create-number').val('')
                    $('#bank-card-create-date_issue').val('')
                    $('#bank-card-create-date_valid').val('')
                    $('#bank-card-create-comment').val('')
                    $('#bank-card-create-currency_id').val('')
                    $('#bank-card-create-company_id').val('')

                    $('#create-bank-card-modal').modal('hide');
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
                        $('#create-bank-card-modal :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    }
                }
            });
        })
    </script>
@endpush
