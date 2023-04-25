<div class="modal fade text-left" id="show-archive-relatives-modal" tabindex="-1" aria-labelledby="show-archive-relatives-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title white" id="show-archive-relatives-modal-label">{{ __('Archive relatives') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" id="archive-relatives-row">
                    @foreach($user->relatives->where('is_archive', true)->sortByDesc('created_at') as $relative)
                        <div class="col-xl-6 col-md-6 col-sm-12 h-100" data-relative-id="{{ $relative->id }}">
                            <div class="card card-relative {{ $relative->is_beneficiary ? 'border-success' : 'border-secondary' }}">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <div class="row">
                                                    <div class="col-12">
                                                <span class="clickable restore-relative-card"
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
                                                      data-passport_date_issue="{{ $relative->passport_date_issue ?? null }}"
                                                      >
                                                    <i class="fa fa-arrow-rotate-left text-success"></i>
                                                    {{ __('restore') }}
                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-10">
                                                {{ $relative->type->displayName }}
                                                @if($relative->is_beneficiary)
                                                    ({{ __('Beneficiary') }})
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <b>{{ __('Full name') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->full_name }}</u>
                                </div>
                                <div class="card-body">
                                    <b>{{ __('Birthday') }}:</b> <u class="card-number" data-id="{{ $relative->id }}">{{ $relative->date_birth }}</u>
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

                    <form action="" id="restore-relative">
                        @csrf
                        @method('PATCH')
                        <input hidden name="id" id="relative-restore-id" value="">
                    </form>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts-body')
    <script>
        $(document).on('click', '.restore-relative-card', function (e) {
            $('#relative-restore-id').val($(this).data('id'))

            let restoreForm = document.getElementById('restore-relative');
            let fd = new FormData(restoreForm);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/restoreRelative/' + $(this).data('id'),
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
