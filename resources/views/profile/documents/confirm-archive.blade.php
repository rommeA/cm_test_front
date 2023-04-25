
<!--Danger theme Modal -->
<div class="modal fade text-left" id="archive-confirm" tabindex="-1" aria-labelledby="myModalLabel120" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title white" id="myModalLabel120">{{ __("Confirm your action") }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    {{ __('Archive this document?') }}
                </p>
                <p>
                    {{ trans_choice('Employees', 1) }}: <b>{{ $user->displayName }}</b>
                </p>
                <p>
                    {{ __('Document') }}: <b id="document-description"> </b>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-outline-danger ml-1" data-bs-dismiss="modal" id="btn-archive-and-create">
                    {{ __("Confirm & Create new") }}
                </button>
                <button type="button" class="btn btn-danger ml-1" data-bs-dismiss="modal" id="btn-archive-confirm">
                    {{ __("Confirm") }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $('#btn-archive-and-create').on('click', function (e){
            e.preventDefault();
            $('#btn-archive-confirm').trigger('click', 'archive-create');

        });

        $('#btn-archive-confirm').on('click', function (e, eventData) {
            e.preventDefault();

            let input = $('#input-is-archive');
            input.val('true');
            $('#edit-document-form :input').prop('readonly', false).prop('disabled', false);
            $('#btn-save-edits').trigger('click', eventData ?? 'archive');
            input.val('false');

            if ( $('#hide-archived-label').is(":hidden")) {
                $("[data-document-id=" + $('#input-document-id').val() + "]").hide();
            }

            if (eventData !== 'archive-create') {
                let firstVisibleDoc = $('a.list-group-item:visible:first');
                if (firstVisibleDoc.length < 1) {
                    $('#activeDocumentCard').hide();
                }
                firstVisibleDoc.trigger('click');
            }
        });
    </script>
@endpush
