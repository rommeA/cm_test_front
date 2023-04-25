@extends('profile.index')
@section('title')
    Employees — Crew Master
@endsection
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ trans_choice("Employees", 2) }}</li>
        </ol>
    </nav>
@endsection

@section('table-title')
    <div class="card-title">
        <div class="btn-group">
            <div class="dropdown">
                <h4 type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true">
                    {{ trans_choice('Employees', 2) }} <i class="fa-solid fa-angle-down"></i>
                </h4>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('partners.index') }}">{{ trans_choice('Partners', 2) }}</a>
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

@section('buttons')
    <a href="#" class="btn icon icon-left btn-primary" data-bs-toggle="modal"
       data-bs-target="#editUserModal" id="createEmployeeButton">

        <i data-feather="plus-circle"></i>
        {{ __('Add Employee') }}
    </a>
@endsection

@section('extra-buttons')
    <a class="btn icon icon-left btn-outline-primary" id="btn-show-archived" type="button" >
        <i class="fa-solid fa-box-archive"></i>
        {{ __("Show archived Employees") }}
    </a>

    <a class="btn icon icon-left btn-outline-primary" id="btn-show-active" type="button" style="display: none;">
        <i class="fa-solid fa-circle-check"></i>
        {{ __("Show active employees") }}
    </a>
@endsection



@section('main-table')
    <table class='table table-hover table-sortable table-striped' id="usersTable" style="width: 100%">
        <thead>
            <tr>
                <th class="col-2 fixed" data-priority="1"></th>
                <th data-priority="1">{{ __('Name') }} </th>
                <th data-priority="1">{{ __('Rank') }}</th>
                <th data-priority="3">
                    <div class="form-group select-filter">
                        <select class="form-select select-filter" id="selectDepartment">
                            <option value="">{{ __('Department') }}</option>
                            @foreach(\App\Models\Department::all() as $dep)
                                <option value="{{$dep->displayName}}">{{$dep->displayName}}</option>

                            @endforeach
                        </select>
                    </div>
                </th>
                <th data-priority="3">
                    <div class="form-group select-filter">
                        <select class="form-select select-filter" id="selectOffice">
                            <option value="">{{ trans_choice('Offices', 1) }}</option>
                            @foreach(\App\Models\Company::where('is_archive', false)->get()->sortBy('displayName') as $dep)
                                <option value="{{$dep->displayName}}">{{$dep->displayName}}</option>

                            @endforeach
                        </select>
                    </div>
                </th>
                <th  data-priority="2">{{ __('Email') }} </th>
                <th  data-priority="2">{{ __('Phone') }} </th>

                <th  data-priority="3">{{ __('Documents Status') }} </th>
                <th  data-priority="3" class="td-edit-user"></th>
                <th  data-priority="2"></th>
            </tr>
        </thead>

        <tbody class="" id="tableEmployees">

        </tbody>

    </table>
@endsection

@section('forms')
    @can('update', App\Models\User::class)
        @include('employees.edit')
    @endcan

    @can('create', App\Models\User::class)
        @include('profile.create')
    @endcan
@endsection


