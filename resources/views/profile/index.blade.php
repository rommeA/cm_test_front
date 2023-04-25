@extends('layouts.app')
@section('content')
<section class="section">
    <div class="row">
        <div class="col">
            @yield('breadcrumb')
        </div>
    </div>
    @if(! Str::contains(Request::url(), '/contractors'))
        @if(! Str::contains(Request::url(), '/partners'))
            @include('seamen.create-edit-form.layout')
        @endif
    @endif

    <div class="row mb-4" id="mainContent">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">


                    @yield('table-title')

                    <div class="d-flex" id="export-button">

                    </div>
                </div>

                <div class="card-body">
                    <div class="row justify-content-evenly">
                        <div class="col">
                            <div id="searchBox"></div>
                        </div>
                        <div class="col">
                            @can('create', App\Models\User::class)
                            <div class="buttons pull-right">
                                @yield('buttons')
                            </div>
                            @elsecan('create', App\Models\Contractor::class)
                                <div class="buttons pull-right">
                                    @yield('buttons')
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>


                <section class="section">
                    <div class="card-body">

                        <div class="table-responsive-sm">
                            <div class="row" id="filtersRow">
                                <div class="col-md-5">
                                    @yield('filter-second')
                                </div>
                                <div class="col-md-5">
                                    @yield('filter-first')
                                </div>
                            </div>
                            @yield('main-table')
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

@yield('forms')

@endsection
@push('styles')
    <style>
        .form-group.select-filter {
            margin-bottom: 0 !important;
        }

        .form-select.select-filter {
            width: auto;
            max-width: 12rem;
            border: 0;
            font-weight: bold;
            padding: 1.15rem 0.5rem;
            color: #727e8c;
            font-size: .9rem;
        }
    </style>
@endpush

@push('scripts-body')
    <script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake.min.js') }}"></script>
    <script src=" {{ asset('js/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatables/buttons.print.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
    <script src="{{ asset('js/datatables/responsive.min.js') }}"></script>




    <script>
        let table = '';
    </script>

    @yield('datatable-script')

    <script>

        $(document).ready(function($) {
            let search_input = $('input[type="search"][aria-controls="usersTable"]');
            search_input.addClass('form-control').removeClass('form-control-sm');
            let div_form_icon = $('<div class="form-control-icon"></div>');
            div_form_icon.append('<i class="fa-solid fa-magnifying-glass"></i>')

            search_input.parent().parent().append(search_input).addClass('form-group position-relative has-icon-left')
            search_input.parent().append(div_form_icon);
            search_input.parent().children('label').remove()

            $('div#searchBox').append(search_input.parent());

            // let selectDiv = $('.dataTables_length')
            // let selectRowCnt = selectDiv.children('label').children('select')
            // selectDiv.append(selectRowCnt)
            // $('#rowCount').append(selectDiv)

            $('#usersTable').on( 'stateLoaded.dt', function () {
                @cannot('update', App\Models\User::class)
                    $(this).columns('.td-edit-user').visible(false);
                    $(this).columns.adjust().draw( false );
                @else
                    $(this).columns('.td-edit-user').visible(true);
                    $(this).columns.adjust().draw( false );
                @endcannot
            } );

        });

    </script>



    @canany(['update', 'create'], App\Models\User::class)

        <script>

            $('div.btn-collapse').on('click', function(){
                let icon = $(this).children('div').eq(0).children('a').eq(0).children('i').eq(0);
                if (icon.hasClass('fa-angle-down')) {
                    icon.removeClass('fa-angle-down');
                    icon.addClass('fa-angle-up');
                } else {
                    icon.addClass('fa-angle-down');
                    icon.removeClass('fa-angle-up');
                }

            });

            function updateRow(row, user_id){
                $.ajax({
                    url: "/users/toJson/" + user_id,
                    method: 'get',
                    dataType: 'json',
                    success: function(data){
                        row.children('td').next('.td-name').html(data['display_name']);
                        row.children('td').next('.td-internal').html(data['internal_phone']);
                        row.children('td').next('.td-position').html(data['positionName']);
                        row.children('td').next('.td-department').html(data['departmentName']);
                        row.children('td').next('.td-companyShortName').html(data['companyShortName']);
                        row.children('td').find('img.avatar-img:first-child').attr('src', "data:image/png;base64," + data['photo']);
                    }
                });
            }

            function addRow(data){
                location.reload();
            }

            $(document).on('click', '.edit-user', function (e){
                $('#employee-form-method').val('PATCH');
                $('#header-create-employee').hide();
                $('#header-edit-employee').show();

                $('#btn-create-save').hide();
                $('#btn-create-save-open-docs').hide();

                $('#btn-save').show();
                $('#header-edit-employee').show();
                $('#header-сreate-employee').hide();
            });
        </script>

        @yield('custom-editors-scripts')
    @endcan
@endpush
