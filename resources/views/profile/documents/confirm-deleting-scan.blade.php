
<!--Danger theme Modal -->
<div class="modal fade text-left" id="delete-scan-confirm" tabindex="-1" aria-labelledby="confirmDeleteScanLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title white" id="confirmDeleteScanLabel">{{ __("Confirm your action") }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-scan">
                    @method('DELETE')

                </form>
                <p>
                    {{ __('Delete this scan?') }}
                </p>
                <p>
                    {{ __('Filename') }}: <b id="confirm-delete-filename"></b>
                </p>
                <p>
                    {{ __('Document') }}: <b id="confirm-delete-doctype"> </b>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-danger ml-1" data-bs-dismiss="modal" id="btn-delete-scan-confirm">
                    {{ __("Confirm") }}
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts-body')
    <script>
        $('#btn-delete-scan-confirm').on('click', function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            let myform = document.getElementById('delete-scan');

            let fd = new FormData(myform);
            $.ajax({
                url: '/documents/' + $('#input-document-id').val() + '/scans/' + deleteScanId,
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,

                success: function (data) {
                    $('a.delete-scan[data-scan-id="' + deleteScanId + '"').closest('.col-xl-3').hide();
                }
            })
        })
    </script>
@endpush
