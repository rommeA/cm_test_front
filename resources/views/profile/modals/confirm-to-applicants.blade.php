
<div class="modal fade text-left" id="toApplicantsModal" tabindex="-1" aria-labelledby="toApplicantsModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title white" id="toApplicantsModalLabel">{{ __("Confirm your action") }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">

                <form action="" id="moveToApplicants">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                </form>

                <p>
                    {{ __('Are you sure you want to transfer') }} <b>{{ $user->displayName }}</b> {{ __('back to applicants') }}?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-warning ml-1" data-bs-dismiss="modal" id="btn-to-applicants-confirm">
                    {{ __("Confirm") }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $("#btn-to-applicants-confirm").click(function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            let formId = 'moveToCandidatesForm';

            let myform = document.getElementById(formId);
            let fd = new FormData(myform);

            $.ajax({
                url: "{{ isset($user) ? route('applicants.candidateToApplicant', ['user' => $user->slug]) : ''}}",
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    window.location.reload();

                },
                error: function (err) {

                    console.log(err)
                    if (err.status === 422) { // when status code is 422, it's a validation issue
                        // console.log(err.responseJSON);
                        // $('#success_message').fadeIn().html(err.responseJSON.message);

                        // you can loop through the errors object and show it to the user
                        // console.warn(err.responseJSON.errors);
                        // display errors on each form field
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
                        $('#'+modalId+' :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    } else if (err.status === 401) {
                        window.location.reload();
                    }
                }
            });

        });
    </script>
@endpush
