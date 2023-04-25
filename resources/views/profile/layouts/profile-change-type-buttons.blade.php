<div class="col col-xl-2 col-md-2 me-3 mb-1">
    <div class=" dropdown">
        @if($user->employee_type !== config('enums.employee_type.partners'))
        <button class="btn btn-outline-success icon" type="button" id="extraActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-bars"></i>
        </button>
        @endif
        <div class="dropdown-menu" aria-labelledby="extraActions" style="margin: 0">
            @if($user->application_form_status == config('enums.application_form_status.checking'))
                @can('changeApplicantStatus', [\App\Models\User::class, $user])
                    <h6 class="dropdown-header">{{ __('Actions with application') }}</h6>


                    <a class="dropdown-item" href="#" title="{{ __('Send back with comment') }}"
                       data-bs-toggle="modal" data-bs-target="#sendApplicationBackModal">
                        <i class="fa-solid fa-reply text-warning"></i>
                        {{ __('Send back with comment') }}
                    </a>

                    <a class="dropdown-item" href="#" title="{{ __('Move to candidates') }}"
                       data-bs-toggle="modal" data-bs-target="#toCandidatesModal">
                        <i class="fa-solid fa-user-clock text-primary"></i>
                        {{ __('Move to candidates') }}
                    </a>

                    <a class="dropdown-item" href="#" title="{{ __('Deny') }}" style="display: none">
                        <i class="fa-solid fa-ban text-danger"></i>
                        {{ __('Deny') }}
                    </a>
                @endcan

            @elseif($user->employee_type === config('enums.employee_type.seaman_crew'))
                <h6 class="dropdown-header">Change type</h6>

                <a class="dropdown-item" href="#"  title="{{ __('Candidate') }}"
                   data-bs-toggle="modal" data-bs-target="#toCandidatesModal">
                    <i class="fa-solid fa-user-clock text-warning"></i>
                    {{ __('Candidate') }}
                </a>

                <a class="dropdown-item" href="#"  title="{{ __('Precaution') }}"
                   data-bs-toggle="modal"
                   data-bs-target="#toPrecautionModal">
                    <i class="fa-solid fa-user-large-slash text-danger"></i>
                    {{ __('Precaution') }}
                </a>

                @can('archive', [\App\Models\User::class, $user])
                    @if($user->is_archive === false)
                        <a class="dropdown-item" href="#" title="{{ __('Archive') }}"
                           data-bs-toggle="modal"
                           data-bs-target="#archive-employee-confirm">
                            <i class="fa-solid fa-box-archive text-secondary"></i>
                            {{ __('Archive') }}
                        </a>
                    @endif
                @endcan

                @if(! ($user->is_seaman && $user->employee_type === config('enums.employee_type.office_employees')))
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Create extra profile</h6>

                <a class="dropdown-item" href="#"  title="{{ __('Create office employee card') }}"
                   data-bs-toggle="modal" data-bs-target="#makeDoubleProfileModal">
                    <i class="fa-solid fa-user-group text-success"></i>
                    {{ __('Create office employee card') }}
                </a>
                @endif

            @elseif($user->employee_type === config('enums.employee_type.seaman_crew_archive'))
                <h6 class="dropdown-header">Change type</h6>

                <a class="dropdown-item" href="#"  title="{{ __('Precaution') }}"
                   data-bs-toggle="modal"
                   data-bs-target="#toPrecautionModal">
                    <i class="fa-solid fa-user-large-slash text-danger"></i>
                    {{ __('Precaution') }}
                </a>

                <a class="dropdown-item" href="#" title="{{ __('Return to the crew') }}"
                   data-bs-toggle="modal" data-bs-target="#backToCrewModal">
                    <i class="fa-solid fa-rotate-left text-warning"></i>
                    {{ __('Return to the crew') }}
                </a>

            @elseif($user->employee_type === config('enums.employee_type.seaman_precaution'))
                <h6 class="dropdown-header">Change type</h6>

                @can('archive', [\App\Models\User::class, $user])
                    @if($user->is_archive === false)
                        <a class="dropdown-item" href="#" title="{{ __('Archive') }}"
                           data-bs-toggle="modal"
                           data-bs-target="#archive-employee-confirm">
                            <i class="fa-solid fa-box-archive text-secondary"></i>
                            {{ __('Archive') }}
                        </a>
                    @endif
                @endcan

                <a class="dropdown-item" href="#" title="{{ __('Return to the crew') }}"
                   data-bs-toggle="modal" data-bs-target="#backToCrewModal">
                    <i class="fa-solid fa-rotate-left text-warning"></i>
                    {{ __('Return to the crew') }}
                </a>

            @elseif($user->employee_type === config('enums.employee_type.seaman_candidates'))

                <h6 class="dropdown-header">Change type</h6>
                <a class="dropdown-item" href="#" title="{{ __('Accept to the Crew') }}"
                   data-bs-toggle="modal"
                   data-bs-target="#acceptToCrewModal">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    {{ __('Accept for the Crew') }}
                </a>

                <a class="dropdown-item" href="#" title="{{ __('Return to applicants') }}"
                   data-bs-toggle="modal" data-bs-target="#toApplicantsModal">
                    <i class="fa-solid fa-circle-check text-warning"></i>
                    {{ __('Return to applicants') }}
                </a>


            @elseif($user->employee_type === config('enums.employee_type.office_employees'))

                <h6 class="dropdown-header">Change type</h6>

                @can('archive', [\App\Models\User::class, $user])
                    @if($user->is_archive === false)
                        <a class="dropdown-item" href="#" title="{{ __('Archive') }}"
                           data-bs-toggle="modal"
                           data-bs-target="#archive-employee-confirm">
                            <i class="fa-solid fa-box-archive text-secondary"></i>
                            {{ __('Archive') }}
                        </a>
                    @endif
                @endcan

                @if(! ($user->is_seaman && $user->employee_type === config('enums.employee_type.office_employees')))

                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Create extra profile</h6>

                <a class="dropdown-item" href="#"  title="{{ __('Create seaman card') }}"
                   data-bs-toggle="modal" data-bs-target="#makeDoubleProfileModal">
                    <i class="fa-solid fa-ship text-success"></i>
                    {{ __('Create seaman card') }}
                </a>
                @endif


            @elseif($user->employee_type === config('enums.employee_type.office_archive'))

                <h6 class="dropdown-header">Change type</h6>

                @can('archive', [\App\Models\User::class, $user])
                    @if($user->is_archive === true)
                        <a class="dropdown-item" href="#" title="{{ __('Return to the office employees') }}"
                           data-bs-toggle="modal" data-bs-target="#backEmployeesModal">
                            <i class="fa-solid fa-rotate-left text-warning"></i>
                            {{ __('Return to the office employees') }}
                        </a>
                    @endif
                @endcan


            @elseif($user->employee_type === config('enums.employee_type.office_candidates'))

                <h6 class="dropdown-header">Change type</h6>
                <a class="dropdown-item" href="#" title="{{ __('Accept as an Employee') }}"
                   data-bs-toggle="modal">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    {{ __('Accept as an Employee') }}
                </a>
            @endif
        </div>
    </div>

</div>
