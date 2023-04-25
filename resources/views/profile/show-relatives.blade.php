<div class="tab-pane fade px-0 py-0" id="relatives" role="tabpanel"
     aria-labelledby="relatives-tab">
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col-lg-12">
            <div class="card h-100 w-100">
                <div class="card-header">
                    @if(auth()->user()->can('create', [\App\Models\UserRelative::class, $user]))

                        <a class="btn btn-outline-success icon icon-left" id="btn-relative-add" data-bs-toggle="modal" data-bs-target="#create-relative-modal"><i class="fa-solid fa-plus"></i> {{ __('Add new relative') }}</a>

                    @endif

                    @if($user->relatives->where('is_archive', true)->count() > 0)
                        @if(auth()->user()->can('list', \App\Models\UserRelative::class))
                            <a class="btn btn-outline-light icon icon-left" id="btn-relative-show-archive" data-bs-toggle="modal" data-bs-target="#show-archive-relatives-modal"><i class="fa-solid fa-box-archive"></i> {{ __('Show archive relatives') }}</a>
                        @endif
                    @endif

                </div>
                <div class="card-body">
                    <div class="row" id="relatives-row">
                        @foreach($user->relatives->where('is_archive', false)->sortByDesc('is_beneficiary') as $key=>$relative)
                            <div class="col-xl-6 col-md-6 col-sm-12 h-100" data-relative-id="{{ $relative->id }}">
                                <div class="card card-relative {{ $relative->is_beneficiary ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <div class="row">
                                                <div class="col-10">
                                                    {{ $relative->type->displayName }}
                                                    @if($relative->is_beneficiary)
                                                        ({{ __('Beneficiary') }})
                                                    @endif
                                                </div>
                                                @if(auth()->user()->can('create', [\App\Models\UserRelative::class, $user]))
                                                <div class="col-1">
                                                    <i class="fa fa-edit text-light clickable edit-relative"
                                                       data-id="{{ $relative->id }}"
                                                       data-type_id="{{ $relative->type->id }}"
                                                       data-full_name="{{ $relative->full_name }}"
                                                       data-is_beneficiary="{{ $relative->is_beneficiary }}"
                                                       data-date_birth="{{ $relative->date_birth ? date('d.m.Y', strtotime($relative->date_birth)) : null}}"
                                                       data-home_phone="{{ $relative->home_phone }}"
                                                       data-mobile_phone="{{ $relative->mobile_phone }}"
                                                       data-email="{{ $relative->email }}"
                                                       data-zip_code="{{ $relative->zip_code }}"
                                                       data-country="{{ $relative->country }}"
                                                       data-city="{{ $relative->city }}"
                                                       data-street="{{ $relative->street }}"
                                                       data-building="{{ $relative->building }}"
                                                       data-apartment="{{ $relative->apartment }}"

                                                       data-passport_series="{{ $relative->passport_series ?? null }}"
                                                       data-passport_number="{{ $relative->passport_number ?? null }}"
                                                       data-passport_place="{{ $relative->passport_place ?? null }}"
                                                       data-passport_date_issue="{{ $relative->passport_date_issue?->format('d.m.Y') ?? null }}"




                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit-relative-modal"
                                                    ></i>
                                                </div>
                                                <div class="col-1">
                                                    <i class="fa fa-trash-can text-danger clickable delete-relative"
                                                       data-id="{{ $relative->id }}"
                                                       data-type_id="{{ $relative->type->id }}"
                                                       data-full_name="{{ $relative->full_name }}"

                                                       data-bs-toggle="modal"
                                                       data-bs-target="#confirmDelete-relative-modal"
                                                    ></i>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <b>{{ __('Full name') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->full_name }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Birthday') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->date_birth?->format('d.m.Y') }}</u>
                                    </div>

                                    <div class="card-body">
                                        <b>{{ __('Address') }}:</b>
                                        <u class="card-number" data-id="{{ $relative->id }}">
                                            {{ $relative->fullAddress }}
                                        </u>
                                    </div>

                                    @if($relative->is_beneficiary)
                                        <div class="card-body">
                                            <b>{{ __('Passport series & number') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->passportSeriesNumber }}</u>
                                        </div>
                                        <div class="card-body">
                                            <b>{{ __('Passport issued') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->passportIssued }}</u>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        <b>{{ __('Home phone') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->home_phone }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Mobile phone') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->mobile_phone }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Email') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->email }}</u>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Confirm delete modal -->
    <div class="modal fade text-left" id="confirmDelete-relative-modal" tabindex="-1" aria-labelledby="confirmDeleteRelativeLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="confirmDeleteRelativeLabel">{{ __("Confirm your action") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-delete-relative" method="POST">
                        @method('DELETE')
                        @csrf
                        <input name="id" id="delete-relative-id" hidden>
                    </form>
                    <p>
                        {{ __("Archive this relative?") }}
                    </p>

                    <p>
                        {{ __('Full name') }}: <b id="confirm-relative-full-name"> </b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger ml-1" data-id="" id="btn-delete-relative-confirm">
                        {{ __("Confirm") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile.relatives.create')
@include('profile.relatives.edit')
@include('profile.relatives.archive')


@push('scripts-body')
    <script>
        $(document).on('click', '.edit-relative', function (e){
            if ($(this).data('is_beneficiary')) {
                $('#relative-edit-is_beneficiary').prop('checked', true).trigger('change');
            } else {
                $('#relative-edit-is_beneficiary').prop('checked', false).trigger('change');

            }
            $('#relative-edit-id').val($(this).data('id'))
            $('#relative-edit-relative_type_id').val($(this).data('type_id'))
            $('#relative-edit-full_name').val($(this).data('full_name'))
            $('#relative-edit-date_birth').val($(this).data('date_birth'))
            let address = ($(this).data('zip_code') ?  $(this).data('zip_code') + ', ' : '')
                + $(this).data('country') +
                ( $(this).data('region') ? ', ' + $(this).data('region') : '')
                + ', ' + $(this).data('city') +
                ', ' + $(this).data('street') + ', ' + $(this).data('building') +
                ($(this).data('apartment') ? ', ' + $(this).data('apartment') : '') ;
            $('#relative-edit-address').val(address)

            $('#relative-edit-zip_code').val($(this).data('zip_code'))
            $('#relative-edit-country').val($(this).data('country'))
            $('#relative-edit-region').val($(this).data('region'))
            $('#relative-edit-city').val($(this).data('city'))
            $('#relative-edit-street').val($(this).data('street'))
            $('#relative-edit-building').val($(this).data('building'))
            $('#relative-edit-apartment').val($(this).data('apartment'))

            $('#relative-edit-home_phone').val($(this).data('home_phone'))
            $('#relative-edit-mobile_phone').val($(this).data('mobile_phone'))
            $('#relative-edit-email').val($(this).data('email'))

            $('#relative-edit-passport_series').val($(this).data('passport_series'))
            $('#relative-edit-passport_number').val($(this).data('passport_number'))
            $('#relative-edit-passport_place').val($(this).data('passport_place'))
            $('#relative-edit-passport_date_issue').val($(this).data('passport_date_issue'))

            $('#relative-edit-agreement').prop('checked', 'checked');




        });


        $(document).on('click', '.delete-relative', function (e) {
            $('#confirm-relative-full-name').text($(this).data('full_name'))
            $('#btn-delete-relative-confirm').data('id', $(this).data('id'))


        });

        $(document).on('click', '#btn-delete-relative-confirm', function (e) {
            let deleteForm = document.getElementById('form-delete-relative');
            let fd = new FormData(deleteForm);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/archiveRelative/' + $(this).data('id'),
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    window.location.reload()
                }
            })
        })
    </script>
@endpush
