<div class="tab-pane fade px-0 py-0" id="documents" role="tabpanel"
     aria-labelledby="documents-tab">

    @if(! auth()->user()->is_applicant)
    <div id="all-docs-control-buttons">
        <div class="row">
            <div class="col-1 offset-11">

                <nav aria-label="breadcrumb">
                    <a class="btn btn-primary icon" href="{{ route('user.media', ['user' => $user->slug]) }}">
                        <i class="fa-solid fa-file-zipper"></i>
                    </a>
                </nav>

            </div>

        </div>
    </div>
    @endif

    <div id="categories">
        <div class="row pe-0 mb-0">
            @foreach($documentCategories as $category)
                <div class="col-xl-3 col-sm-6 col-12">
                    <div
                        class="card text-center border-{{ $user->getDocumentCategoryStatus($category->name) }} doc-category"
                        data-target="{{Str::slug($category->name)}}"
                        data-id="{{ $category->id }}">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row"></div>
                                <div class="row">
                                    <div class="col">

                                        <span class="doc-title"> <h5> {{ $category->displayName }} </h5> </span>
                                        <span>
                                            @if($count = $user->getDocumentsByCategoryCount($category))
                                                ({{$count}})
                                            @else
                                                {{"-"}}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#documents#{{Str::slug($category->name)}}" class="stretched-link navigation-link">

                        </a>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="row pe-0 mb-0">
            <div class="col-xl-5 col-sm-6 col-12">
                {{ __("Last update") }}:
                <b id="doc-updated-at">{{ $user->lastChange->updated_at ?? ''}}</b>
                by
                <b>
                    @if($user->changedByUserSlug)
                        <a href="{{ route('employees.show', ['employee' => $user->changedByUserSlug]) }}">{{$user->changedByUserName ?? ''}}</a>
                    @else
                        <a href="#">Admin</a>
                    @endif
                </b>
            </div>
            <div class="col-xl-7 col-sm-6 col-12 right">
                @if(count($archiveDocuments) > 0)
                    <a class="btn btn-sm btn-outline-light doc-category navigation-link extra-category-link" data-target="archive"
                       href="#documents#archive">
                        <span class="doc-title">{{ __('Archive') }} </span> <span
                            class="badge badge-circle bg-light ">{{count($archiveDocuments)}}</span>

                    </a>
                @endif

                @if(count($preExpired) > 0)
                    <a class="btn btn-sm btn-outline-warning doc-category navigation-link extra-category-link" data-target="pre-expired"
                       href="#documents#pre-expired">
                        <span class="doc-title">{{ __('Pre-expired') }} </span><span
                            class="badge badge-circle bg-warning ">{{count($preExpired)}}</span>
                    </a>
                @endif

                @if(count($expired) > 0)

                    <a class="btn btn-sm btn-outline-danger doc-category navigation-link extra-category-link" data-target="expired"
                       href="#documents#expired">
                        <span class="doc-title">{{ __('Expired documents') }}</span> <span
                            class="badge badge-circle bg-danger ">{{count($expired)}}</span>
                    </a>
                @endif


                @if(isset($missed) && $missed && count($missed) > 0)

                    <a class="btn btn-sm btn-outline-primary" href="#documents" data-bs-target="#missedDocsModal" data-bs-toggle="modal">
                        <span class="doc-title">{{ __('Missed documents') }}</span> <span
                            class="badge badge-circle bg-primary ">{{count($missed)}}</span>
                    </a>
                @endif
                @push('scripts-body')
                    <script>
                        $('.navigation-link').on('click', function(e){
                            if ($(this).hasClass('extra-category-link')) {
                                $('#addDocMenuDiv').hide();
                            } else {
                                $('#addDocMenuDiv').show();
                            }

                        });
                    </script>
                @endpush
            </div>
        </div>

        <div class="modal fade text-left" id="missedDocsModal" tabindex="-1" aria-labelledby="missedDocsModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title white" id="missedDocsModalLabel">{{ __('Missing documents') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center" id="check_title"></h4>
                        <table class='table table-hover' id="check_table" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="">{{ __('Category') }} </th>
                                <th class="">{{ __('Type') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($missed) && $missed)
                                @foreach($missed as $doc)
                                    <tr>
                                        <td> {{ $doc['category_name'] }} </td>
                                        <td> {{ $doc['type_name'] }} </td>
                                        <td> <button data-category-id="{{ $doc['document_category_id'] }}" data-type-id="{{ $doc['document_type_id'] }}" class="addMissingDoc btn btn-primary"> {{ __('Create') }} </button> </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        @push('scripts-body')
                            <script>
                                $('.addMissingDoc').on('click', function (e){
                                    let docCatId = $(this).data('categoryId');
                                    let docTypeId = $(this).data('typeId');

                                    $('#missedDocsModal').modal('hide')
                                    let event = jQuery.Event( "click" );
                                    event.addDocType = docTypeId;

                                    $('.doc-category[data-id="'+docCatId+'"]').trigger(event)

                                });
                            </script>
                        @endpush
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <span> {{ __('Close') }} </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <div id="docs-view-div" style="display: none;">
        <div class="row">
            <div class="col-xl-5 col-sm-6 col-12">
                <div class="row">
                    <div class="col-12">
                        <a class="btn icon btn-light navigation-link" href="#documents" id="back-button">
                            <i class="fa-solid fa-circle-chevron-left"></i>
                        </a>
                        <a class="btn icon icon-left btn-light" href="#documents">
                            <i class="fa-solid fa-user"></i>
                            <span id="categoryTitle">{{ __('Identity') }}</span>
                        </a>
                    </div>

                </div>
                <div class="row">

                    <div class="col-7" id="div-show-archived">
                        <a class="btn icon icon-left btn-outline-light" id="btn-show-archived" type="button">

                            <i class="fa-solid fa-box-archive"></i>
                            <span id="show-archived-label"> {{ __("Show archived") }} </span>
                            <span id="hide-archived-label" style="display: none"> {{ __("Hide archived") }} </span>

                        </a>
                    </div>


                    <div class="col-5">
                        @can('create', App\Models\User::class)
                            <div class="dropdown" id="addDocMenuDiv">
                                <a class="btn icon btn-primary me-1" type="button" id="downloadAllDocsButton">
                                    <i class="fa-solid fa-file-zipper"></i>
                                </a>

                                <button class="btn icon btn-success  me-1" type="button" id="addDocMenuButton"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-circle-plus"></i>
                                </button>
                            </div>
                        @endcan

                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <div role="tablist" id="docsTablist">
                            @foreach($documentCategories as $category)
                                <div class="list-group documents-list" id="{{Str::slug($category->name)}}"
                                     style="display: none;">

                                    <div class="dropdown-menu" aria-labelledby="addDocMenuButton" style="margin: 0px;">
                                        <div class="px-2 py-2">

                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                                <input class="form-control search-items" autocomplete="off"
                                                       onfocus="this.placeholder='Search by document name'" onblur="this.placeholder='';"
                                                >
                                            </div>
                                        </div>
                                        @push('scripts-body')
                                            <script>
                                                $('#addDocMenuButton').on('click', function (e){
                                                    $('.search-items').val('');
                                                })

                                                $('.search-items').on('keypress', function (e){
                                                    let searchVal = $(this).val().toLowerCase();
                                                    let res = $('a.add-doc-item:visible')
                                                        .filter(function(){
                                                            let str = $(this).text().toLowerCase();
                                                            return str.indexOf(searchVal) >= 0;
                                                        })

                                                    $(this).parent().parent().after(res)
                                                });
                                            </script>
                                        @endpush

                                        @foreach($category->documentTypes->where('is_archive', false) as $type)
                                            <a class="dropdown-item add-doc-item" data-target="{{Str::slug($category->name)}}"
                                               href="#" data-doctype-id="{{ $type->id }}">{{ $type->displayName }}</a>
                                        @endforeach
                                        @foreach($category->subcategories as $sub_category)
                                            <div class="dropdown-divider add-doc-item dropdown-item"></div>
                                            <div class="dropdown-header add-doc-item dropdown-item">
                                                {{ $sub_category->displayName }}
                                            </div>
                                            @foreach($sub_category->documentTypes as $sub_type)
                                                <a class="dropdown-item add-doc-item" data-target="{{Str::slug($category->name)}}"
                                                   href="#"
                                                   data-doctype-id="{{ $sub_type->id }}">{{ $sub_type->displayName }}</a>
                                            @endforeach
                                        @endforeach

                                    </div>
                                    @foreach($user->documents->where('is_archive', false)->sortBy('type') as $document)
                                        @if($document->category?->id == $category->id)

                                            <a class="list-group-item
                                                    list-group-item-action
                                                    list-group-item-{{ $document->statusStyleName }}"
                                               id="list-foreign-passport"
                                               data-bs-toggle="list"
                                               href="#documents#{{Str::slug($category->name)}}"
                                               data-document-type="{{ $document->type->id ?? ''}}"
                                               data-document-title="{{ $document->type->displayName ?? ''}}"
                                               data-document-id="{{ $document->id }}"
                                               data-is-archived="{{$document->is_archive}}"
                                               role="tab"><b></b>

                                                <div class="row">
                                                    <div class="col-12">
                                                        {{ $document->type->displayName ?? ''}}
                                                        @if($document->is_relevant)
                                                            <i class="fa-solid fa-check is-relevant"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-6">
                                                        {{ $document->number }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                </div>



                                            </a>

                                        @elseif($document?->category?->parent_id == $category->id)

                                            <a class="list-group-item
                                                list-group-item-action
                                                list-group-item-{{ $document->statusStyleName }}"
                                               id="list-foreign-passport"
                                               data-bs-toggle="list"
                                               href="#documents#{{Str::slug($category->name)}}"
                                               data-document-type="{{ $document->type->id ?? ''}}"
                                               data-document-title="{{ $document->type->displayName ?? ''}}"
                                               data-document-id="{{ $document->id }}"
                                               data-is-archived="{{$document->is_archive}}"
                                               role="tab"><b></b>

                                                <div class="row">
                                                    <div class="col-12">
                                                        {{ $document->type->displayName ?? ''}}
                                                        @if($document->is_relevant)
                                                            <i class="fa-solid fa-check is-relevant"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-6">
                                                        {{ $document->number }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                </div>

                                            </a>

                                        @endif
                                    @endforeach

                                    @foreach($user->documents->where('is_archive', true)->sortBy('type') as $document)
                                        @if($document->category?->id == $category->id)

                                            <a class="list-group-item
                                                    list-group-item-action
                                                    list-group-item-{{ $document->statusStyleName }}"
                                               id="list-foreign-passport"
                                               data-bs-toggle="list"
                                               href="#documents#{{Str::slug($category->name)}}"
                                               data-document-type="{{ $document->type->id ?? ''}}"
                                               data-document-title="{{ $document->type->displayName ?? ''}}"
                                               data-document-id="{{ $document->id }}"
                                               data-is-archived="{{$document->is_archive}}"
                                               role="tab">


                                                <div class="row">
                                                    <div class="col-12">
                                                        <b><i class="fa-solid fa-ban"></i>
                                                            ({{ __('Archive') }}) </b>
                                                        {{ $document->type->displayName ?? ''}}
                                                        @if($document->is_relevant)
                                                            <i class="fa-solid fa-check is-relevant"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-6">
                                                        {{ $document->number }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                    <div class="col-3">
                                                        {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                                    </div>
                                                </div>


                                            </a>
                                        @endif
                                    @endforeach
                                    <a style="display: none;" href='#documents#{{Str::slug($category->name)}}'
                                       id='hidden-document-tmp'
                                       class="list-group-item list-group-item-action hidden-document-tmp"
                                       data-is-archived="false" data-bs-toggle="list" role="tab"><b></b></a>
                                </div>
                            @endforeach

                            <div class="list-group documents-list" id="archive" style="display: none;">

                                @foreach($archiveDocuments as $document)
                                    <a class="list-group-item
                                                    list-group-item-action
                                                    list-group-item-{{ $document->statusStyleName }}"
                                       data-bs-toggle="list"
                                       href="#documents#archive"
                                       data-document-type="{{ $document->type->id ?? ''}}"
                                       data-document-title="{{ $document->type->displayName ?? ''}}"
                                       data-document-id="{{ $document->id }}"
                                       data-is-archived="{{$document->is_archive}}"
                                       role="tab"><b></b>

                                        <div class="row">
                                            <div class="col-12">
                                                <b><i class="fa-solid fa-ban"></i>
                                                    ({{ __('Archive') }}) </b>
                                                {{ $document->type->displayName ?? ''}}
                                                @if($document->is_relevant)
                                                    <i class="fa-solid fa-check is-relevant"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-6">
                                                {{ $document->number }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                            </div>
                                        </div>

                                    </a>
                                @endforeach
                                <a style="display: none;" href='#documents#archive' id='hidden-document-tmp'
                                   class="list-group-item list-group-item-action hidden-document-tmp"
                                   data-is-archived="false" data-bs-toggle="list" role="tab"><b></b></a>

                            </div>
                            <div class="list-group documents-list" id="pre-expired" style="display: none;">
                                @foreach($preExpired as $document)
                                    <a class="list-group-item
                                                    list-group-item-action
                                                    list-group-item-{{ $document->statusStyleName }}"
                                       data-bs-toggle="list"
                                       href="#documents#pre-expired"
                                       data-document-type="{{ $document->type->id ?? ''}}"
                                       data-document-title="{{ $document->type->displayName ?? ''}}"
                                       data-document-id="{{ $document->id }}"
                                       data-is-archived="{{$document->is_archive}}"
                                       role="tab"><b></b>

                                        <div class="row">
                                            <div class="col-12">
                                                <b><i class="fa-solid fa-ban"></i>
                                                    ({{ __('Archive') }}) </b>
                                                {{ $document->type->displayName ?? ''}}
                                                @if($document->is_relevant)
                                                    <i class="fa-solid fa-check is-relevant"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-6">
                                                {{ $document->number }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                            </div>
                                        </div>

                                    </a>

                                @endforeach
                                <a style="display: none;" href='#documents#pre-expired' id='hidden-document-tmp'
                                   class="list-group-item list-group-item-action hidden-document-tmp"
                                   data-is-archived="false" data-bs-toggle="list" role="tab"><b></b></a>

                            </div>

                            <div class="list-group documents-list" id="expired" style="display: none;">

                                @foreach($expired as $document)
                                    <a class="list-group-item
                                                    list-group-item-action
                                                    list-group-item-{{ $document->statusStyleName }}"
                                       data-bs-toggle="list"
                                       href="#documents#expired"
                                       data-document-type="{{ $document->type->id ?? ''}}"
                                       data-document-title="{{ $document->type->displayName ?? ''}}"
                                       data-document-id="{{ $document->id }}"
                                       data-is-archived="{{$document->is_archive}}"
                                       role="tab"><b></b>

                                        <div class="row">
                                            <div class="col-12">
                                                <b><i class="fa-solid fa-ban"></i>
                                                    ({{ __('Archive') }}) </b>
                                                {{ $document->type->displayName ?? ''}}
                                                @if($document->is_relevant)
                                                    <i class="fa-solid fa-check is-relevant"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-6">
                                                {{ $document->number }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_issue?->format('d.m.Y') ?? '-' }}
                                            </div>
                                            <div class="col-3">
                                                {{ $document->date_valid?->format('d.m.Y') ?? '-' }}
                                            </div>
                                        </div>

                                    </a>
                                @endforeach
                                <a style="display: none;" href='#documents#expired' id='hidden-document-tmp'
                                   class="list-group-item list-group-item-action hidden-document-tmp"
                                   data-is-archived="false" data-bs-toggle="list" role="tab"><b></b></a>

                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-sm-6 col-12">
                <div class="tab-content text-justify sticky-top" id="nav-tabContent">
                    <div class="tab-pane show active" role="tabpanel">


                        <div class="card border-success" id="activeDocumentCard" style="display: none">
                            <div class="card-content">
                                <div class="card-body card-loading">
                                    <div class="d-flex justify-content-center" style="display: none">
                                        <img src="{{ asset('assets/images/svg-loaders/puff.svg') }}" class="me-4"
                                             style="width: 3rem" alt="audio">
                                    </div>
                                </div>
                                <div class="card-body card-loaded">

                                    <div class="row">
                                        <div class="col">
                                            <div class="row" id="div-edit-buttons">
                                                <div class="col-sm-12 align-content-end">
                                                    @can('update', App\Models\Document::class)
                                                        <a href="#" class="btn btn-success icon edit-buttons" id="btn-edit-document">
                                                            <i class="fa-solid fa-pen"></i>
                                                            {{__("Edit")}}
                                                        </a>
                                                        <a href="#" class="btn btn-light icon edit-buttons" id="btn-archive"
                                                           data-bs-toggle="modal" data-bs-target="#archive-confirm">
                                                            <i class="fa-solid fa-box-archive"></i>
                                                            {{__("Archive")}}
                                                        </a>
                                                    @endcan

                                                    @if(! auth()->user()->is_applicant)

                                                    <a href="#" class="btn btn-light icon" id="btn-print-document">
                                                        <i class="fa-solid fa-print"></i>
                                                        {{__("Print")}}
                                                    </a>
                                                    @endif

                                                    <a href="#" class="btn btn-primary icon"
                                                       id="btn-download-media-document">
                                                        <i class="fa-solid fa-file-zipper"></i>
                                                        {{__("Download")}}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row" id="div-archived-buttons">
                                                <div class="col-sm-12 align-content-end">
                                                    @can('update', App\Models\Document::class)

                                                        <a href="#" class="btn btn-primary icon" id="btn-restore">
                                                            <i class="fa-solid fa-boxes-packing"></i>
                                                            {{__("Restore")}}
                                                        </a>

                                                    @endcan

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <h4 class="card-title" id="document-title">Document name </h4>
                                        </div>

                                    </div>
                                    @include('profile.documents.document-form')

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pe-0 mb-0">
            <div class="col-xl-5 col-sm-6 col-12">
                {{ __("Last update") }}:
                <b id="doc-updated-at">{{ $user->lastChange->updated_at ?? ''}}</b>
                by
                <b>
                    @if($user->changedByUserSlug)
                        <a id="doc-updated-by"
                           href="{{ route('employees.show', ['employee' => $user->changedByUserSlug]) }}">{{$user->changedByUserName ?? ''}}</a>
                    @else
                        <a id="doc-updated-by" href="#">Admin</a>
                    @endif
                </b>
            </div>
        </div>
    </div>


    <div class="row">

    </div>


</div>

@can('update', App\Models\Document::class)
    @include('profile.documents.confirm-archive')
    @include('profile.documents.confirm-deleting-scan')
@endcan

@push('scripts-body')
    @canany(['update', 'create'], App\Models\Document::class)
        <script>
            function removeValidationMessages() {
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
            }

            $('#btn-save-edits').on('click', function (e, eventData) {
                e.preventDefault();
                let document_id = $('#input-document-id').val();
                let user_id = $('#input-user-id').val();
                let url = '/documents/' + document_id;
                if ($('#form-method').val() === 'POST') {
                    url = '/users/' + user_id + '/documents';
                }
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                let myform = document.getElementById('edit-document-form');
                let fd = new FormData(myform);
                fd.set('is_archive', fd.get('is_archive') === "false" ? 0 : 1)
                $.ajax({
                    url: url,
                    data: fd,
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,

                    success: function (data) {
                        uploadFileDocID = data['id'];

                        removeValidationMessages();

                        $('#edit-document-form :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');

                        $('.multiple-files-filepond').filepond('processFiles').then((file) => {
                            $('#input-document-id').val(data['id']);
                            $('.opened > a.active').attr('data-document-id', data['id']);
                            updateFormDataNew(data['id'], eventData);

                            $('#btn-save-edits').parent().hide();
                            $('.delete-scan').hide();
                            $('#drop-area').hide();
                            $('#edit-document-form :input').prop('readonly', true).prop('disabled', true);
                            $('.edit-buttons').show();

                            $('.document-form-input').hide();
                            $('.input-alter').show();
                            removeValidationMessages();
                        });
                    },
                    error: function (err) {
                        removeValidationMessages();
                        if (err.status === 422) { // when status code is 422, it's a validation issue
                            $.each(err.responseJSON.errors, function (input, error) {
                                let [i, index] = input.split('.');
                                let el = $(document).find('[name="' + i + '"]');
                                if (index >= 0) {
                                    el = $(document).find('[name="' + i + '[' + index + ']"]');
                                }
                                el.addClass('is-invalid');
                                el.after($('<div class="invalid-feedback"><i class="bx bx-radio-circle"></i>' +
                                    error[0] +
                                    '</div>'));
                            });
                            $('#edit-document-form :input').filter(function () {
                                return $.trim($(this).val()).length > 0
                            }).addClass('is-valid');
                        }
                    }

                });


            });

            $('#btn-edit-document').on('click', function (e) {
                console.log('btn edit')
                e.preventDefault();
                let buttonsDiv = $('#btn-save-edits').parent();

                $('#edit-document-form :input').prop('readonly', false).prop('disabled', false);
                $('.delete-scan').show();
                $('#drop-area').show();

                buttonsDiv.show();
                $('.document-form-input').show();
                $('.input-alter').hide();

                $('.edit-buttons').hide();
                $('.card-loading').hide();
            });

            $('#btn-reset').on('click', function (e) {
                e.preventDefault();

                $('#input-number').val('');
                $('#input-date-issue').val('');
                $('#input-date-valid').val('');
                $('#input-place').val('');
                $('#input-is-relevant').prop('checked', true);

                removeValidationMessages();
                if ($('#input-document-id').val() == '') {
                    return;
                }

                $('#edit-document-form :input').prop('readonly', true).prop('disabled', true);
                $(this).parent().hide();
                $('#drop-area').hide();
                $('.document-form-input').hide();
                $('.input-alter').show();
                $('.delete-scan').hide();
                $('.edit-buttons').show();
                updateFormDataNew($('#input-document-id').val());

            });

            $('#btn-restore').on('click', function (e) {
                e.preventDefault();

                let input = $('#input-is-archive');
                input.val('false');
                $('#edit-document-form :input').prop('readonly', false).prop('disabled', false);
                $('#btn-save-edits').trigger('click');

            });

            let deleteScanId = null;

            $(document).on('click', 'a.delete-scan', function (e) {
                deleteScanId = $(this).attr('data-scan-id')
                $('#confirm-delete-filename').text($(this).attr('data-filename'));
                $('#confirm-delete-doctype').text($('#document-title').text());
            });


        </script>

        <script>

            $('a.add-doc-item').on('click', function (e) {
                e.preventDefault();

                if ($(this).attr('data-doctype-id') == null) {
                    return;
                }

                $('.opened > a').removeClass('active');

                let label = $(this).text();
                let newDoc = $('#hidden-document-tmp').clone(true);
                newDoc.addClass('list-group-item-primary')
                    .attr('data-document-type', $(this).attr('data-doctype-id'))
                    .attr('data-document-title', label)
                    .text(label)
                    .addClass('active').trigger('click');
                $('#' + $(this).attr('data-target')).append(newDoc);


                newDoc.show();
                toggleEditDocCard();

                clearForm();

                $.ajax({
                    url: '/document-type/' + $(this).attr('data-doctype-id') + '/extra-fields',
                    type: 'GET',
                    contentType: 'json',
                    success: function (data) {
                        data['extraFields'].forEach((elem) => {
                            if (elem['defaultValues']) {
                                let field = '<div class="col-md-3 extra-field">' +
                                    '<label>' + elem['displayName'] + '</label></div>' +
                                    '<div class="col-md-9 form-group extra-field">' +
                                    '<select class="form-select extra-field-input form-control document-form-input" name="' + elem['slug'] + '" id="' + elem['id'] + '">' +
                                    '<option value=""></option>';

                                elem['defaultValues'].forEach(function (item) {
                                    field = field + '<option value="' + item + '">' + item + '</option>';
                                })

                                field = field + '</select></div>';
                                $('#document-place-field-div').after(field);
                            } else {
                                $('#document-place-field-div').after('<div class="col-md-3 extra-field">' +
                                    '<label>' + elem['displayName'] + '</label></div>' +

                                    '<div class="col-md-9 form-group extra-field">' +
                                    '<input type="text" id="' + elem['id'] + '" class="form-control document-form-input extra-field-input" name="' + elem['slug'] + '" value="">' +
                                    '</div>');

                            }

                        })

                    }
                })

                $('.extra-field-input').hide()

                $('#btn-edit-document').trigger('click');


            });

        </script>
    @endcan

    <script>



        xhrPool = [];

        function clearForm() {
            $('#input-number').val('');
            $('#input-date-issue').val('');
            $('#input-date-valid').val('');
            $('#input-place').val('');
            $('#input-is-relevant').prop('checked', true);

            $('#activeDocumentCard').removeClass().addClass('card');
            $('#status-icon').removeClass().addClass('fa-solid').addClass('fa-circle').addClass('text-primary');


        }

        function updateFormDataNew(document_id, eventData='') {
            $('.card-loading').show();
            $('.card-loaded').hide();

            if ($('.list-group:visible').find(' .list-group-item[data-is-archived="1"]').length > 0) {
                $('#div-show-archived').show();
            } else {
                $('#div-show-archived').hide();
            }

            if (!document_id) {
                $('#div-archived-buttons').hide(0);
                $('.card-loading').hide();
                $('.card-loaded').show();
                $('#btn-edit-document').trigger();
                return;
            }
            $.ajax({
                url: '/documents/' + document_id,
                type: 'GET',
                contentType: 'json',
                beforeSend: function (jqXHR, settings) {
                    xhrPool.push(jqXHR);
                },
                success: function (data) {
                    $('.extra-field').remove();
                    if (jQuery.isEmptyObject(data)) {
                        $('#form-method').val('POST');
                        clearForm();
                    } else {
                        let activeDocument = $('.opened > a.active');
                        $('#activeDocumentCard').removeClass()
                            .addClass('card')
                            .addClass('border-' + data['statusStyleName']);
                        activeDocument.removeClass()
                            .addClass('active list-group-item list-group-item-action list-group-item-' + data['statusStyleName'])
                        $('#form-method').val('PATCH');
                        $('#input-number').val(data['number']);
                        $('#span-number').text(data['number']);

                        $('#input-date-issue').val(data['date_issue']);
                        $('#span-date-issue').text(data['date_issue'] ?? '-');

                        $('#input-date-valid').val(data['date_valid']);
                        $('#span-date-valid').text(data['date_valid'] ?? '-');

                        $('#input-place').val(data['place']);

                        $('#span-place').text(data['place']);

                        $('#input-document-id').val(data['id']);
                        $('#input-is-relevant').prop('checked', data['is_relevant']);

                        if (data['extraFields']) {
                            data['extraFields'].forEach((elem) => {

                                if (elem['defaultValues']) {
                                    let field = '<div class="col-md-3 extra-field">' +
                                        '<label>' + elem['displayName'] + '</label></div>' +
                                        '<div class="col-md-9 form-group extra-field">' +
                                        '<select id="' + elem['id'] + '" class="form-select extra-field-input form-control document-form-input" name="' + elem['slug'] + '">' +
                                        '<option value=""></option>';

                                    elem['defaultValues'].forEach(function (item) {
                                        field = field + '<option value="' + item + '" ' + ((typeof data['extraValues'][elem['id']] !== 'undefined') ? (data['extraValues'][elem['id']]['value'] === item ? 'selected' : '') : '') + '>' + item + '</option>';
                                    })

                                    field = field + '</select>' +
                                        '<span id="span-place" class="input-alter"> ' + ((typeof data['extraValues'][elem['id']] !== 'undefined') ? data['extraValues'][elem['id']]['value'] : '-') + ' </span>' +
                                        '</div>';
                                    $('#document-place-field-div').after($(field));
                                } else {
                                    $('#document-place-field-div').after('<div class="col-md-3 extra-field">' +
                                        '<label>' + elem['displayName'] + '</label></div>' +

                                        '<div class="col-md-9 form-group extra-field">' +
                                        '<input type="text" id="' + elem['id'] + '" class="form-control document-form-input extra-field-input" name="' + elem['slug'] + '" readonly value="' + ((typeof data['extraValues'][elem['id']] !== 'undefined') ? data['extraValues'][elem['id']]['value'] : '') + '">' +
                                        '<span id="span-place" class="input-alter"> ' + ((typeof data['extraValues'][elem['id']] !== 'undefined') ? data['extraValues'][elem['id']]['value'] : '-') + ' </span>' +
                                        '</div>');

                                }
                            })

                        }
                        $('.extra-field-input').hide()


                        if (data['lastChange']) {
                            $('#doc-updated-at').text(data['lastChange']['updated_at']);
                            $('#doc-updated-by').html(data['changedByUserName'] ?? 'Admin')
                            $('#doc-updated-by').attr('href', data['changedByUserSlug'] ?? '#');
                        } else {
                            $('#doc-updated-at').text('');
                        }

                        let activeDocIcon = $('.opened > a.active > b');
                        if (data['is_archive']) {
                            activeDocument.attr('data-is-archived', '1');
                            $('.edit-buttons').hide(0);
                            $('#div-archived-buttons').show(0);


                            if (data['is_expired']) {
                                if (data['allow_restore']) {
                                    $('#btn-restore').show(0);
                                } else {
                                    $('#btn-restore').hide(0);
                                }
                            }

                        } else {
                            activeDocument.attr('data-is-archived', 'false');
                            $('#div-archived-buttons').hide(0);
                            $('.edit-buttons').show(0);
                            activeDocIcon.html('')
                        }

                        let relevantIcon = $('.opened > a.active > .is-relevant');
                        if (data['is_relevant']) {
                            relevantIcon.show();
                        } else {
                            relevantIcon.hide();
                        }

                        $('#document-description').text($('#document-title').text() + ", №" + data['number'] + ", status: " + data['status'])

                        $('#status-icon').removeClass()
                            .addClass('fa-solid fa-circle text-' + data['statusStyleName']);

                        $('#images-div').html('');
                        $('#gallery').html('');

                        if (data['docScans'].length > 0) {
                            $('#btn-download-media-document').show()
                            $('#btn-download-media-document').attr('href', '/doc-all-media/' + data['id']);
                        } else {
                            $('#btn-download-media-document').hide()
                            $('#btn-download-media-document').attr('href', '#');
                        }

                        data['docScans'].forEach((element) => {

                            let title = 'Image';
                            if (element.is_pdf) {
                                title = 'PDF';
                            }

                            let wrapper_1 = '<div class="col-xl-3 col-md-6 col-sm-12"> <div class="card"> <div class="card-content">' +
                                '<img class="card-img img-fluid" data-scan-id="' + element.id + '" data-full-src="' + element.url + '" src="' + element.preview_url + '" alt="doc scan">' +
                                '<div class="card-img-overlay overlay-dark bg-overlay d-flex justify-content-between flex-column">' +
                                '<div class="overlay-content"><div class="row">' +

                                '<div class="col-12"><h4 class="card-title mb-50">' + title +
                                '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + element.filename + '">' +
                                '<a class="scan-thumbnail stretched-link" ' +
                                'data-download-href="/document-scan/' + element.id + '" ' +
                                'data-pages-count="' + element.pages_count + '" ' +
                                'data-scan-id="' + element.id + '"  ' +
                                'data-is-pdf=' + Number(element.is_pdf) + '  ' +
                                'data-bs-toggle="modal" ' +
                                'data-bs-target="#photoModal" aria-label="Preview"></a>' +
                                '<span class="badge bg-info text-sm">' + (element.filename ? element.filename.substring(0, 7) : '') + '...</span></span></h4>' +


                                '</div></div>' +
                                '<div class="row">' +
                                '<div class="col-6 col-offset-3">' +
                                '<div class="btn-group-vertical" role="group" aria-label="controls buttons">' +
                                '<a class="delete-scan btn btn-danger icon stretched-link" ' +
                                'data-bs-toggle="modal" ' +
                                'data-bs-target="#delete-scan-confirm" ' +
                                'aria-label="delete scan" ' +
                                'data-scan-id="' + element.id + '" ' +
                                'data-filename="' + element.filename + '" ' +
                                'style="display:none; position: relative;"><i class="fa-solid fa-trash-can"></i></a>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +

                                '</div><div class="overlay-status"></div></div></div></div></div>';

                            $('#images-div').append($(wrapper_1));
                        })
                    }
                    $('.card-loading').hide();
                    $('.card-loaded').show();

                    if (eventData === 'archive-create') {

                        let doctype_id = $('#input-doctype').val();
                        let doctype_dropdown = $('a.add-doc-item[data-doctype-id="'+doctype_id+'"]');
                        doctype_dropdown.trigger('click');
                    }


                    return true;
                },
                error: function (err) {
                    $('.card-loading').hide();
                    $('.card-loaded').show();
                    return false;
                }
            });
        }

        function toggleEditDocCard() {

            let editDocCard = $('#activeDocumentCard');
            if ($('.opened > a').length > 1) {
                editDocCard.show();
                $('.document-form-input').hide();
                $('.input-alter').show();
            } else {
                editDocCard.hide();
                $('.document-form-input').show();
                $('.input-alter').hide();
            }
        }

        // shows all available document types for selected category.
        // dropdown options where generated
        function showAddDocumentMenu(documentListGroup) {
            let menu = documentListGroup.find('.dropdown-menu > .add-doc-item').clone(true);
            let menuWraper = $('#addDocMenuDiv');
            menuWraper.find('.dropdown-menu > .add-doc-item').remove();
            menuWraper.children('.dropdown-menu:first').append(menu);
            menuWraper.append(documentListGroup.find('.dropdown-menu').clone(true));
        }

        // when 'large' category selected (Identity, Official Employment, etc.)
        $('.doc-category').on('click', function (e) {
            e.preventDefault();
            $('#all-docs-control-buttons').hide();

            let category_id = $(this).data('id');
            let user_id = $('#input-user-id').val();

            $('#downloadAllDocsButton').attr('href', '/media/users/'+user_id+'/categories/' + category_id)

            if ($(this).attr('href') === '#documents#archive') {

                $('#btn-show-archived').trigger('click');
                $('.list-group-item[data-is-archived="1"]').show();

            } else {
                $('#hide-archived-label').hide();
                $('#show-archived-label').show();
                $('.list-group-item[data-is-archived="1"]').hide();

            }

            // hide cards with 'large' document groups
            $('#categories').toggle(0);
            // set category/group title above documents list (same title as selected card)
            // let documentCategoryTitle = $(this).find('h5:first-child').text();
            let documentCategoryTitle = $(this).find('.doc-title:first-child').text();

            $('#categoryTitle').text(documentCategoryTitle);

            $('#docs-view-div').toggle(0); //docs-view-div contains list of documents, edit form for documents

            // make the list of documents visible
            // target is the id of list-group element (all document types) for selected category/group
            let target = $(this).attr('data-target');
            let documentListGroup = $('#' + target);

            documentListGroup.show().addClass('opened');
            showAddDocumentMenu(documentListGroup);


            $('.opened > a').removeClass('active');



            // making active the first document in the list,
            // retrieving document type for the first element in list group
            // and updating values for this document

            let firstDocElem = documentListGroup
                .find('a.list-group-item')
                .not('.hidden-document-tmp')
                .filter(":first");

            if (e.addDocType) {
                $('a.add-doc-item[data-doctype-id="'+e.addDocType+'"]').trigger('click')

            } else if (firstDocElem.length === 0) {
                $('#activeDocumentCard').hide();

            } else {
                firstDocElem.addClass('active').trigger('click');
                $('#activeDocumentCard').show();
                $('#document-title').text(firstDocElem.attr('data-document-title'));
                $('#input-document-id').val(firstDocElem.attr('data-document-id'));
                let firstDocType = firstDocElem.attr('data-document-type');
                $('#input-doctype').val(firstDocType);
                updateFormDataNew(firstDocElem.attr('data-document-id'));
            }
            toggleEditDocCard();

            if (documentListGroup.find(' .list-group-item[data-is-archived="1"]').length > 0) {
                $('#div-show-archived').show();
            } else {
                $('#div-show-archived').hide();
            }


            if (e.addDocType) {
                $('a.add-doc-item[data-doctype-id="'+e.addDocType+'"]').trigger('click')

            }

        });


        $('.list-group-item').on('click', function (e) {
            // when selecting ONE specific document

            $('.list-group-item.list-group-item-primary').remove();
            $('.extra-field').remove();
            // updating document data from server
            let doctype = $(this).attr('data-document-type');
            let title = $(this).attr('data-document-title');
            let doc_id = $(this).attr('data-document-id');

            $('#div-archived-buttons').hide(0);

            uploaded_files = [];

            $('#input-doctype').val(doctype);
            $('#input-document-id').val(doc_id);
            $('#document-title').text(title);
            let formMethod = $('#form-method');
            if (!doc_id) {
                $('#images-div').html('');
                clearForm();
                $('#btn-edit-document').trigger('click');
                formMethod.val('POST');
                return;
            }

            formMethod.val('PATCH');

            $.each(xhrPool, function (idx, jqXHR) {
                jqXHR.abort();
            });

            updateFormDataNew(doc_id);


            $('#btn-reset').trigger('click', 'dont-hide');
            toggleEditDocCard();

        });


        $('#back-button').on('click', function (e) {
            $('#all-docs-control-buttons').show();
            $('#btn-reset').trigger('click');
            // hide list-group with documents & show 'large' category cards (Identity, Official Employment, etc.)
            e.preventDefault();
            $('.opened').toggle().removeClass('opened');
            $('#docs-view-div').toggle();
            $('#categories').toggle();

            $('a[data-bs-toggle="tab"]').trigger('show.bs.tab');
            $('a.list-group-item-primary').remove();
        });

        $('#btn-show-archived').on('click', function (e) {
            e.preventDefault();
            let archive_docs = $('.list-group-item[data-is-archived="1"]');
            archive_docs.toggle();

            $('#hide-archived-label').toggle();
            $('#show-archived-label').toggle();
        });



        $(document).on('click', '.scan-thumbnail', function (e) {
            if ($(this).hasClass('pdf')) {
                let mbstring = $(this).attr('data-src');
                let objbuilder = '';
                objbuilder += ('<object width="100%" height="100%"' +
                    'data="data:application/pdf;base64,');
                objbuilder += (mbstring);
                objbuilder += ('" type="application/pdf" class="internal">');
                objbuilder += ('<embed src="data:application/pdf;base64,');
                objbuilder += (mbstring);
                objbuilder += ('" type="application/pdf"  />');
                objbuilder += ('</object>');

                let win = window.open("#", "_blank");
                let title = "my tab title";
                win.document.write('<html><title>' + title + '</title><body style="margin-top:' +
                    '0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px;">');
                win.document.write(objbuilder);
                win.document.write('</body></html>');
                layer = jQuery(win.document);

            } else {
                $('.extraScan').remove();
                $('#scan-large').attr('src', $('img[data-scan-id=' + $(this).data('scan-id') + ']').attr('data-full-src'));

                if ($(this).data('is-pdf') === 0) {
                    $('#modal-header-scan').show()
                    $('#modal-header-pdf').hide()

                } else {
                    $('#modal-header-scan').hide()
                    $('#modal-header-pdf').show()
                    $('#pdf-pages-count').text($(this).data('pages-count'))
                }
                $('#btn-download-full-version').attr('href', $(this).data('download-href'))
            }

            $("#scan-large").blowup({
                "background" : "#FCEBB6",
                "width" : 250,
                "height" : 250,
                "scale": 2,
            });
        });

        $('#documents-tab').on('click', function(e){
            if ($('#back-button').is(':visible')) {
                $('#back-button').trigger('click');
            }

        })

    </script>

@endpush
