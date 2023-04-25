@extends('layouts.app')

@section('content')
    @if ( Str::contains(Request::url(), '/seamen') || ($user->is_applicant && ($user->is_draft or auth()->user()->can('changeApplicantStatus', $user))))
        @include('seamen.applicants.application-edit-form')
    @endif
    <form method="POST" id="partner-form-delete-photo" action=" {{ route('partners.deletePhoto', ['user' => $user->slug] ) }}">
        @method('DELETE')
        @csrf
    </form>
    <section class="section" id="profileInfoSection">
        <div class="row">
            <div class="col">
                @yield('breadcrumb')
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="media d-flex align-items-top">
                            <div class="employee-photo d-none d-md-block">

                                <div class="row justify-content-center">
                                    @if($user->sex == 'Female')
                                        <img class="img-fluid" id="avatar"
                                             src="{{ asset('assets/images/avatar/employee_female.png') }}"
                                             alt="employee photo">
                                    @else
                                        <img class="img-fluid" id="avatar"
                                             src="{{ asset('assets/images/avatar/employee_male.png') }}"
                                             alt="employee photo">
                                    @endif
                                </div>
                                @include('profile.layouts.profile-action-buttons')

                                @if ($user->employee_type === config('enums.employee_type.partners'))
                                    @can('update', App\Models\Contractor::class)
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-xl-2 col-md-2 me-3 mb-3">
                                            <a class="btn btn-outline-success icon edit-partner-form" onclick="$('#btn-employees-responsible-add').hide();$('#btn-employees-director-add').hide()"
                                               data-user-id="{{$user->id}}"
                                               id="editPartnerButton"
                                               title="{{ __('Edit') }}"><i class="fa-solid fa-pen"></i></a>
                                        </div>
                                    </div>
                                    @endcan
                                @endif
                            </div>
                            <div class="name flex-grow-1">
                                <div class="row">
                                    <div class="col d-md-none d-block">
                                        @if($user->sex == 'Female')
                                            <img class="img-fluid" id="avatar-sm"
                                                 src="{{ asset('assets/images/avatar/employee_female.png') }}"
                                                 alt="employee photo">
                                        @else
                                            <img class="img-fluid" id="avatar-sm"
                                                 src="{{ asset('assets/images/avatar/employee_male.png') }}"
                                                 alt="employee photo">
                                        @endif
                                    </div>
                                    <div class="col-9 col-sm-7" id="profileNameRow">
                                        <h4 class="" >
                                            <span id="full-user-name"></span>
                                            @if($user->formattedLastSeen !== '-')
                                                <span class="badge bg-primary mb-1 mt-2">{{ __('Last login: ') }} {{ $user->formattedLastSeen }}</span>
                                            @endif
                                        </h4>
                                    </div>

                                    <div class="col">
                                        <div class="d-flex justify-content-end">
                                            <div class="" id="action-btns-sm">

                                                @push('scripts-body')
                                                    <script>
                                                        function getViewport () {
                                                            // https://stackoverflow.com/a/8876069
                                                            const width = Math.max(
                                                                document.documentElement.clientWidth,
                                                                window.innerWidth || 0
                                                            )
                                                            if (width <= 576) return 'xs'
                                                            if (width <= 768) return 'sm'
                                                            if (width <= 992) return 'md'
                                                            if (width <= 1200) return 'lg'
                                                            return 'xl'
                                                        }

                                                        $(document).ready(function () {
                                                            let viewport = getViewport()
                                                            let debounce
                                                            $(window).resize(() => {
                                                                debounce = setTimeout(() => {
                                                                    const currentViewport = getViewport()
                                                                    if (currentViewport !== viewport) {
                                                                        viewport = currentViewport
                                                                        $(window).trigger('newViewport', viewport)
                                                                    }
                                                                }, 500)
                                                            })
                                                            $(window).on('newViewport', (viewport) => {
                                                                let vp = getViewport()
                                                                if (vp === 'xs' || vp === 'sm') {
                                                                    $('#action-btns-sm').after($('#profile-action-buttons'));

                                                                } else {
                                                                    if ($('#collapseProfile').is(':visible')) {
                                                                        $('#avatar').parent().after($('#profile-action-buttons'));

                                                                    } else {
                                                                        $('#btnCollapseProfile').before($('#profile-action-buttons'))

                                                                    }
                                                                }
                                                            })
                                                            // run when page loads
                                                            $(window).trigger('newViewport', viewport)
                                                        });
                                                    </script>
                                                @endpush
                                            </div>

                                            <button class="btn btn-light-secondary icon" type="button" id="btnCollapseProfile" data-bs-toggle="collapse" data-bs-target="#collapseProfile" aria-expanded="false" aria-controls="collapseProfile">
                                                <i class="fa-solid fa-chevron-down" id="communications-chevron-down" style="display: none;"></i>
                                                <i class="fa-solid fa-chevron-up" id="communications-chevron-up" ></i>

                                            </button>
                                            @push('scripts-body')
                                                <script>

                                                    let collapsibleProfile = document.getElementById('collapseProfile')
                                                    collapsibleProfile.addEventListener('hide.bs.collapse', function () {
                                                        $('#communications-chevron-up').hide();
                                                        $('#communications-chevron-down').show();
                                                        let viewPort = getViewport();
                                                        if (viewPort !== 'xs' && viewPort !== 'sm' ) {
                                                            $('#avatar').parent().hide();

                                                            $('#btnCollapseProfile').before($('#profile-action-buttons'))

                                                        }


                                                    })

                                                    collapsibleProfile.addEventListener('show.bs.collapse', function () {
                                                        $('#communications-chevron-up').show();
                                                        $('#communications-chevron-down').hide();

                                                        let viewPort = getViewport();
                                                        if (viewPort !== 'xs' && viewPort !== 'sm' ) {
                                                            let avatar_elem = $('#avatar');
                                                            avatar_elem.parent().after($('#profile-action-buttons')).show();
                                                        }

                                                    })
                                                </script>
                                            @endpush
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row collapse show" id="collapseProfile">
                                    <div class="col">
                                        @yield('profile_info')
                                        <div id="profile-info-contacts">
                                            <div class="divider"></div>
                                            @if ($user->employee_type === config('enums.employee_type.partners'))
                                                <div class="row mb-0">
                                                    <div class="col-sm-12">
                                                        {{ __("Contractor") }}:
                                                        <td>
                                                            <a href="{{ $user->infoPartner?->contractor ? route('contractors.show', ['contractor' => $user->infoPartner?->contractor]) : '#'}}">
                                                                {{ $user->infoPartner?->contractor?->realName }}
                                                            </a>
                                                        </td>
                                                    </div>
                                                </div>
                                                <div class="row mb-0">
                                                    <div class="col-sm-12">
                                                        {{ __("Position") }}: <a id="position_partner">{{ $user->infoPartner?->position }}</a>
                                                    </div>

                                                </div>
                                                <div class="divider"></div>
                                            @endif


                                            <span class='text'><b> {{ __("Communications")}} </b></span>
                                            <div class="card-content">
                                                <div class="row mb-0">
                                                    <div class="col-sm-12">
                                                        {{ __("Email") }}: <u id="user-email"></u>
                                                    </div>
                                                </div>

                                                @if (! $user->is_applicant && $user->employee_type !== config('enums.employee_type.partners'))
                                                    @if (! $user->is_applicant)
                                                        <div class="row mb-0">
                                                            <div class="col-sm-12">
                                                                {{ __("Skype") }}: <u id="user-skype-login"></u>
                                                            </div>

                                                        </div>
                                                    @endif

                                                    <div class="row mb-0">
                                                        <div class="col-sm-12">
                                                            {{ __("Internal phone") }}: <u id="user-internal-phone"></u>
                                                        </div>

                                                    </div>
                                                @endif

                                                <div class="row mb-0">
                                                    <div class="col-sm-12">
                                                        {{ __("Mobile phone") }}: <a id="user-phone"></a>
                                                    </div>

                                                </div>

                                                <br>
                                                @if(count($user->extraContacts))
                                                    <div class="row mb-0">
                                                        <div class="col-sm-12">

                                                            <a class="btn icon btn-outline-primary"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#extraContactsModal">
                                                                <i class="fa-solid fa-address-card"></i>
                                                                {{ __('Show extra contacts') }}
                                                            </a>

                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                            </div>

                        </div>


                    </div>

                    <div class="card-body py-0">
                        <div class="row">
                            @yield('nav-tabs')
                            <i class='bx bx-check font-medium-5 pl-25 pr-75'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="tab-content" id="myTabContent">
        @yield('tab-content')
    </div>

    <div class="modal fade text-left" id="extraContactsModal" tabindex="-1" aria-labelledby="myModalLabel130"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title " id="myModalLabel130">{{ __('Extra contacts') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-0">
                        <div class="col-sm-12">
                            <i class="fa-solid fa-check text-success"></i>
                            {{ __("Email") }}: <a href="mailto: {{ $user->email }} ">{{ $user->email }}</a>
                        </div>

                    </div>

                    @if (! $user->is_applicant)
                        <div class="row mb-0">
                            <div class="col-sm-12">
                                <i class="fa-solid fa-check text-success"></i>
                                {{ __("Internal phone") }}: <u>{{ $user->internal_phone }}</u>
                            </div>

                        </div>
                    @endif

                    <div class="row mb-0">
                        <div class="col-sm-12">
                            <i class="fa-solid fa-check text-success"></i>
                            {{ __("Mobile phone") }}: <a
                                href="tel:{{$user->phone}}">{{ $user->phone ? "+$user->phone" : $user->phone}}</a>
                        </div>

                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-12">
                            <i class="fa-solid fa-check text-success"></i>
                            {{ __("Skype") }}: <u>{{ $user->skype_login }}</u>
                        </div>

                    </div>
                    <div class="divider">
                        <div class="divider-text">
                            {{ __('Extra contacts') }}
                        </div>
                    </div>
                    @foreach($user->extraContacts as $contact)
                        <div class="row mb-0">
                            <div class="col-sm-12">
                                @if($contact->type === 'PHONE')
                                    {{ \App\Enums\ContactType::fromKey(Str::upper($contact->type)) }}: <a
                                        href="tel:{{"+$contact->contact"}}">{{"+$contact->contact"}}</a>
                                @else
                                    {{ \App\Enums\ContactType::fromKey(Str::upper($contact->type)) }}:
                                    <u>{{$contact->contact}}</u>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <span>{{ __('Close') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('profile.modals.confirm-archive')
    @include('profile.modals.confirm-to-candidates')
    @include('profile.modals.confirm-to-precaution')
    @include('profile.modals.confirm-to-crew')
    @include('profile.modals.confirm-to-office')
    @include('profile.modals.confirm-to-applicants')
    @include('profile.modals.confirm-double-profile')
    @include('employees.partners.edit')
    @include('contractors.commerce-fields.create')

    @if($user->is_draft && auth()->user()->id == $user->id)
        @include('seamen.applicants.delete-profile-modal')

        <div class="modal fade text-left" id="applicationSentModal" tabindex="-1" aria-labelledby="applicationSentLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title white" id="applicationSentLabel">{{ __('Application was sent') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __("Your application was sent.") }}
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-success ml-1" data-bs-dismiss="modal"
                           href="{{ route('seamen.show', ['seaman' => $user->slug]) }}">
                            <i class="d-block d-sm-none fa-solid fa-check"></i>
                            <span class="d-none d-sm-block">{{ __("Ok") }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->can('changeApplicantStatus', $user))
        <div class="modal fade text-left" id="sendApplicationBackModal" tabindex="-1"
             aria-labelledby="sendApplicationBackModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <form method="POST" action="{{ route('applicants.sendBackToApplicant', ['user' => $user->slug]) }}"
                      id="sendApplicationBackForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title white"
                                id="sendApplicationBackModalLabel">{{ __('Send CV back to the applicant') }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ __('If you need applicant to edit/add some data to their CV, please leave a comment and send this form.') }}

                            <div class="form-group">
                                <label for="sendApplicationBackComment"> {{ __('Comment') }}: </label>
                                <textarea type="text" class="form-control" name="comment"
                                          id="sendApplicationBackComment" required></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <span>{{ __('Cancel') }}</span>
                            </button>

                            <button type="submit" class="btn btn-warning ml-1">
                                <span>{{ __('Send CV back') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="modal fade text-left" id="moveApplicantToCandidateModal" tabindex="-1"
             aria-labelledby="moveApplicantToCandidateModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <form method="POST" action="{{ route('applicants.moveToCandidates', ['user' => $user->slug]) }}"
                      id="moveApplicantToCandidateForm">
                    @csrf
                    @method('PATCH')

                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title white"
                                id="moveApplicantToCandidateModalLabelLabel">{{ __('Approve Applicants CV & transfer to Candidates') }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><b> {{ __('You are going to approve this applicant & transfer their CV to Candidates') }}
                                    : </b></p>

                            <p> {{ __('Applicant') }}: <b>{{ $user->displayName }}</b></p>
                            <p> {{ __('Age') }}: <b>{{ $user->age }}</b></p>
                            <p> {{ __('Rank') }}: <b>{{ $user->rank?->displayName }}</b></p>
                            <p> {{ __('Email') }}: <a href="mailto: {{ $user->email }} ">{{ $user->email }}</a></p>
                            <p> {{ __('Phone') }}: <b>{{ $user->phone }}</b></p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <span>{{ __('Cancel') }}</span>
                            </button>

                            <button type="submit" class="btn btn-success ml-1">
                                <span>{{ __('Continue') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="modal fade text-left" id="acceptToCrewModal" tabindex="-1" aria-labelledby="acceptToCrewModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="dialog">
                <form method="POST" action="{{ route('seamen.acceptToCrew', ['seaman' => $user->slug]) }}"
                      id="acceptToCrewForm">
                    <input hidden name="id" value="{{ $user->id }}">
                    <input hidden name="employee_type" value="{{ config('enums.employee_type.seaman_crew') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title white"
                                id="acceptToCrewModalLabel">{{ __('Accept the Candidate for the Crew') }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ __('You are going to accept') }}
                            <b>{{ $user->displayName }} </b> {{ __('for the Crew') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <span>{{ __('Cancel') }}</span>
                            </button>

                            <button type="submit" class="btn btn-success ml-1">
                                <span>{{ __('Proceed') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    <div class="modal" id="historyOfStatusesModal" tabindex="-1" aria-labelledby="historyOfStatusesModalLabel"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="historyOfStatusesModalLabel">{{ __('Comments to CV') }}</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    @foreach($user->applicationChanges->sortByDesc('created_at') as $change)

                        @if($change->old_status > $change->new_status)
                            <div class="divider">
                                <div class="divider-text">
                                    {{ $change->created_at->format('d.m.Y H:i') }} <span
                                        class="badge bg-info mb-3">{{ $change->oldStatusName }}</span> <i
                                        class="fa-solid fa-arrow-right"></i> <span
                                        class="badge bg-light mb-3">{{ $change->newStatusName }}</span>
                                </div>
                            </div>
                            <p>
                                <b> {{ __('HR department comment') }}:</b> {{ $change->hr_comment}}
                            </p>
                        @else
                            <div class="divider">
                                <div class="divider-text">
                                    {{ $change->created_at->format('d.m.Y H:i') }} <span
                                        class="badge bg-light mb-3">{{ $change->oldStatusName }}</span> <i
                                        class="fa-solid fa-arrow-right"></i> <span
                                        class="badge bg-info mb-3">{{ $change->newStatusName }}</span>
                                </div>
                            </div>
                            <p>
                                <b> {{ __('Applicant comment') }}:</b> {{ $change->applicant_comment}}
                            </p>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <span>{{ __('Close') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('styles')
    <style>
        .list-group-item {
            border: 0px;
        }

        .row {
            margin-bottom: 1rem;
        }
    </style>
@endpush

@push('scripts-body')
    @if($errors->isNotEmpty())
        <script>
            $('#application-form').show();
            $('#application-progress').show();

            $('#myTabContent').hide();
            $('#profileInfoSection').hide();

            $(document).ready(function () {

            })
        </script>
    @endif
    <script>
        $('.show-application-form').on('click', function (e) {
            $('#application-form').show();
            $('#application-progress').show();

            $('#myTabContent').hide();
            $('#profileInfoSection').hide();
        })
    </script>

    <script src="{{ asset('js/fontawesome.js') }}"></script>
    <script>
        $(document).ready(function () {
            let url = location.href.replace(/\/$/, "");

            $(function () {
                var hash = window.location.hash;
                if (hash.split('#').length > 2) {
                    let hashes = hash.split('#')
                    $('ul.nav a[href="#' + hashes[1] + '"]').tab('show');
                    $('a.navigation-link[href="' + hash + '"]').trigger('click');

                } else {
                    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
                }

            });


            $('a[data-bs-toggle="tab"]').on('show.bs.tab', function (e) {
                let newUrl;
                const hash = $(this).attr("href");
                if (hash == "#profile") {
                    newUrl = url.split("#")[0];
                } else {
                    newUrl = url.split("#")[0] + hash;
                }
                history.replaceState(null, null, newUrl);
            });


            $('a.navigation-link').on('click', function (e) {
                let newUrl;
                const hash = $(this).attr("href");
                newUrl = url.split("#")[0] + hash;
                history.replaceState(null, null, newUrl);
            });


        });
    </script>

    <script>
        let user_id = $("#edit-user_id").val();
        updateEmployee(user_id);

        function updateEmployee(user_id) {

            console.log('updating employee')
            $.ajax({
                url: "/users/toJson/" + user_id,
                method: 'get',
                dataType: 'json',
                success: function (data) {

                    if (data['photo']) {
                        $('#avatar').attr('src', "data:image/png;base64," + data['photo'])
                        $('#avatar-sm').attr('src', "data:image/png;base64," + data['photo'])
                        $('#applicant-photo').attr('src', "data:image/png;base64," + data['photo'])

                    }

                    $('#full-user-name').text(data['fullNameEn'] + " | " + data['fullNameRu'])
                    $('#user-email').html('<a href="mailto: ' + data['email'] + '">' + data['email'] + '</a>')
                    $('#user-internal-phone').text(data['internal_phone'])
                    data['phone'] == null ? $('#user-phone').text('') : $('#user-phone').text("+" + data['phone']).attr('href', "tel:" + "+" + data['phone'])
                    $('#user-skype-login').text(data['skype_login'])
                    $('#position_partner').text(data['position_partner'])
                },
                error: function (err) {
                    if (err.status === 401) {
                        window.location.reload();
                    }
                }
            });
        }

        $('#edit-item').on('click', function (e) {
            e.preventDefault();
            $('#employee-form-method').val('PATCH');
            $('#header-create-employee').hide();
            $('#header-edit-employee').show();

            $('#btn-create-save').hide();
            $('#btn-create-save-open-docs').hide();

            $('#btn-save').show();
            $('#header-edit-employee').show();
            $('#header-сreate-employee').hide();

            $('#createUserForm').attr('id', 'editUserForm');
            $('select[form="createUserForm"]').attr('form', 'editUserForm');
            $('input[form="createUserForm"]').attr('form', 'editUserForm');
        });
    </script>


    <script>
        function getFullCVInfo() {
            let url = "{{ route('seamen.export_full_cv', ['user' => $user->slug, 'lastTenYears' => 'lastTenYearsValue']) }}"
            console.log(url)
            url = url.replace("lastTenYearsValue", +document.getElementById("lastTenYears").checked);
            console.log(url)
            window.open(url)
        }
    </script>

    <script>
        function getShortCVInfo() {
            let url = "{{ route('seamen.export_short_cv', ['user' => $user->slug, 'lastTenYears' => 'lastTenYearsValue']) }}"
            console.log(url)
            url = url.replace("lastTenYearsValue", +document.getElementById("lastTenYears").checked);
            console.log(url)
            window.open(url)
        }
    </script>

    <script>
    $('#editPartnerButton').on('click', function (e) {
        $('#edit-partner-form').show();
        $('#btn-create-save').show();
        $('#btn-create-save-open-docs').show();

        $('#editPartnerButton').hide();
        $('#createContractorButton').hide();
        $('#partnerArchive').hide();
        $('#myTabContent').hide();
        $('#partnerInfoSection').hide()
        $('#profileInfoSection').hide()
    })

    $('#partner-edit-close-btn').on('click', function (e){
        $('#edit-partner-form').hide();
        $('#btn-create-save').hide();
        $('#btn-create-save-open-docs').hide();


        $('#createContractorButton').show()
        $('#editPartnerButton').show();
        $('#partnerArchive').show();
        $('#myTabContent').show();
        $('#partnerInfoSection').show()
        $('#profileInfoSection').show()
    })

    $('#cancel-edit-partner').on('click', function (e){
        $('#edit-partner-form').hide();
        $('#btn-create-save').hide();
        $('#btn-create-save-open-docs').hide();

        $('#createContractorButton').show()
        $('#editPartnerButton').show();
        $('#partnerArchive').show();
        $('#myTabContent').show();
        $('#partnerInfoSection').show()
        $('#profileInfoSection').show()
    })

    $('#createContractorButton').on('click', function (e){
        e.preventDefault();
        $('#contractor-form-method').val('POST');
        $('#btn-create-save').show();
        $('#btn-create-save-open-docs').show();

        $('#btn-save').hide();
        $('#header-edit-employee').hide();
    });

    $('.show-contractor-create-form').on('click', function (e){
        $('#contractor-create-form').show();
        $('#application-progress').show();

        $('#editPartnerButton').hide();
        $('#createContractorButton').hide();
        $('#partnerArchive').hide();
        $('#myTabContent').hide();
        $('#partnerInfoSection').hide()
        $('#profileInfoSection').hide()
    })

    $('.contractor-create-close-btn').on('click', function (e){
        console.log(1)
        $('#contractor-create-form').hide();
        $('#application-progress').hide();

        $('#editPartnerButton').show();
        $('#createContractorButton').show();
        $('#partnerArchive').show();
        $('#myTabContent').show();
        $('#partnerInfoSection').show();
        $('#profileInfoSection').show();
    })

    $('.cancel-create-contractor').on('click', function (e){
        $('#contractor-create-form').hide();
        $('#application-progress').hide();

        $('#editPartnerButton').show();
        $('#createContractorButton').show();
        $('#partnerArchive').show();
        $('#myTabContent').show();
        $('#partnerInfoSection').show();
        $('#profileInfoSection').show();
    })


    </script>

@endpush
