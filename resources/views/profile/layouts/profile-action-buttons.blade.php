<div id="profile-action-buttons">
    <div class="row align-items-center justify-content-center mb-0">

        @if ($user->is_draft && $user->id == auth()->user()->id)
        @else
            @can('update', [\App\Models\User::class, $user])
                <div class="col col-xl-2 col-md-2 me-3 mb-1">
                    @if($user->employee_type !== config('enums.employee_type.partners'))
                        @if(Str::contains(Request::url(), '/seamen'))
                            <a class="btn btn-outline-success icon show-application-form" title="{{ __('Edit') }}"><i class="fa-solid fa-pen"></i></a>
                        @else
                            <a class="btn btn-outline-success icon edit-item-trigger-clicked" data-user-id="{{$user->id}}" id="edit-item" title="{{ __('Edit') }}" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fa-solid fa-pen"></i></a>
                        @endif
                    @endif
                </div>
            @endcan
        @endif

        @if( $user->is_seaman)
            @can('getCV', [\App\Models\User::class, $user])
                <div class="col col-xl-2 col-md-2 me-3 mb-1">
                    <div class="dropend">
                        <a class="btn btn-success icon"
                           data-bs-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false"><i
                                class="fa-solid fa-file-arrow-down"></i></a>
                        <div class="dropdown-menu" style="margin: 0;">
                            <a class="dropdown-item">
                                <input type="checkbox"
                                       class="form-check-input form-check-primary form-check-glow form-check-sm"
                                       name="lastTenYears" id="lastTenYears">
                                <label class="form-check-label"
                                       for="lastTenYears">
                                    <font color=#808080>Only last 10 years</font>
                                </label>
                            </a>
                            <a class="dropdown-item" onclick="getFullCVInfo()"
                               target="_blank"
                               id="cv">{{ __("Export full CV ") }}</a>
                            <a class="dropdown-item" onclick="getShortCVInfo()"
                               target="_blank"
                               id="cv">{{ __("Export short CV") }}</a>
                        </div>
                    </div>
                </div>
            @endcan
        @endif

        @if ($user->id !== auth()->user()->id)
            @can('update', [\App\Models\User::class, $user])
                @include('profile.layouts.profile-change-type-buttons')
            @endcan
        @elseif( $user->is_draft)
            <div class="media d-flex align-items-center justify-content-center">
                <div class="name flex-grow-1">
                            <span class="text-sm">
                                <a class="btn btn-sm btn-success icon icon-left mb-3 show-application-form" title="Edit"><i class="fa-solid fa-pen"></i> <span class="d-none d-xl-inline">{{ __("Edit CV") }}</span></a>
                                <a class="btn btn-sm btn-primary icon icon-left mb-3 check-cv-btn"><i class="fa-solid fa-paper-plane"></i> <span class="d-none d-lg-inline">{{ __('Send CV') }}</span></a>
                            </span>
                    <span class="text-sm">
                                <a class="btn btn-sm btn-danger icon icon-left mb-3 delete-application-form" data-bs-toggle="modal" data-bs-target="#delete-profile-modal" title="{{ __('Delete CV') }}"><i class="fa fa-trash"></i> <span class="d-none d-xl-inline">{{ __('Delete CV') }}</span></a>
                            </span>
                </div>
            </div>

        @endif

    </div>
</div>