@section('datatable-script')
    <script>
        $('#filtersRow').hide()

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
                    window.open("/employees/" + table.row(this).data().slug, "_blank");

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
                    '<th class="col-3">Offices</th>' +
                    '<th class="col-sm">Subordinates</th>' +
                    '<th class="col-sm td-date_birth">Birthday</th>' +
                    '<th class="col-sm">Contacts</th>' +
                    '</tr>' +
                    '<tbody class="">' +
                    '<tr class="d-flex hidden-table"><td></td>' +
                    '<td class="col-3"><i class="fa-solid fa-location-dot"></i> ' + data['officeAddress'] +'</td>'+
                    '<td class="col-sm"><div>'+ subordinates  +'</div></td>' +
                    '<td class="col-sm td-date_birth"> ' + birthday +' </td>' +
                    '<td class="col-sm"> ' + contacts + ' </td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>';
                return $(htmlStr);
            }
            table = $('#usersTable').DataTable( {
                "responsive": true,
                "processing": true,
                "serverSide": true,
                responsive: true,
                colReorder: true,

                "ajax": {
                    url: "/datatable/employees",
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 401) {
                            window.location.href = window.location.href;
                        }
                    }
                },

                "order": [[1, 'asc']],
                columnDefs: [
                    {
                        // The `data` parameter refers to the data for the cell (defined by the
                        // `data` option, which defaults to the column being worked with, in
                        // this case `data: 0`.
                        render: function (data, type, row) {
                            return '<a href="#" data-user-id="'+data+'" id="edit-item" class="btn edit-user" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fa-solid fa-pen-to-square"></i></a>'
                        },
                        targets: -2,
                    },
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
                    { data: 'name', orderable: true, class: 'clickable'},
                    { data: 'rank', orderable: false, class: 'clickable'},
                    { data: 'department', orderable: false, class: 'clickable'},
                    { data: 'office', orderable: false, class: 'clickable'},

                    { data: 'email', orderable: false, class: 'clickable'},
                    { data: 'internal_phone', orderable: false, class: 'clickable'},
                    { data: 'documentStatus', orderable: false, class: 'clickable'},
                    {
                        class: 'td-edit-user',
                        orderable: false,
                        data: 'id'
                    },
                    {
                        class: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '<i class="fa-solid fa-angle-down" style="cursor: pointer;"></i>',
                    },
                ],
                "stateSave": true,
                "paging": true,
                "dom": 'tlfripB',
                "buttons": [
                    {
                        className: 'dt-export-buttons',
                        extend: 'collection',
                        text: '',
                        buttons: [

                            $.extend( true, {}, buttonCommon, {
                                extend: 'print',
                                exportOptions: {
                                    "format": {
                                        "header": function(content, index) {
                                            // Here 2 is the index of the column
                                            // whose header name we want to change(0 based)
                                            let result = content;
                                            if (index === 3 ) {
                                                result = '{{ __('Department') }}';
                                            }
                                            else if (index === 4 ) {
                                                result = '{{ __('Office') }}';
                                            }
                                            return result;
                                        }
                                    }
                                },
                            } ),
                            {
                                extend: 'excel',
                                text: 'Excel',
                                exportOptions: {
                                    modifier: {
                                        page: 'current'
                                    },
                                    columns: [1,2,3,4,5,6],
                                    "format": {
                                        "header": function(content, index) {
                                            // Here 2 is the index of the column
                                            // whose header name we want to change(0 based)
                                            let result = content;
                                            if (index === 3 ) {
                                                result = '{{ __('Department') }}';
                                            }
                                            else if (index === 4 ) {
                                                result = '{{ __('Office') }}';
                                            }
                                            return result;
                                        }
                                    }
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
                                    columns: [1,2,3,4,5,6],
                                    "format": {
                                        "header": function(content, index) {
                                            // Here 2 is the index of the column
                                            // whose header name we want to change(0 based)
                                            let result = content;
                                            if (index === 3 ) {
                                                result = '{{ __('Department') }}';
                                            }
                                            else if (index === 4 ) {
                                                result = '{{ __('Office') }}';
                                            }
                                            return result;
                                        }
                                    }
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
                },
            } );

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('#usersTable tbody').on('click', 'tr td.details-control', function () {
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


@section('custom-editors-scripts')
    <script>
        function clearEditForm()
        {
            $('#editUserForm  input:not([type=hidden])').val("");
            $('#editUserForm  select:not([type=hidden])').val("0");
            companyChoices.setChoiceByValue('');

            $('#department-div').hide()
            $('#position-div').hide()

            homePortChoices.removeActiveItems();
            jacketChoices.setChoiceByValue('');
            trousersChoices.setChoiceByValue('');
            shoesChoices.setChoiceByValue('');
        }

        $('#createEmployeeButton').on('click', function (e){
            e.preventDefault();
            $('#employee-form-method').val('POST');

            clearEditForm();

            $('#header-create-employee').show();
            $('#btn-create-save').show();
            $('#btn-create-save-open-docs').show();

            $('#btn-save').hide();
            $('#header-edit-employee').hide();
        });
    </script>
@endsection
