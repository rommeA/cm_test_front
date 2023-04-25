<div class="modal fade text-left" id="show-archive-bank-card-modal" tabindex="-1" aria-labelledby="show-archive-cards-modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title white" id="show-archive-cards-modal-label">{{ __('Archive bank cards') }} </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" id="archive-bank-cards-row">
                    @foreach($user->bankCards->where('is_archive', true)->sortByDesc('date_issue') as $key=>$card)
                        <div class="col-xl-3 col-md-6 col-sm-12 h-100">
                            <div class="card card-bank-card border-dark">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="clickable restore-card"
                                                    data-id="{{ $card->id }}"
                                                    data-number="{{ $card->number }}"
                                                    data-date_issue="{{ date('d.m.Y', strtotime($card->date_issue)) }}"
                                                    data-date_valid="{{ date('d.m.Y', strtotime($card->date_valid)) }}"
                                                    data-company_id="{{ $card->company_id }}"
                                                    data-currency_id="{{ $card->currency_id }}"
                                                    data-currency_display_name="{{ $card->currencyDisplayName }}"
                                                    data-comment="{{ $card->comment }}"
                                                    data-is_archive="{{ $card->is_archive }}">
                                                    <i class="fa fa-arrow-rotate-left text-success"></i>
                                                    {{ __('restore') }}
                                                </span>
                                            </div>
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
                                    <b>{{ __('Date issue') }}:</b> <u class="card-date_issue" data-id="{{ $card->id }}">{{ date('d.m.Y', strtotime($card->date_issue)) }}</u>
                                </div>
                                <div class="card-body">
                                    <b>{{ __('Date valid') }}:</b> <u class="card-date_valid" data-id="{{ $card->id }}">{{ date('d.m.Y', strtotime($card->date_valid)) }}</u>
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

                    <form action="" id="restore-bank-card">
                        @csrf
                        @method('PATCH')
                        <input hidden name="id" id="bank-card-restore-id" value="">
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
        $(document).on('click', '.restore-card', function (e) {
            $('#bank-card-restore-id').val($(this).data('id'))

            let restoreForm = document.getElementById('restore-bank-card');
            let fd = new FormData(restoreForm);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/restoreBankCard/' + $(this).data('id'),
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
