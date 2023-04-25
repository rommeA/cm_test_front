@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush

@section('modal-edit-label')
    {{ __("Edit Partner Card") }}
@endsection

@section('modal-add-label')
    {{ __("Add Partner Card") }}
@endsection

<div class="modal fade text-left" data-bs-backdrop="static" data-bs-keyboard="false" id="editUserModal" tabindex="-1"
     role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success" id="header-edit-employee">
                <h4 class="modal-title white" id="editUserLabel">@yield('modal-edit-label')</h4>
                <button type="button" class="close" onclick="$('#editUserModal').modal('toggle');"
                        aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <div class="modal-header bg-primary" id="header-create-employee">
                <h4 class="modal-title white" id="createUserLabel">@yield('modal-add-label')</h4>
                <button type="button" class="close" onclick="$('#editUserModal').modal('toggle');"
                        aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form action="" id="archiveUserForm">
                @csrf
                <input type="hidden" name="_method" value="PATCH" id="archive-form-method">
                <input hidden id="edit-user_id" value="{{$data->id ?? ''}}">
            </form>

            <form action="" id="editUserForm">
                @csrf
                <input hidden name="_method" value="POST" id="employee-form-method">
                <input hidden name="isRedirect" value="1" id="employee-form-is-need-redirect">
                <input hidden name="name" id="edit-username" value="{{$data->name ?? ''}}">
                <input hidden id="edit-user_id" value="{{$data->id ?? ''}}">
                <input hidden id="edit-is_archive" value="{{$data->id ?? ''}}" name="is_archive">



                <div class="modal-body" style="height:calc(100vh - 310px);">
                    <ul class="nav nav-tabs" id="editFormTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="edit-basic-tab" data-bs-toggle="tab" href="#edit-basic" role="tab" aria-controls="edit-basic" aria-selected="false">{{ __('Basic information') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="edit-address-tab" data-bs-toggle="tab" href="#edit-address" role="tab" aria-controls="edit-address" aria-selected="true">{{ __('Addresses & Extra contacts') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="edit-physical-tab" data-bs-toggle="tab" href="#edit-physical" role="tab" aria-controls="edit-physical" aria-selected="true">{{ __('Physical parameters') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="edit-comments-tab" data-bs-toggle="tab" href="#edit-comments" role="tab" aria-controls="edit-comments" aria-selected="true">{{ __('Photo & Comment') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="editFormTabContent">
                        <div class="tab-pane fade active show" id="edit-basic" role="tabpanel" aria-labelledby="edit-basic-tab">

                            <div class="">


                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapseName"
                                     aria-expanded="false"
                                     aria-controls="collapseName"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingName" style="box-shadow: 0 0 0 0">
                                            {{ __("Name in English") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapseName"
                                     aria-labelledby="headingName">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label>{{ __("Firstname")  }} (en): </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-firstname"
                                                       name="firstname"
                                                       required
                                                       value="{{ $data->firstname ?? '' }}"
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label>{{ __("Lastname")  }} (en): </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-lastname"
                                                       name="lastname"
                                                       required
                                                       value="{{ $data->lastname ?? '' }}"
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapseNameRu"
                                     aria-expanded="false"
                                     aria-controls="collapseNameRu"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingNameRu" style="box-shadow: 0 0 0 0">
                                            {{ __("Name in Russian") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapseNameRu"
                                     aria-labelledby="headingNameRu">

                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Firstname")  }} (ru): </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-firstname_ru"
                                                       name="firstname_ru"
                                                       required
                                                       value="{{ $data->firstname_ru ?? '' }}"
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Patronymic")  }} (ru): </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-patronymic"
                                                       name="patronymic"
                                                       value="{{ $data->patronymic ?? '' }}"
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Lastname")  }} (ru): </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-lastname_ru"
                                                       name="lastname_ru"
                                                       required
                                                       minlength="2"
                                                       value="{{ $data->lastname_ru ?? '' }}"
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapsePlaceBirth"
                                     aria-expanded="false"
                                     aria-controls="collapsePlaceBirth"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingPlaceBirth" style="box-shadow: 0 0 0 0">
                                            {{ __("Place of birth") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapsePlaceBirth"
                                     aria-labelledby="headingPlaceBirth">


                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <label>{{ __("Place of birth (civil passport)")  }}: </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-place-birth-ru"
                                                       name="place_birth_ru"
                                                       required
                                                       value="{{ '' }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <label>{{ __("Place of birth (foreign passport)")  }}: </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-place-birth"
                                                       name="place_birth"
                                                       required
                                                       value="{{ '' }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapsePersonalInfo"
                                     aria-expanded="false"
                                     aria-controls="collapsePersonalInfo"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingPersonalInfo" style="box-shadow: 0 0 0 0">
                                            {{ __("Personal information") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapsePersonalInfo"
                                     aria-labelledby="headingPersonalInfo">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <label>{{ __("Citizenship")  }}: </label>

                                            <div class="form-group">
                                                <select class="form-select" name="citizenship" id="edit-citizenship">
                                                    <option value=""></option>
                                                    @foreach(config('enums.citizenship') as $country)
                                                        <option value="{{ $country }}"
                                                        @if (isset($data)){{( $country == $data->citizenship)  ? "selected" : ''}} @endif>
                                                            {{ __($country) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <label>{{ __("Sex")  }}: </label>

                                            <div class="form-group">
                                                <select class="form-select" name="sex" id="edit-sex">
                                                    <option value=""></option>
                                                    @foreach(config('enums.sex') as $sex)
                                                        <option value="{{ $sex }}"
                                                        @if (isset($data)){{( $sex  == $data->sex)  ? "selected" : ''}} @endif>

                                                            {{ __($sex) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <label>{{ __("Marital status")  }}: </label>

                                            <div class="form-group">
                                                <select class="form-select" name="marital_status" id="edit-marital_status">
                                                    <option value=""></option>
                                                    @foreach(config('enums.marital_status') as $status)
                                                        <option value="{{ $status }}"
                                                        @if (isset($data)){{( $status  == $data->marital_status)  ? "selected" : ''}} @endif>
                                                            {{ __($status) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <label>{{ __("Birth date")  }}: </label>

                                            <div class="form-group">
                                                <input class="form-control datepicker"
                                                       autocomplete="off"
                                                       id="edit-date_birth"
                                                       name="date_birth"
                                                       required
                                                       min="{{ date('Y-m-d',strtotime("-100 years")) }}"
                                                       max="{{ date('Y-m-d',strtotime("-18 years")) }}"
                                                       value="{{ $data->date_birth ?? '' }}">
                                                <span id="date-inline"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>





                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapseContacts"
                                     aria-expanded="false"
                                     aria-controls="collapseContacts"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingContacts" style="box-shadow: 0 0 0 0">
                                            {{ __("Contacts") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapseContacts"
                                     aria-labelledby="headingContacts">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Email")  }}: </label>
                                            <div class="form-group">
                                                <input type="email"
                                                       class="form-control"
                                                       id="edit-email"
                                                       name="email"
                                                       required
                                                       value="{{ $data->email ?? '' }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Mobile phone")  }}: </label>
                                            <div class="form-group">
                                                <input type="tel"
                                                       class="form-control"
                                                       id="edit-mobile_phone"
                                                       name="phone"
                                                       value="{{ isset($data) && $data->phone ? "+$data->phone" : '' }}"
                                                       oninput="this.value = this.value.replace(/[^+0-9\(\)]/g, '');"
                                                       onfocus="this.placeholder='{{ __('Only numbers, "+" and "()"') }}'"
                                                       onblur="this.placeholder='';"
                                                       onclick="phoneChange(this.id, true);"
                                                       onfocusout="phoneChange(this.id);"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Internal phone no.")  }}: </label>
                                            <div class="form-group">
                                                <input type="number"
                                                       class="form-control"
                                                       id="edit-internal_phone"
                                                       name="internal_phone"
                                                       value="{{ $data->internal_phone ?? '' }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>{{ __("Skype")  }}: </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-skype_login"
                                                       name="skype_login"
                                                       value="{{ $data->skype_login ?? '' }}"
                                                >
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapsePosition"
                                     aria-expanded="false"
                                     aria-controls="collapsePosition"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingPosition" style="box-shadow: 0 0 0 0">
                                            {{ __("Position") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapsePosition"
                                     aria-labelledby="headingPosition">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label>{{ trans_choice("Companies", 1)  }}: </label>

                                            <div class="form-group">
                                                <select class="form-select" form="editUserForm" name="company_id" id="edit-company_id">
                                                    <option value=""></option>
                                                    @foreach(\App\Models\Company::where('is_archive', false)->get()->sortBy('displayName') as $company)
                                                        <option value="{{ $company->id }}"
                                                        @if (isset($data)){{( $company->id  == $data->company_id)  ? "selected" : ''}} @endif>
                                                            {{ __($company->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @yield('position-and-rank')

                                        <div class="col-md-6 col-12">
                                            <label>{{ __("From date")  }}: </label>

                                            <div class="form-group">
                                                <input class="form-control datepicker" name="date_from" id="edit-date_from" autocomplete="off"
                                                       value="{{ $data->date_from ?? ''}}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade " id="edit-address" role="tabpanel" aria-labelledby="edit-address-tab">

                            <div class="">

                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapseAddresses"
                                     aria-expanded="false"
                                     aria-controls="collapseAddresses"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingAddressRegistration" style="box-shadow: 0 0 0 0">
                                            {{ __("Addresses") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapseAddresses"
                                     aria-labelledby="headingAddressRegistration">


                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <label>{{ __("Address of registration")  }}: </label>
                                            <div class="form-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-address-registration"
                                                       name="registration_address"
                                                       required
                                                       value="{{ '' }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12 col-12">

                                            <label class="mb-1">{{ __("Actual address")  }}:
                                                <button class="btn btn-outline-light btn-sm icon mb-1" id="btn-copy-address">
                                                    <i class="fa-solid fa-paste"></i>
                                                    {{ __("Copy from registration address") }}
                                                </button>
                                            </label>

                                            <div class="form-group">

                                                <input type="text"
                                                       class="form-control"
                                                       id="edit-address-actual"
                                                       name="actual_address"
                                                       required
                                                       value="{{ '' }}"
                                                >
                                                <br>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <label>{{ __("Homeport")  }}: </label>
                                            <div class="form-group">
                                                <select class="form-select" name="homeport_id" form="editUserForm"  id="edit-select-homeport">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="divider btn-collapse"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapseExtraContacts"
                                     aria-expanded="false"
                                     aria-controls="collapseExtraContacts"
                                     role="button">
                                    <div class="divider-text">
                                        <a href="#" class="btn" id="headingExtraContacts" style="box-shadow: 0 0 0 0">
                                            {{ __("Extra Contacts") }}
                                            <i class="fa-solid fa-angle-up"></i>
                                        </a>

                                    </div>
                                </div>

                                <div class="collapse show"
                                     id="collapseExtraContacts"
                                     aria-labelledby="headingExtraContacts">
                                    <div class="row" id="add-contact-row">
                                        <div class="col-md-4 col-12" id="add-contact-div">
                                            <label></label>
                                            <div class="form-group input-group input-group-sm">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">{{ __("Add Contact")  }}</span>
                                                <select class="form-select" id="add-extra-contact">
                                                    <option value=""></option>
                                                    @foreach(\App\Enums\ContactType::asArray() as $key=>$contact)
                                                        <option value="{{ $key }}">
                                                            {{ __($contact) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="edit-physical" role="tabpanel" aria-labelledby="edit-physical-tab">
                            <div class="divider  btn-collapse"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#collapsePhysicalParams"
                                 aria-expanded="false"
                                 aria-controls="collapsePhysicalParams"
                                 role="button">
                                <div class="divider-text">
                                    <a href="#" class="btn" id="headingPhysicalParams" style="box-shadow: 0 0 0 0">
                                        {{ __("Employee's physical parameters") }}
                                        <i class="fa-solid fa-angle-up"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="collapse show"
                                 id="collapsePhysicalParams"
                                 aria-labelledby="headingPhysicalParams">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <label>{{ __('Jacket size')  }}: </label>

                                        <div class="form-group">
                                            <select class="form-select" name="jacket_size" id="edit-jacket_size">
                                                <option value=""></option>
                                                @foreach(config('enums.jacket_size') as $size)
                                                    <option value="{{ $size }}"
                                                    @if (isset($data)){{( $size  == $data->jacket_size)  ? "selected" : ''}} @endif>
                                                        {{ __($size) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>{{ __('Trousers size')  }}:
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"  title="{{ __("Для определения размера можно воспользоваться одним из двух способов:
1. Вычесть цифру 16 из своего 'российского' размера брюк, например 46-16=W30.
2. Ориентироваться на размер джинсов, который обычно покупаете.") }}">
                                        <i class="fa-solid fa-circle-question text-primary"></i>
                                    </span>
                                        </label>
                                        <div class="form-group position-relative">
                                            <select class="form-select" name="trousers_size" id="edit-trousers_size">
                                                <option value=""></option>
                                                @foreach(config('enums.trousers_size') as $size)
                                                    <option value="{{ $size }}"
                                                    @if (isset($data)){{( $size  == $data->trousers_size)  ? "selected" : ''}} @endif>

                                                        {{ __($size) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label>{{ __('Shoe size')  }}: </label>
                                        <div class="form-group position-relative">
                                            <select class="form-select" name="shoe_size" id="edit-shoe_size">
                                                <option value=""></option>
                                                @foreach(range(35, 50, 0.5) as $size)
                                                    <option value="{{ $size }}"
                                                    @if (isset($data)){{( $size  == $data->shoe_size)  ? "selected" : ''}} @endif>
                                                        {{ __($size) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>


                                </div>

                                <div class="divider"></div>

                                <div class="row">
                                    <div class="col-md-3 col-12">
                                        <label>{{ __('Height')  }}: </label>

                                        <div class="input-group">
                                            <input type="number" min="50" max="250" class="form-control" name="height" id="edit-height" value="{{ $data->height ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>{{ __('Weight')  }}: </label>

                                        <div class="input-group">
                                            <input type="number" min="20" max="250" class="form-control" name="weight" id="edit-weight" value="{{ $data->weight ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>{{ __('Hair color')  }}: </label>
                                        <div class="input-group">
                                            <select class="form-select" name="hair_color" id="edit-hair_color">
                                                <option value=""></option>
                                                @foreach(config('enums.hair_color') as $color)
                                                    <option value="{{ $color }}"
                                                    @if (isset($data)){{( $color  == $data->hair_color)  ? "selected" : ''}} @endif>

                                                        {{ __($color) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>{{ __('Eye color')  }}: </label>

                                        <div class="input-group">
                                            <select class="form-select" name="eye_color" id="edit-eye_color">
                                                <option value=""></option>
                                                @foreach(config('enums.eye_color') as $color)
                                                    <option value="{{ $color }}"
                                                    @if (isset($data)){{( $color  == $data->eye_color)  ? "selected" : ''}} @endif>
                                                        {{ __($color) }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                </div>

                                <div class="divider"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="edit-comments" role="tabpanel" aria-labelledby="edit-comments-tab">

                            <div class="divider btn-collapse"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#collapsePhoto"
                                 aria-expanded="false"
                                 aria-controls="collapsePhoto"
                                 role="button">
                                <div class="divider-text">
                                    <a href="#" class="btn" id="headingPhoto" style="box-shadow: 0 0 0 0">
                                        {{ __("Photo") }}
                                        <i class="fa-solid fa-angle-up"></i>
                                    </a>

                                </div>
                            </div>

                            <div class="collapse show"
                                 id="collapsePhoto"
                                 aria-labelledby="headingPhoto">
                                <div class="row">
                                    <div class="col-md-3 employee-photo">
                                        @if (isset($photo))
                                            <img class="img-fluid" src="data:image/png;base64,{!! $photo !!} " alt="employee photo" id="photo">
                                        @else
                                            <img class="img-fluid"  id="photo">

                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-file">
                                            <label>Photo <input id="upload-edit" form="editUserForm" type="file" accept="image/*"></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="upload-div-edit">

                                    </div>
                                </div>

                            </div>

                            @can('seeComment', \App\Models\User::class)
                            <div class="divider  btn-collapse"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#collapseComment"
                                 aria-expanded="false"
                                 aria-controls="collapseComment"
                                 role="button">
                                <div class="divider-text">
                                    <a href="#" class="btn" id="headingComment" style="box-shadow: 0 0 0 0">
                                        {{ __("Comment") }}
                                        <i class="fa-solid fa-angle-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse show"
                                 id="collapseComment"
                                 aria-labelledby="headingComment">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <textarea name="comment" id="edit-comment" class="form-control" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endcan
                        </div>

                    </div>



                </div>


                <div class="modal-footer">
                    <div id="modal-save-footer" style="display: none">

                        <button type="button" class="btn btn-danger"
                                onclick="$('#editUserModal').modal('toggle');">
                            {{ __('Cancel') }}
                        </button>

                        <button type="submit" form="editUserForm" class="btn btn-success ml-1" id="btn-save">
                            {{ __('Save') }}
                        </button>
                        <button type="submit" form="createUserForm" class="btn btn-outline-success ml-1" id="btn-create-save-open-docs">
                            {{ __("Save & Open employee's documents") }}
                        </button>
                        <button type="submit" form="createUserForm" class="btn btn-success ml-1" id="btn-create-save">
                            {{ __('Save') }}
                        </button>


                    </div>

                    <div id="modal-paginator">
                        <button type="button" class="btn btn-primary icon prev-tab" style="display: none;">
                            <i class="fa-solid fa-angle-left"></i>
                            <span id="prev-btn-span">{{ __('Prev') }}</span>

                        </button>
                        <button type="button" class="btn btn-primary icon next-tab">
                            <span id="next-btn-span">{{ __('Addresses & Extra contacts')  }}</span>
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>


@push('scripts-body')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        $("#edit-basic-tab").on('show.bs.tab', function (e){
            $('.prev-tab').hide();
            $('.next-tab').show();
            $('#modal-save-footer').hide();
            $('#modal-paginator').show();

            $('#prev-btn-span').text($(this).parent().prev().text())

            $('#next-btn-span').text($(this).parent().next().text())
        });

        $("#edit-address-tab,#edit-physical-tab").on('show.bs.tab', function (e){
            $('.prev-tab').show();
            $('.next-tab').show();


            $('#modal-save-footer').hide();
            $('#modal-paginator').show();
            $('#prev-btn-span').text($(this).parent().prev().text())

            $('#next-btn-span').text($(this).parent().next().text())

        });

        $("#edit-comments-tab").on('show.bs.tab', function (e){
            $('#modal-save-footer').show();
            $('#prev-btn-span').text($(this).parent().prev().text())
            $('.next-tab').hide();

        });

        $('.next-tab').click(function() {
            const nextTabLinkEl = $('#editFormTabs .active').closest('li').next('li').find('a');

            nextTabLinkEl.tab('show');

        });

        $('.prev-tab').click(function() {
            const prevTabLinkEl = $('#editFormTabs .active').closest('li').prev('li').find('a');
            // const prevTab = new bootstrap.Tab(prevTabLinkEl);
            prevTabLinkEl.tab('show');
        });


        const companyChoices = new Choices(document.getElementById('edit-company_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        const homePortChoices = new Choices(document.getElementById('edit-select-homeport'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
            searchEnabled: true,
            searchFields: ['label', 'value'],
            position: 'bottom'
        });
        const shoesChoices = new Choices(document.getElementById('edit-shoe_size'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });
        const trousersChoices = new Choices(document.getElementById('edit-trousers_size'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });
        const jacketChoices = new Choices(document.getElementById('edit-jacket_size'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });



        $('#edit-department_id').on('change', function () {
            let department_id = $(this).val();
            let is_department_set = !(department_id == '' || department_id == 'null' || department_id == null);
            positionChoices.removeActiveItems();

            positionChoices.clearChoices();
            positionChoices.setChoices(async () => {
                try {
                    if(! is_department_set) {
                        $('#position-div').hide()
                        return [];
                    }
                    $('#position-div').show()
                    const items = await fetch("/department/" + department_id + "/positions");
                    return items.json();
                } catch (err) {
                    console.error(err);
                }
            }, 'id', 'displayName', true);

        });

        $('#edit-company_id').on('change', function (){
            let company_id = $(this).val();
            let is_company_set = !(company_id == '' || company_id == 'null' || company_id == null);

            if (document.getElementById('edit-department_id')) {
                departmentsChoices.removeActiveItems();
                departmentsChoices.clearChoices();
                departmentsChoices.setChoices(async () => {
                    try {
                        if(! is_company_set) {
                            $('#department-div').hide()
                            $('#position-div').hide()
                            return [];
                        }
                        $('#department-div').show()
                        const items = await fetch("/company/" + company_id + "/departments");
                        return items.json();
                    } catch (err) {
                        console.error(err);
                    }
                }, 'id', 'displayName', true);
                $('#edit-department_id').trigger('change');
            }

        });


        $(document).ready(function() {
            $(document).on('click', "#edit-item", function() {
                $(this).addClass('edit-item-trigger-clicked');
                var el = $("a.edit-item-trigger-clicked");
                var row = el.closest(".data-row");
                var id = el.data('user-id');
                updateEditForm(id);

            })

        } );

        function updateEditForm(id){
            $.ajax({
                url: '/users/toJson/' + id,
                method: 'get',
                dataType: 'json',
                success: function(data){
                    $("#edit-username").val(data['name']);
                    $("#edit-user_id").val(data['id']);

                    if(data['is_archive']) {
                        $('#edit-is_archive').val(1);
                    } else {
                        $('#edit-is_archive').val('');
                    }

                    $("#edit-employee_type").val(data['employee_type']);
                    $("#edit-lastname").val(data['lastname']);
                    $("#edit-firstname").val(data['firstname']);
                    $("#edit-lastname_ru").val(data['lastname_ru']);
                    $("#edit-firstname_ru").val(data['firstname_ru']);
                    $("#edit-patronymic").val(data['patronymic']);
                    $("#edit-date_birth").val(data['date_birth']);

                    $("#edit-email").val(data['email']);
                    $("#edit-mobile_phone").val(data['phone'] ? "+" + data['phone'] : data['phone']);
                    $("#edit-skype_login").val(data['skype_login']);
                    $("#edit-internal_phone").val(data['internal_phone']);

                    $("#edit-comment").val(data['comment']);

                    $('#edit-address-registration').val([data['registration_address']])
                    $('#edit-address-actual').val([data['address_actual']])

                    $('#edit-place-birth-ru').val(data['place_birth_ru']);
                    $('#edit-place-birth').val(data['place_birth']);


                    $("#edit-citizenship").val(data['citizenship']).change();

                    $("#edit-sex").val(data['sex']).change();
                    $("#edit-marital_status").val(data['marital_status']).change();

                    companyChoices.setChoiceByValue(data['company_id']);
                    $('#edit-company_id').trigger('change');

                    if (document.getElementById('edit-department_id')) {
                        departmentsChoices.setChoices(
                            [
                                { value: data['department_id'], label: data['departmentName'] },

                            ],
                            'value',
                            'label',
                            true,
                        );
                        departmentsChoices.setChoiceByValue(data['department_id']);
                    }

                    homePortChoices.setChoiceByValue(data['homeport_id']);

                    $('#edit-department_id').trigger('change');

                    if (document.getElementById('edit-position_id')) {
                        positionChoices.setChoices(
                            [
                                { value: data['position_id'], label: data['positionName'] },

                            ],
                            'value',
                            'label',
                            true,
                        );
                        positionChoices.setChoiceByValue(data['position_id']);
                    }


                    if (document.getElementById('edit-rank_id')) {

                        rankChoices.setChoiceByValue(data['rank_id']);
                    }


                    $("#edit-date_from").val(data['date_from']);


                    data['allExtraContacts'].forEach((item, index) => {
                        let newDiv = '<div class="col-md-4 col-12 extra-contacts">' +
                            '<label>Extra '+ item.contactType +':</label>' +
                            '<div class="form-group"> ' +
                            '<input form="editUserForm" type="'+item.type+'" ' +
                            'placeholder="'+ capitalizeFirstLetter(item.contactType) +'" class="form-control extra_'+ item.type +'" ' +
                            ' name="extra_'+ item.type +'['+index+']" id="extra_'+ item.type +'['+index+']"';

                        if (item.type === "PHONE") {
                            value = '+' + item.contact
                            newDiv = newDiv + ' oninput="this.value = this.value.replace(/[^+0-9\(\)]/g, \'\');"' +
                                '  onblur="this.placeholder=\'\';"' +
                                ' onclick="phoneChange(this.id, true);"' +
                                ' onfocusout="phoneChange(this.id);"' +
                                ' value="'+value+'"';
                        } else {
                            newDiv = newDiv + ' value="'+item.contact+'"'
                        }
                        newDiv = newDiv + '></div> </div>';
                        $('#add-contact-div').after(newDiv);
                    });

                    jacketChoices.setChoiceByValue(data['jacket_size']);
                    trousersChoices.setChoiceByValue(data['trousers_size']);
                    shoesChoices.setChoiceByValue(data['shoe_size']);

                    $("#edit-hair_color").val(data['hair_color']);
                    $("#edit-eye_color").val(data['eye_color']);
                    $("#edit-height").val(data['height']);
                    $("#edit-weight").val(data['weight']);
                    if(data['photo']){
                        $("#photo").attr('src', "data:image/png;base64, " + data['photo']).show();
                    } else {
                        $("#photo").hide();
                    }
                },
            });
        }

        $uploadCropEdit = $('#upload-div-edit').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: 250,
                height: 250
            }
        }).hide();

        $('#upload-edit').on('change', function () {
            let reader = new FileReader();
            reader.onload = function (e) {
                $uploadCropEdit.croppie('bind', {
                    url: e.target.result
                })
            }
            reader.readAsDataURL(this.files[0]);
            $('#upload-div-edit').show();
        });

        $('#editUserModal')
            .on('show.bs.modal', function() {
                let el = $("a.edit-item-trigger-clicked");


                $.ajax({
                    url: '/ports',
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        homePortChoices.setChoices(data, 'id', 'displayName', true);
                    }
                });



            })
            .on('hidden.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked');
                // $("#editUserForm").trigger("reset");
                $('.extra-contacts').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');

                $('.upload-demo').removeClass('ready');
                $('#upload-edit').val('');
                $('#upload-div-edit').hide();
                $('#photo').attr('src', '');
                $('#edit-basic-tab').tab('show');
            })


        $('#editUserModal :input').on('change', function(e){
            $(this).removeClass('is-invalid');
            $(this).removeClass('is-valid');
        });





        function sendForm(e, url, user_id=null, modalId, formId, open_docs=false){
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.is-valid').removeClass('is-valid');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            let myform = document.getElementById(formId);
            let fd = new FormData(myform);

            let fileName = $("#upload-edit").val();
            if (fileName) {

                $uploadCropEdit.croppie('result', 'blob').then(function(blob) {
                    fd.set('photo_file', blob);
                    sendAjax(url, fd, modalId, user_id, open_docs);

                    return;
                });


            }
            sendAjax(url, fd, modalId, user_id, open_docs);


        }

        function sendAjax(url, fd, modalId, user_id=null, open_docs=false) {
            $.ajax({
                url: url,
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (open_docs) {
                        window.location.href = "/employees/" + data.slug + "#documents";
                        return;
                    }
                    if (user_id) {
                        let row = $("a.edit-item-trigger-clicked").closest('tr');
                        if (typeof updateRow === "function") {
                            updateRow(row, user_id);
                        }
                        if (typeof updateEmployee === "function") {
                            window.location.reload();
                            updateEmployee(user_id);
                        }

                    } else {
                        if (fd.get('isRedirect') !== '1') {
                            $('#editUserModal').modal('toggle')
                        } else {
                            window.location.href = "/employees/" + data.slug;
                        }
                    }

                    $('#'+modalId).modal('hide');
                },
                error: function (err) {

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
                        $('#'+modalId+' :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    } else if (err.status === 401) {
                        window.location.reload();
                    }
                }
            });
        }


        $("#btn-archive-employee-confirm").click(function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            let formId = 'archiveUserForm';

            let myform = document.getElementById(formId);
            let fd = new FormData(myform);

            $.ajax({
                url: "{{ isset($user) ? route('users.archive', ['user' => $user->slug]) : ''}}",
                data: fd,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {

                    if(data['route']){
                        window.location.href = data['route']
                    } else {
                        window.location.reload();

                    }

                },
                error: function (err) {

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
                        $('#'+modalId+' :input').filter(function () {
                            return $.trim($(this).val()).length > 0
                        }).addClass('is-valid');
                    } else if (err.status === 401) {
                        window.location.reload();
                    }
                }
            });

        });

        $("#btn-save").click(function (e) {
            let user_id = $("#edit-user_id").val();
            let url = "/employees/" + user_id;
            let modalId = "editUserModal";
            let formId = "editUserForm";
            $('#employee-form-method').val('PATCH');

            $('#createUserForm').attr('id', 'editUserForm');
            $('select[form="createUserForm"]').attr('form', 'editUserForm');
            $('input[form="createUserForm"]').attr('form', 'editUserForm');

            sendForm(e, url, user_id, modalId, formId);
        });

        function updateSelectField(select, url){
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data){
                    let old_val = select.val();
                    select.empty().append($("<option></option>"));
                    $.each(data, function(key,value) {
                        select.append($("<option></option>")
                            .attr("value", value['id']).text(value['name']))
                            .val(old_val);
                    });
                }
            });
        }



        $('#add-extra-contact').change(function (){
            if ($(this).val() == null) {
                return;
            }
            let class_name = '.extra_'+$(this).val();
            let id = $(class_name).length;
            let label = $('#add-extra-contact :selected').text();
            let newDiv = '<div class="col-12 extra-contacts">' +
                '<div class="form-group"> ' +
                '<label>Extra ' + label + ':</label>' +
                '<input class="form-control extra_' + $(this).val() + '" form="editUserForm" type="' +
                ($(this).val() === "PHONE" ? "tel" : ($(this).val() === "EMAIL" ? "email" : "text")) +
                '" name="extra_' + $(this).val() + '[' + id + ']" id="extra_' + $(this).val() + '[' + id + ']"';
            if ($(this).val() === "PHONE") {
                newDiv = newDiv + ' oninput="this.value = this.value.replace(/[^+0-9\(\)]/g, \'\');"' +
                    '  onblur="this.placeholder=\'\';"' +
                    ' onclick="phoneChange(this.id, true);"' +
                    ' onfocusout="phoneChange(this.id);"' +
                    ' value=""'
            }
            newDiv = newDiv + '></div> </div>';

            $('#add-contact-div').after(newDiv);
            $(this).val('');
        });


        $('#btn-copy-address').on('click', function (e){
            e.preventDefault();

            $('#edit-address-actual').val($('#edit-address-registration').val());
        });

        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: 'Предыдущий',
            nextText: 'Следующий',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Не',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };

        let date = $('edit-date_birth').val();
        // $.datepicker.setDefaults($.datepicker.regional['ru']);
        $("#edit-date_birth").datepicker({
            maxDate: "-18Y",
            minDate: "-100Y",
            dateFormat: 'dd.mm.yy',
            changeYear: true,
            yearRange: "-100:-18",
            defaultDate: date,
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

        $("#edit-date_from").datepicker({
            dateFormat: 'dd.mm.yy',
            changeYear: true,
            defaultDate: "today",
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
    </script>


    <script>
        $("#edit-address-actual, #edit-address-registration").suggestions({
            token: "9ab2f4cd6166203eebaec3bce6f157e1590b52f1",
            type: "ADDRESS",
            /* Вызывается, когда пользователь выбирает одну из подсказок */
            onSelect: function(suggestion) {
                $(this).val(suggestion['unrestricted_value'])
            }
        });

        function phoneChange(id, isClick = false)
        {
            let input = document.getElementById(id);
            let phone = input.value
            const firstChar = phone.charAt(0);
            if (firstChar !== "+") {
                phone = "+" + phone
            }
            console.log(isClick)
            console.log(phone === '+')
            if (phone === '+' && !isClick) {
                phone = ''
            }

            input.value = phone
        }

    </script>


@endpush
