<div class="tab-pane fade px-0 py-0" id="previous-service" role="tabpanel"
     aria-labelledby="previous-service-tab">
    <div class="row ">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-body ">
                    @can('create', \App\Models\User::class)
                        <a class="btn btn-outline-success icon icon-left" id="btn-ps-add" data-bs-toggle="modal" data-bs-target="#edit-ps-modal"><i class="fa-solid fa-plus"></i> {{ __('Add record') }}</a>
                    @endcan
                    <div class="table-responsive nopadding">

                        <table class="table table-hover " id="prev-service-table">

                            <thead>
                                <th>{{ trans_choice('Companies', 1) }}</th>
                                <th>{{ __('Position') }}</th>
                                <th>{{ __('Type') }}</th>

                                <th>{{ __('Date from') }}</th>
                                <th>{{ __('Date to') }}</th>
                                <th class="ps-buttons"></th>


                            </thead>
                            <tbody>

                                @foreach($user->previousService as $position)
                                    <tr data-id="{{ $position->id }}" class="@if(! $position->company_id) table-danger @elseif(! $position->position_id) table-light @endif ">
                                        <td class="ps-company-name company-row" @if($position->company_id) onclick="window.location='{{ route('companies.show', ['company' => $position->company_id]) }}'" @endif>
                                            <div class="text">{{ $position->is_external ? $position->company_name : $position?->company->displayName ?? '' }}</div>
                                        </td>

                                        <td class="ps-position-name">
                                            <div class="text">{{ $position->is_external ? $position->position_name : $position->position?->displayName ?? $position->position_name ?? ''}}</div>
                                        </td>

                                        <td class="ps-type">
                                            <div class="text">{{ $position->is_full_time ?  __('Full-time') : __('Part-time') }}</div>
                                        </td>
                                        <td class="ps-date-from" data-order={{ $position->date_from?->getTimestamp() ?? 999999999999}}>
                                            <div class="text">{{ $position->date_from?->format('d.m.Y') ?? '-'}}</div>
                                        </td>
                                        <td class="ps-date-to" data-order={{ $position->date_to?->getTimestamp() ?? 0 }}>{{ $position->date_to?->format('d.m.Y') ?? '-' }}</td>
                                        <td class="sorter-false ps-buttons">
                                            @can('update', \App\Models\User::class)
                                            <a class="btn icon btn-sm btn-outline-success ps-edit"
                                               data-id="{{ $position->id }}"
                                               data-position-id="{{ $position->position_id }}"
                                               data-position-name="{{ $position->is_external ? $position->position_name : $position->position?->displayName ?? '' }}"
                                               data-position-name-ru="{{ $position->is_external ? $position->position_name_ru : $position->position?->displayName ?? '' }}"

                                               data-company-id="{{ $position->company_id }}"
                                               data-company-name="{{ $position->is_external ? $position->company_name : $position?->company->displayName ?? ''}}"
                                               data-company-name-ru="{{ $position->is_external ? $position->company_name_ru : $position?->company->name_ru ?? ''}}"

                                               data-date-from="{{ $position->date_from?->format('d.m.Y') ?? ''}}"
                                               data-date-to="{{ $position->date_to?->format('d.m.Y') ?? ''}}"
                                               data-is-full-time="{{ $position->is_full_time ? '1' : '0'}}"
                                               data-is-external="{{ $position->is_external }}"

                                               data-bs-toggle="modal" data-bs-target="#edit-ps-modal"><i class="fa-solid fa-pen"></i></a>
                                            <a class="btn icon btn-sm btn-outline-danger ps-delete"
                                               data-id="{{ $position->id }}"
                                               data-position-id="{{ $position->position_id }}"
                                               data-position-name="{{ $position->is_external ? $position->position_name : $position->position?->displayName ?? '' }}"
                                               data-position-name-ru="{{ $position->is_external ? $position->position_name_ru : $position->position?->displayName ?? '' }}"

                                               data-company-id="{{ $position->company_id }}"
                                               data-company-name="{{ $position->is_external ? $position->company_name : $position?->company->displayName ?? ''}}"
                                               data-company-name-ru="{{ $position->is_external ? $position->company_name_ru : $position?->company->name_ru ?? ''}}"

                                               data-date-from="{{ $position->date_from?->format('d.m.Y') ?? ''}}"
                                               data-date-to="{{ $position->date_to?->format('d.m.Y') ?? ''}}"
                                               data-is-full-time="{{ $position->is_full_time ? '1' : '0'}}"
                                               data-is-external="{{ $position->is_external }}"


                                               data-bs-toggle="modal" data-bs-target="#delete-ps-confirm"><i class="fa-solid fa-trash"></i></a>
                                            @endcan
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    </div>


                    <div class="divider">
                        <div class="divider-text">{{ __('Working experience in Company group (years)') }}: {{ $user->innerExperienceYears }}</div>
                    </div>

                    <div id="container" style="width:100%; height:400px;"></div>
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



    @include('profile.edit-prev-service')

    <!--Confirm delete modal -->
    <div class="modal fade text-left" id="delete-ps-confirm" tabindex="-1" aria-labelledby="confirmDeletePSLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="confirmDeletePSLabel">{{ __("Confirm your action") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-delete-ps">
                        @method('DELETE')
                        @csrf
                        <input name="id" id="delete-ps-id" hidden>
                    </form>
                    <p>
                        {{ __("Delete this record of employee's previous service?") }}
                    </p>
                    <p>
                        {{ __('Company') }}: <b id="confirm-ps-company"></b>
                    </p>
                    <p>
                        {{ __('Department') }}: <b id="confirm-ps-department"></b>
                    </p>
                    <p>
                        {{ __('Position') }}: <b id="confirm-ps-position"> </b>
                    </p>
                    <p>
                        {{ __('Dates') }}: <b id="confirm-ps-dates"> </b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger ml-1" id="btn-delete-ps-confirm">
                        {{ __("Confirm") }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts-body')
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/timeline.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/accessibility.js') }}"></script>

    <script>
        let tablePrevService = $('#prev-service-table').DataTable( {
            "searching": false,
            "order": [[3, 'desc'], [4, 'asc']],
            "stateSave": false,
            "paging": false,
            columnDefs: [
                { orderable: false, targets: [0,1,2,5] },

            ]
        } );
    </script>

    <script>
        const psPositionChoices = new Choices(document.getElementById('ps-edit-position_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        const psDepartmentChoices = new Choices(document.getElementById('ps-edit-department_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        const psCompanyChoices = new Choices(document.getElementById('ps-edit-company_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        const psIsFullTimeChoices = new Choices(document.getElementById('ps-edit-is_full_time'), {
            itemSelectText: '',
            searchEnabled: false,
            searchChoices: false,
        });

    </script>

    <script>


        $('#ps-edit-company_id').on('change', function (){
            console.log($(this).val())
            psDepartmentChoices.removeActiveItems();

            let company_id = $(this).val();

            psDepartmentChoices.setChoices(async () => {
                    try {
                        if(company_id == '') {
                            return [];
                        }
                        const items = await fetch("/company/" + company_id + "/departments");

                        return items.json();
                    } catch (err) {
                        console.error(err);
                    }
                },
                'id',
                'displayName',
                true,
            );
        });

        $('#ps-edit-department_id').on('change', function (){
            console.log($(this).val())
            psPositionChoices.removeActiveItems();

            let department_id = $(this).val();

            psPositionChoices.setChoices(async () => {
                    try {
                        if(department_id == '') {
                            return [];
                        }
                        const items = await fetch("/department/" + department_id + "/positions");

                        return items.json();
                    } catch (err) {
                        console.error(err);
                    }
                },
                'id',
                'displayName',
                true,
            );


        });

        $('a.ps-edit').on('click', function (e){
            e.preventDefault()
            $('#ps-form-method').val('PATCH')

            let company_id = $(this).attr('data-company-id');

            $('#ps-edit-id').val($(this).attr('data-id'))
            $("#ps-edit-date_from").val($(this).attr('data-date-from'))
            $("#ps-edit-date_to").val($(this).attr('data-date-to'))

            if($(this).attr('data-is-external')) {
                $('#ps-edit-is-external').prop('checked', true).trigger('change');
                $('#ps-edit-company_name').val($(this).attr('data-company-name'))
                $('#ps-edit-company_name_ru').val($(this).attr('data-company-name-ru'))

                $('#ps-edit-position_name').val($(this).attr('data-position-name'))
                $('#ps-edit-position_name_ru').val($(this).attr('data-position-name-ru'))


            }


            psIsFullTimeChoices.setChoiceByValue($(this).attr('data-is-full-time'))


            psCompanyChoices.setChoiceByValue(company_id);

            $('#ps-edit-company_id').trigger('change');
            psDepartmentChoices.setChoices(
                [
                    { value: $(this).attr('data-department-id'), label: $(this).attr('data-department-name') },

                ],
                'value',
                'label',
                true,
            );
            psDepartmentChoices.setChoiceByValue($(this).attr('data-department-id'));



            $('#ps-edit-department_id').trigger('change');
            psPositionChoices.setChoices(
                [
                    { value: $(this).attr('data-position-id'), label: $(this).attr('data-position-name') },

                ],
                'value',
                'label',
                true,
            );
            psPositionChoices.setChoiceByValue($(this).attr('data-position-id'));

        })

        $('#btn-ps-add').on('click', function (e){
            e.preventDefault()
            $('#ps-form-method').val('POST')
        })

        $('#ps-save-edits').on('click', function (e){
            e.preventDefault()
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.is-valid').removeClass('is-valid');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            let url = $('#ps-edit-id').val() ? '/previousService/'+$('#ps-edit-id').val() : '/previousService';
            let myform = document.getElementById('edit-ps-form');
            let fd = new FormData(myform);
            console.log($('#edit-ps-form').serializeArray())

            let modalID = 'edit-ps-form';

            $.ajax({
                url: url,
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#'+modalID+' :input').filter(function () {
                        return $.trim($(this).val()).length > 0
                    }).addClass('is-valid');

                    $('.modal').modal('hide');
                    window.location.reload();
                },
                error: function (err) {
                    console.log(err)
                    if (err.status === 422) { // when status code is 422, it's a validation issue
                        // console.log(err.responseJSON);
                        // $('#success_message').fadeIn().html(err.responseJSON.message);

                        // you can loop through the errors object and show it to the user
                        // console.warn(err.responseJSON.errors);
                        // display errors on each form field
                        $.each(err.responseJSON.errors, function (input, error) {
                            let [i, index] = input.split('.');
                            let el = $(document).find('[name="'+i+'"]');
                            if (index >= 0) {
                                el = $(document).find('[name="'+i+'['+index+']"]');
                            }
                            el.addClass('is-invalid');
                            el.after($('<div class="invalid-feedback"><i class="bx bx-radio-circle"></i>' +
                                error[0]  +
                                '</div>'));
                        });
                        $('#'+modalID+' :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    }
                }
            });
        })

        let psDateFrom = $("#ps-edit-date_from").val() ?? 'today';
        $(".ps-edit-date_from").datepicker({

            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:+100",
            defaultDate:  psDateFrom,

            beforeShow: function(input, inst) {
                // Handle calendar position before showing it.
                // It's not supported by Datepicker itself (for now) so we need to use its internal variables.
                var calendar = inst.dpDiv;

                // Dirty hack, but we can't do anything without it (for now, in jQuery UI 1.8.20)
                setTimeout(function() {
                    calendar.position({
                        my: 'center top',
                        at: 'center bottom',
                        collision: 'none',
                        of: input
                    });
                }, 2);
            }
        });

        let psDateTo = $("#ps-edit-date_to").val() ?? 'today';
        $(".ps-edit-date_to").datepicker({

            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:+100",
            defaultDate:  psDateTo,
            beforeShow: function(input, inst) {
                // Handle calendar position before showing it.
                // It's not supported by Datepicker itself (for now) so we need to use its internal variables.
                var calendar = inst.dpDiv;

                // Dirty hack, but we can't do anything without it (for now, in jQuery UI 1.8.20)
                setTimeout(function() {
                    calendar.position({
                        my: 'center top',
                        at: 'center bottom',
                        collision: 'none',
                        of: input
                    });
                }, 2);
            }
        });

        $(".ps-delete").on('click', function(e){
            $('#confirm-ps-company').text($(this).data('companyName'))
            $('#confirm-ps-department').text($(this).data('departmentName'))
            $('#confirm-ps-position').text($(this).data('positionName'))
            $('#confirm-ps-dates').text($(this).data('dateFrom') + ' - ' + $(this).data('dateTo'))
            $('#delete-ps-id').val($(this).data('id'))
        })

        $("#btn-delete-ps-confirm").on('click', function (e){
            e.preventDefault();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            let myform = document.getElementById('form-delete-ps');
            let fd = new FormData(myform);
            $.ajax({
                url: '/previousService/'+$('#delete-ps-id').val(),
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    window.location.reload();
                },
                error: function (err) {
                    console.log(err)
                }
            });
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let chartData = [];
            let url =  "/employees/{{$user->slug}}/previousService";
            $.getJSON(url,  function(data) {
                chartData = data;
                const chart = Highcharts.chart('container', {
                    chart: {
                        zoomType: 'x',
                        type: 'timeline'
                    },
                    credits: {
                        // Remove highcharts.com credits link from chart footer.
                        enabled: false,
                    },
                    exporting: {
                        printMaxWidth: 1600,
                        showTable: true
                    },
                    xAxis: {
                        type: 'datetime',
                        visible: false
                    },
                    yAxis: {
                        gridLineWidth: 1,
                        title: null,
                        labels: {
                            enabled: false
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    tooltip: {
                        style: {
                            width: 300
                        }
                    },
                    colors: [
                        '#4185F3',
                        '#427CDD',
                    ],
                    animation: {
                        enabled:  true
                    },
                    series: [{
                        dataLabels: {
                            alternate: true,
                            allowOverlap: false,
                            format: '<span style="color:{point.color}">● </span><span style="font-weight: bold;" > ' +
                                '{point.date_from}</span><br/>{point.label}' +
                                '<br/><span>{point.experience}</span>',
                            filter:  {
                                operator: '<',
                                property: 'index',
                                value: data.length - 1
                            }
                        },
                        marker: {
                            symbol: 'circle'
                        },
                        data: chartData
                    }]
                });
            })


        });
    </script>
@endpush
