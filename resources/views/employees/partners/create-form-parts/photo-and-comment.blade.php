@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
<div class="divider">
    <div class="divider-text">
        <span class="badge bg-info mb-3">{{ __('PHOTO & COMMENT') }}</span>
    </div>
</div>

<div class="divider"></div>
<div class="row">
    <div class="col partner-photo">
        @if(isset($user) && $user->binary_photo)
            <img class="img-fluid" src="data:image/png;base64,{!! $user->photoNew !!} " alt="partner photo" id="partner-photo">
        @endif
    </div>

    <div class="col" id="partner-upload-div-edit"></div>
    <div class="col">
        <div class="form-group">
            <div class="form-file">
                <label> {{ __('Upload new photo') }} <input id="upload-partner-photo" type="file" accept="image/*"></label>
            </div>
        </div>
    </div>

</div>
<div class="row" id="partner-delete-photo-row" @if(isset($user) && !$user->binary_photo) style="display: none;" @endif>
    <div class="col">
        <a id="partner-delete-photo-btn" class="btn btn-danger btn-outline icon"><i class="fa-solid fa-xmark"></i> {{ __('Delete photo') }}</a>
    </div>
</div>
<div class="divider"></div>



<div class="row">
    <div class="col-md-12 col-12">
        <div class="form-group">
            <label>{{ __("Comment")  }}: </label>
            <div class="form-group">
            <textarea type="text"
                      class="form-control"
                      id="comment"
                      name="comment"
                      autocomplete="off"
            >{{ $user->comment ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>


<div class="divider">
    <div class="divider-text">
        <span class="badge bg-info mb-3">{{ __('CONSENT TO THE PROCESSING OF PERSONAL DATA') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-1 form-group">
        <input type="checkbox"
               id="input-consent_personal_data"
               class="form-check-input"
               name="consent_personal_data"
               placeholder="{{ __('HAS CONSENT TO THE PROCESSING OF PERSONAL DATA?') }}"
            {{isset($user) ? $user->consent_personal_data ? 'checked' : '' : ''}}>
    </div>
    <div class="col-md-11">
        <label>{{ __('HAS CONSENT TO THE PROCESSING OF PERSONAL DATA?') }}</label>
    </div>
</div>


@push('scripts-body')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        $uploadCropEditPartner = $('#partner-upload-div-edit').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: 250,
                height: 250
            }
        }).hide();

        $("#upload-partner-photo").on('change', function (e){
            let reader = new FileReader();
            reader.onload = function (e) {
                $uploadCropEditPartner.croppie('bind', {
                    url: e.target.result
                })
            }
            reader.readAsDataURL(this.files[0]);
            $('#partner-upload-div-edit').show();
            $('#partner-delete-photo-row').show();
        })

        $('#partner-delete-photo-btn').on('click', function (e) {
            e.preventDefault();
            $('#partner-upload-div-edit').hide();
            $('#upload-partner-photo').val('');
            $('#partner-delete-photo-row').hide();

            let fd = new FormData(document.getElementById("partner-form-delete-photo"));
            @if(isset($user))
                $.ajax({
                    url: "{{ route('partners.deletePhoto', ['user' => $user->slug] ) }}",
                    data: fd,
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#partner-photo').hide();
                    }
                });
            @else
                $('#partner-photo').hide();
            @endif
        })

    </script>
@endpush
