<!--BorderLess Modal Modal -->
<div class="modal fade text-center modal-borderless w-100" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning" id="modal-header-pdf">
                <h5 class="modal-title white" id="photoModalLabel">
                    {{ __('PDF preview') }} 1 {{ __('page of') }} <span id="pdf-pages-count"></span>
                </h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-header bg-warning" id="modal-header-scan" style="display: none">
                <h5 class="modal-title white" id="photoModalLabel">
                    {{ __('File preview') }}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <a class="btn btn-outline-warning" style="border-radius: 0;" id="btn-download-full-version">{{ __('Download full version') }}</a>
            <div class="modal-body" id="modal-scans-container">
                <img class="img-fluid" style="max-width: 80%; max-height: 80%" src="" alt="doc scan" id="scan-large">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-bs-dismiss="modal">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/blowup/blowup.js') }}"></script>
<script>
    $("#photoModal").draggable({
        handle: ".modal-header"
    });

    // $("#scan-large").blowup({
    //     background : "#FCEBB6"
    // });
</script>
