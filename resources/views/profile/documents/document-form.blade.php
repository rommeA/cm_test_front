@push('styles')
    <link rel="stylesheet" href="{{ asset('css/extensions/filepond.css') }}">
@endpush

<form class="form form-horizontal" id="edit-document-form">
    @csrf
    <input type="hidden" name="_method" value="PATCH" id="form-method">
    <input hidden id="input-user-id" value="{{$user->id}}" name="user_id">
    <input hidden id="input-document-id" name="id">
    <input hidden id="input-doctype" name="document_type_id">
    <input hidden id="input-is-archive" name="is_archive" value="false" disabled>


    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <label>{{ __('Number') }}</label>
            </div>
            <div class="col-md-9 form-group">
                <input type="text" id="input-number" class="form-control document-form-input" name="number" placeholder="{{ __('Number') }}" required readonly>
                <span id="span-number" class="input-alter"> </span>
            </div>
            <div class="col-md-3">
                <label>{{ __('Date issue') }}</label>
            </div>
            <div class="col-md-9 form-group">
                <input id="input-date-issue" class="form-control document-form-input datepicker" name="date_issue" placeholder="{{ __('Date issue') }}" required readonly max="{{ date('Y-m-d', strtotime("today")) }}">
                <span id="span-date-issue" class="input-alter"> </span>

            </div>
            <div class="col-md-3">
                <label>{{ __('Date valid') }}</label>
            </div>
            <div class="col-md-9 form-group">
                <input  id="input-date-valid" class="form-control document-form-input datepicker" name="date_valid" placeholder="{{ __('Date valid') }}" readonly>
                <span id="span-date-valid" class="input-alter"> </span>

            </div>

            <div class="col-md-3">
                <label>{{ __('Place') }}</label>
            </div>
            <div class="col-md-9 form-group" id="document-place-field-div">
                <input type="text" id="input-place" class="form-control document-form-input" name="place" placeholder="{{ __('Place') }}" required readonly>
                <span id="span-place" class="input-alter"> </span>
            </div>


            <div class="col-md-3">
                <label>{{ __('Relevant') }}</label>
            </div>
            <div class="col-md-9 form-group">
                <input type="checkbox" id="input-is-relevant" class="form-check-input" name="is_relevant" placeholder="{{ __('Relevant') }}" @cannot('update', 'App\Models\User::class') disabled @endcan>
                <span id="span-is-relevant" class="input-alter"> </span>
            </div>

            <div class="col-md-3">
                <label>{{ __('Status') }}</label>
            </div>
            <div class="col-md-9 form-group">
                <i id="status-icon" class="fa-solid fa-circle"></i>
            </div>




            <div class="col-md-12 form-group">


                <div class="row" id="images-div">

                </div>
                <div id="drop-area" style="display: none;">
                    <form  class="files-form select-files-button">

                        <input id="fileInput" type="file" class="multiple-files-filepond" multiple accept="image/*,application/pdf">
                    </form>
                </div>
            </div>

            <div class="col-sm-12 justify-content-end"  style="display: none;">
                <a href="#" class="btn btn-success me-1 mb-1" id="btn-save-edits"> {{ __('Save') }} </a>
                <a type="reset" href="#" class="btn btn-light-secondary me-1 mb-1" id="btn-reset"> {{ __('Reset') }} </a>
            </div>
        </div>
    </div>

    @if(! auth()->user()->is_applicant)

    @include('layouts.elements.copy-id-button')
    @endif
</form>




@push('scripts-body')
    @include('layouts.modals.scan-preview-modal')




    <script>
        // $.datepicker.setDefaults($.datepicker.regional['ru']);
        $("#input-date-issue").datepicker({

            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:+100",
            onSelect: function(dateStr)
            {
                let oldval = $("#input-date-valid").val();
                $("#input-date-valid").datepicker("destroy");
                $("#input-date-valid").val(oldval);
                $("#input-date-valid").datepicker({
                    minDate: dateStr,
                    dateFormat: 'dd.mm.yy',
                    changeYear: true,
                    yearRange: "-100:+100",
                })
            }


        });

        let dateIssue = $("#input-date-issue").val();


        $("#input-date-valid").datepicker({
            minDate: dateIssue,
            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:+100",
        });
    </script>
@endpush
