@extends('profile.index')
@section('title')
    Partners — Crew Master
@endsection
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ trans_choice("Partners", 2) }}</li>
        </ol>
    </nav>
@endsection

@section('table-title')
    <div class="card-title">
        <div class="btn-group">
            <div class="dropdown">
                <h4 type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true">
                    {{ trans_choice('Partners', 2) }} <i class="fa-solid fa-angle-down"></i>
                </h4>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('employees.index') }}">{{ trans_choice('Employees', 2) }}</a>
                    <a class="dropdown-item" href="{{ route('employees.candidates.index') }}">{{ trans_choice('Candidates', 2) }}</a>
                    <a class="dropdown-item" href="{{ route('employees.archive.index') }}">{{ __("Archive Employees") }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('seamen.index') }}">{{ __("Seamen") }}</a>
                    <a class="dropdown-item" href="{{ route('seamen.archive.index') }}">{{ __("Seamen archive") }}</a>
                    <a class="dropdown-item" href="{{ route('seamen.applicants.index') }}">{{ __("Crew applicants") }}</a>
                    <a class="dropdown-item" href="{{ route('seamen.candidates.index') }}">{{ __("Crew candidates") }}</a>
                    <a class="dropdown-item" href="{{ route('seamen.precaution.index') }}">{{ __("Crew precaution") }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@can('create', App\Models\Contractor::class)
@section('buttons')
    <a href="#" class="btn icon icon-left btn-primary show-partner-create-form"  id="createPartnerButton">

        <i data-feather="plus-circle"></i>
        {{ __('Add Partner') }}
    </a>
@endsection

@section('forms')
    @include('employees.partners.create')
    @include('contractors.commerce-fields.create')
@endsection
@endcan

@section('main-table')
    <table class='table table-hover table-sortable table-striped' id="usersTable" style="width: 100%">
        <thead>
            <tr>
                <th class="col-2 fixed"></th>
                <th class="fixed">{{ __('Name') }} </th>

                <th class="d-md-none d-lg-table-cell" data-priority="2">{{ __('Email') }} </th>
                <th class="" data-priority="2">{{ __('Phone') }} </th>
                <th class="" data-priority="2">{{ __('Birthday') }} </th>
                <th class="sorter-false"></th>
                <th></th>
            </tr>
        </thead>

        <tbody class="" id="tableEmployees">

        </tbody>

    </table>
@endsection




@section('datatable-script')
    <script>
        $(document).ready(function() {
            let buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column === 8 || column === 9)
                            {
                                return '';
                            }

                            if (column === 0) {
                                return  data.replace('name="customCheck"', 'name="customCheck" hidden')
                            }

                            return data;
                        }
                    }
                }
            };

            $('#usersTable').on('click', 'tbody td', function() {

                if ($(this).hasClass('details-control') || $(this).hasClass('td-edit-user')) {
                } else {
                    window.location.href = '/partners/' + table.row(this).data().slug;

                }

            })

            /* Formatting function for row details - modify as you need */
            function format (data) {
                let subordinates = ''; //data['subordinates']
                let contacts = ''; //data['phone']
                let birthday = data['date_birth'] ? data['date_birth'] : '';
                if (data['phone']) {
                    contacts += '<div class="row"><div class="col">Phone: <u>'+ data['phone'] +'</u></div></div>';
                }
                if (data['email']) {
                    contacts += '<div class="row"><div class="col">Email: <u>'+ data['email'] +'</u></div></div>';
                }
                if (data['internal_phone']) {
                    contacts += '<div class="row"><div class="col">Internal phone: <u>'+ data['internal_phone'] +'</u></div></div>';
                }
                if (data['skype_login']) {
                    contacts += '<div class="row"><div class="col">Skype: <u>'+ data['skype_login'] +'</u></div></div>';
                }

                if (data['subordinates']) {
                    data['subordinates'].forEach( function ( elem, i ) {
                        subordinates += '<div class="row">' +
                            '<div class="col px-0">' +
                            '<a class="btn icon icon-left" href="/employees/'+elem['id']+'">' +
                            '<i class="fa-solid fa-user"></i> '+ elem['display_name'] +'</a></div></div>';
                    });
                }

                let htmlStr = '<table class="table table-disable-hover table-borderless table-responsive">' +
                    '<tr class="d-flex hidden-table"><th></th>' +

                    '<th class="col-sm td-date_birth">Birthday</th>' +
                    '<th class="col-sm">Contacts</th>' +
                    '</tr>' +
                    '<tbody class="">' +
                    '<tr class="d-flex hidden-table"><td></td>' +

                    '<td class="col-sm td-date_birth"> ' + birthday +' </td>' +
                    '<td class="col-sm"> ' + contacts + ' </td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>';
                return $(htmlStr);
            }
            table = $('#usersTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/datatable/partners",
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 401) {
                            window.location.href = window.location.href;
                        }
                    }
                },

                "order": [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, -1, -2] },
                ],
                "columns": [
                    {
                        orderable: false,
                        data: 'avatar',
                        render: function(data, type, row, meta) {
                            if (row['use_default_avatar']) {
                                return '<div class="avatar bg-primary avatar-lg">' +
                                    '<span class="avatar-content">'+row['initials']+'</span>' +
                                    '</div>'
                            }
                            return '<div class="avatar avatar-lg">' +
                                '<img class="avatar-img" src="' + data + '" alt="employee photo">' +
                                '</div>'
                        },
                    },
                    { data: 'name', class: 'company-row'},
                    { data: 'email', class: 'd-md-none d-lg-table-cell company-row'},
                    { data: 'phone', class: 'd-md-none d-lg-table-cell company-row'},
                    { data: 'date_birth', class: 'd-md-none d-lg-table-cell company-row'},
                    {
                        class: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                    },
                ],
                "stateSave": true,
                "paging": true,
                "dom": 'flrtipB',
                "buttons": [
                    {
                        className: 'dt-export-buttons',
                        extend: 'collection',
                        text: '',
                        buttons: [

                            $.extend( true, {}, buttonCommon, {
                                extend: 'print'
                            } ),
                            {
                                extend: 'excel',
                                text: 'Excel',
                                exportOptions: {
                                    modifier: {
                                        page: 'current'
                                    },
                                    columns: [1,2,3,4,5,6]
                                }
                            },
                            {
                                extend: 'pdf',
                                text: 'PDF',
                                orientation: 'landscape',
                                exportOptions: {
                                    modifier: {
                                        page: 'current'
                                    },
                                    columns: [1,2,3,4,5,6]
                                }
                            },
                        ],
                        messageTop: 'test',
                        key: {
                            key: 'p',
                            altkey: true
                        },
                        fade: true
                    }
                ],

                initComplete: function () {
                    this.api().columns([3]).every( function () {
                        var column = this;
                        var select = $('#selectDepartment')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );

                        $("#selectDepartment>option").each( function(){
                            var $option = $(this);
                            $option.siblings()
                                .filter( function(){ return $(this).val() === $option.val() } )
                                .remove()
                        })
                    } );

                    this.api().columns([4]).every( function () {
                        var column = this;
                        var select = $('#selectOffice')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );

                        $("#selectOffice>option").each( function(){
                            var $option = $(this);
                            $option.siblings()
                                .filter( function(){ return $(this).val() === $option.val() } )
                                .remove()
                        })
                    } );

                    @cannot('update', App\Models\User::class)
                    $('.td-edit-user').hide();
                    @endcannot
                },
            } );

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('#employeesTable tbody').on('click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var idx = detailRows.indexOf(tr.attr('id'));

                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice(idx, 1);
                } else {
                    tr.addClass('details');
                    row.child(format(row.data())).show();

                    // Add to the 'open' array
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            table.on('draw', function () {
                detailRows.forEach(function(id, i) {
                    $('#' + id + ' td.details-control').trigger('click');
                });
            });


            $('.dt-export-buttons')
                .addClass('btn btn-outline-light icon')
                .removeClass('dt-button')
                .prepend($('<i class="fa-solid fa-download"></i>'))
                .find('span').remove();

            $('#export-button').append($('.dt-export-buttons'));

            let state = table.state.loaded();
            if (state) {
                table.columns().eq( 0 ).each( function ( colIdx ) {
                    let colSearch = state.columns[colIdx].search;

                    if ( colSearch.search ) {
                        if ( colIdx == 3 ) {
                            $('#selectDepartment').val( colSearch.search.slice(1,-1).split("\\").join("") );
                        }
                        if ( colIdx == 4 ) {
                            $('#selectOffice').val( colSearch.search.slice(1,-1).split("\\").join("") );
                        }
                    }
                });
            }



            $('#btn-show-archived').on('click', function (e) {
                $('#btn-show-archived').hide();
                $('#btn-show-active').show();
                $('.card-title').toggle();
                table.ajax.url("/datatable/archive-employees").load();
            });

            $('#btn-show-active').on('click', function (e) {
                $('#btn-show-archived').show();
                $('#btn-show-active').hide();
                $('.card-title').toggle();
                table.ajax.url("/datatable/employees").load();
            });

        } );
    </script>
@endsection


@push('scripts-body')
    <script>
        $('#createPartnerButton').on('click', function (e){
            e.preventDefault();
            $('#employee-form-method').val('POST');
            $('#header-create-employee').show();
            $('#btn-create-save').show();
            $('#btn-create-save-open-docs').show();

            $('#btn-save').hide();
            $('#header-edit-employee').hide();
        });
    </script>



    @if($errors->isNotEmpty())
        <script>
            $('#partner-create-form').show();
            $('#application-progress').show();

            $('#mainContent').hide();
            // $('#profileInfoSection').hide();

            $(document).ready(function() {

            })
        </script>
    @endif

    <script>
        $('.show-partner-create-form').on('click', function (e){
            $('#partner-create-form').show();
            $('#application-progress').show();

            $('#mainContent').hide();
        })
    </script>

    <script>
        $('.show-partner-create-form').on('click', function (e){
            $('#partner-create-form').show();
            $('#application-progress').show();

            $('#mainContent').hide();
        })
    </script>
@endpush
