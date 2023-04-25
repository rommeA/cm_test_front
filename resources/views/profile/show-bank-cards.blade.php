<div class="tab-pane fade px-0 py-0" id="bank-cards" role="tabpanel"
     aria-labelledby="bank-cards-tab">
    <div class="row ">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header">
                    @can('create', \App\Models\Document::class)

                        <a class="btn btn-outline-success icon icon-left" id="btn-bank-card-add" data-bs-toggle="modal" data-bs-target="#create-bank-card-modal"><i class="fa-solid fa-plus"></i> {{ __('Add new card') }}</a>
                    @endcan

                    @if($user->bankCards->where('is_archive', true)->count() > 0)
                        <a class="btn btn-outline-light icon icon-left" id="btn-bank-card-show-archive" data-bs-toggle="modal" data-bs-target="#show-archive-bank-card-modal"><i class="fa-solid fa-box-archive"></i> {{ __('Show archive cards') }}</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row" id="bank-cards-row">
                        @foreach($user->bankCards->where('is_archive', false)->sortByDesc('date_issue') as $key=>$card)
                            <div class="col-xl-4 col-md-6 col-sm-12 h-100">
                                <div class="card card-bank-card border-{{ $card->statusStyleName }}">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <div class="row">
                                                <div class="col-10">
                                                    {{ __('Bank card') }} № {{$key + 1}}
                                                </div>
                                                @can('update', \App\Models\Document::class)

                                                <div class="col-1">
                                                    <i class="fa fa-edit text-light clickable edit-bank-card"
                                                       data-id="{{ $card->id }}"
                                                       data-number="{{ $card->number }}"
                                                       data-date_issue="{{ $card->date_issue ? date('d.m.Y', strtotime($card->date_issue)) : null }}"
                                                       data-date_valid="{{ $card->date_valid ? date('d.m.Y', strtotime($card->date_valid)) : null}}"
                                                       data-company_id="{{ $card->company_id }}"
                                                       data-currency_id="{{ $card->currency_id }}"
                                                       data-comment="{{ $card->comment }}"
                                                       data-is_archive="{{ $card->is_archive }}"

                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit-bank-card-modal"
                                                    ></i>
                                                </div>
                                                <div class="col-1">
                                                    <i class="fa fa-trash-can text-danger clickable delete-bank-card"
                                                       data-id="{{ $card->id }}"
                                                       data-number="{{ $card->number }}"
                                                       data-date_issue="{{ $card->date_issue ? date('d.m.Y', strtotime($card->date_issue)) : null }}"
                                                       data-date_valid="{{ $card->date_valid ? date('d.m.Y', strtotime($card->date_valid)) : null}}"
                                                       data-company_id="{{ $card->company_id }}"
                                                       data-currency_id="{{ $card->currency_id }}"
                                                       data-currency_display_name="{{ $card->currencyDisplayName }}"
                                                       data-comment="{{ $card->comment }}"
                                                       data-is_archive="{{ $card->is_archive }}"

                                                       data-bs-toggle="modal"
                                                       data-bs-target="#confirmDelete-bankCard-modal"
                                                    ></i>
                                                </div>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <b>{{ __('Number') }}:</b> <u class="card-number" data-id="{{ $card->id }}">{{ $card->number }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ trans_choice('Office', 1) }}:</b> <u class="card-office" data-id="{{ $card->id }}">{{ $card->company?->displayName ?? '' }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Date issue') }}:</b> <u class="card-date_issue" data-id="{{ $card->id }}">{{ $card->date_issue ? date('d.m.Y', strtotime($card->date_issue)) : null}}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Date valid') }}:</b> <u class="card-date_valid" data-id="{{ $card->id }}">{{ $card->date_valid ? date('d.m.Y', strtotime($card->date_valid)) : null }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Currency') }}:</b> <u class="card-currency" data-id="{{ $card->id }}">{{ $card->currency?->displayName ?? '' }}</u>
                                    </div>
                                    <div class="card-body">
                                        <b>{{ __('Comment') }}:</b> <u class="card-comment" data-id="{{ $card->id }}">{{ $card->comment ?? '' }}</u>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 col-sm-6 col-12">
            {{ __("Last update") }}:
            <b>{{ $user->lastChange->updated_at ?? ''}}</b>
            by
            <b>
                @if($user->changedByUserSlug)
                    <a href="{{ route('employees.show', ['employee' => $user->changedByUserSlug]) }}">{{$user->changedByUserName ?? ''}}</a>
                @else
                    <a href="#">Admin</a>
                @endif
            </b>
        </div>
    </div>

    <!--Confirm delete modal -->
    <div class="modal fade text-left" id="confirmDelete-bankCard-modal" tabindex="-1" aria-labelledby="confirmDeleteBCLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="confirmDeleteBCLabel">{{ __("Confirm your action") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-delete-bank-card" method="POST">
                        @method('DELETE')
                        @csrf
                        <input name="id" id="delete-bank-card-id" hidden>
                    </form>
                    <p>
                        {{ __("Archive this bank card?") }}
                    </p>
                    <p>
                        {{ __('Number') }}: <b id="confirm-bank-card-number"></b>
                    </p>
                    <p>
                        {{ __('Currency') }}: <b id="confirm-bank-card-currency"> </b>
                    </p>
                    <p>
                        {{ __('Comment') }}: <b id="confirm-bank-card-comment"> </b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger ml-1" data-id="" id="btn-delete-bank-card-confirm">
                        {{ __("Confirm") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile.bank-cards.edit')
@include('profile.bank-cards.create')
@include('profile.bank-cards.archive')

@push('scripts-body')
    <script>
        $(document).on('click', '.edit-bank-card', function (e){
            $('#bank-card-edit-id').val($(this).data('id'))
            $('#bank-card-edit-number').val($(this).data('number'))
            $('#bank-card-edit-date_issue').val($(this).data('date_issue'))
            $('#bank-card-edit-date_valid').val($(this).data('date_valid'))
            $('#bank-card-edit-comment').val($(this).data('comment'))
            $('#bank-card-edit-currency_id').val($(this).data('currency_id'))
            $('#bank-card-edit-company_id').val($(this).data('company_id'))
        });

        $(document).on('click', '.delete-bank-card', function (e) {
            $('#confirm-bank-card-number').text($(this).data('number'))
            $('#confirm-bank-card-currency').text($(this).data('currencyDisplayName'))
            $('#confirm-bank-card-comment').text($(this).data('comment'))
            $('#btn-delete-bank-card-confirm').data('id', $(this).data('id'))


        });

        $(document).on('click', '#btn-delete-bank-card-confirm', function (e) {
            let deleteForm = document.getElementById('form-delete-bank-card');
            let fd = new FormData(deleteForm);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/archiveBankCard/' + $(this).data('id'),
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
