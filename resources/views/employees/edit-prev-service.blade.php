<div class="modal fade text-left" id="edit-ps-modal" tabindex="-1" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title white" id="myModalLabel33">{{ __('Edit previous service record') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="" id="edit-ps-form">
                @csrf
                <input hidden name="id" id="ps-edit-id" value="">
                <input type="hidden" name="_method" value="PATCH" id="ps-form-method">
                <input type="hidden" name="user_id" value="{{$employee->id}}" id="ps-edit-user_id">


                <div class="modal-body">

                    <div class="form-group">
                    <div class="form-check">
                        <div class="custom-control custom-checkbox">
                            <label class="form-check-label" for="ps-edit-is-external">{{ __('External company') }}</label>
                            <input type="checkbox" class="form-check-input form-check-warning form-check-glow" name="is_external" id="ps-edit-is-external">
                        </div>
                    </div>
                    </div>

                    <div class="internal-company">

                        <label>{{ trans_choice('Companies', 1) }}: </label>
                        <div class="form-group internal-company">
                            <select class="ps-edit-company_id" name="company_id" id="ps-edit-company_id">
                                <option value=""></option>
                                @foreach(\App\Models\Company::all()->sortBy('displayName') as $company)

                                    <option value="{{$company->id}}">{{ $company->displayName }}</option>

                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="external-company" style="display: none;">

                        <label>{{ trans_choice('Companies', 1) }} (en): </label>
                        <div class="form-group">
                            <input class="form-control" name="company_name" id="ps-edit-company_name">
                        </div>

                        <label>{{ trans_choice('Companies', 1) }} (ru): </label>
                        <div class="form-group">
                            <input class="form-control" name="company_name_ru" id="ps-edit-company_name_ru">
                        </div>
                    </div>


                    <div class="internal-company">
                        <label>{{ __('Position') }}: </label>
                        <div class="form-group">
                            <select class="ps-edit-position_id" name="position_id" form="edit-ps-form" id="ps-edit-position_id">
                            </select>
                        </div>
                    </div>

                    <div class="external-company" style="display: none;">
                        <label>{{ __('Position') }} (en): </label>

                        <div class="form-group">
                            <input class="form-control" name="position_name" id="ps-edit-position_name">
                        </div>

                        <label>{{ __('Position') }} (ru): </label>

                        <div class="form-group">
                            <input class="form-control" name="position_name_ru" id="ps-edit-position_name_ru">
                        </div>

                    </div>

                    <label>{{ __('Type') }}: </label>
                    <div class="form-group">
                        <select class="form-select" name="is_full_time" id="ps-edit-is_full_time">
                            <option value="1" >{{ __('Full-time') }}</option>
                            <option value="0" >{{ __('Part-time') }}</option>
                        </select>


                    </div>



                    <label>{{ __('Date from') }}: </label>
                    <div class="form-group">
                        <input name="date_from" class="form-control datepicker ps-edit-date_from" value="" id="ps-edit-date_from" autocomplete="off">

                    </div>

                    <label>{{ __('Date to') }}: </label>
                    <div class="form-group">
                        <input name="date_to" class="form-control datepicker ps-edit-date_to" value="" id="ps-edit-date_to" autocomplete="off">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                    </button>
                    <a type="submit" class="btn btn-success ml-1"  id="ps-save-edits">
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
        $('#ps-edit-is-external').on('change', function(e){
            if ($(this).is(":checked")) {
                $('.external-company').show();
                $('.internal-company').hide();
            } else {
                $('.internal-company').show();
                $('.external-company').hide();
            }
        });


        $( "#edit-ps-modal" ).on('hidden.bs.modal', function(){
            $(':input','#edit-ps-form')
                .not(':button, :submit, :reset, [name="_method"], [name="user_id"]')
                .val('')
                .prop('checked', false)
                .prop('selected', false);
            $('#ps-edit-is-external').trigger('change');

            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.is-valid').removeClass('is-valid');

            psPositionChoices.removeActiveItems()
            psCompanyChoices.removeActiveItems()
            psIsFullTimeChoices.removeActiveItems()
        });

    </script>
@endpush
