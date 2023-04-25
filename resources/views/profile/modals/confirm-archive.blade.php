
<!--Danger theme Modal -->
<div class="modal fade text-left" id="archive-employee-confirm" tabindex="-1" aria-labelledby="archiveEmployeeModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title white" id="archiveEmployeeModalLabel">{{ __("Confirm your action") }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">

                <p>
                </p>

                <p>
                    {{ __('Are you sure you want to archive this employee/seaman') }}: <b>{{ $user->displayName }}</b>?
                </p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-danger ml-1" data-bs-dismiss="modal" id="btn-archive-employee-confirm">
                    {{ __("Confirm") }}
                </button>
            </div>
        </div>
    </div>
</div>
