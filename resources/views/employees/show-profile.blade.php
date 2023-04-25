<div class="tab-pane fade show active px-0 py-0" id="profile" role="tabpanel"
     aria-labelledby="profile-tab">
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">

            <div class="card h-100">
                <div class="card-body pe-0">
                    <div class="table-responsive nopadding">
                        <table class="table table-borderless table-padding-sm">
                            <tr>
                                <td>{{ __('Date of birth') }}:</td>
                                <td>
                                    {{ $user->date_birth ? $user->date_birth->format('d.m.Y') :  __('Not specified')}}
                                    {{ $user->date_birth ? '(' .date_diff(date_create($user->date_birth), date_create('now'))->y  . ')' :  ''}}

                                </td>
                            </tr>
                            @if ($user->employee_type !== config('enums.employee_type.partners'))
                                <tr>
                                    <td>{{ __('Gender') }}:</td>
                                    <td> {{ __($user->sex) ?? __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Citizenship') }}:</td>
                                    <td> {{ $user->citizenship ?? __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Relationship') }}:</td>
                                    <td> {{ $user->marital_status ?? __('Not specified')}} </td>
                                </tr>
                            @endif
                            @if ($user->employee_type === config('enums.employee_type.partners'))
                                <tr>
                                    <td>{{ __('Country') }}:</td>
                                    <td> {{ $user->infoPartner?->country->name ?? __('Not specified')}} </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col ">
            <div class="card h-100">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-borderless table-padding-sm">
                            @if ($user->employee_type === config('enums.employee_type.partners'))
                                <tr>
                                    <td>{{ __('Contractor') }}:</td>
                                    <td>
                                        <a href="{{ $user->infoPartner?->contractor ? route('contractors.show', ['contractor' => $user->infoPartner?->contractor]) : '#'}}">{{ $user->infoPartner?->contractor?->realName}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Position') }}:</td>
                                    <td> {{ $user->infoPartner?->position ?? __('Not specified')}} </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ __('Place of birth (in Civil passport)') }}:</td>
                                    <td> {{ $user->place_birth_ru ?? __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Place of birth (in Foreign passport)') }}:</td>
                                    <td> {{ $user->place_birth ?? __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Address of registration') }}:</td>
                                    <td> {{ $user->registration_address ?? __('Not specified')}} </td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ __('Actual address') }}:</td>
                                <td> {{ $user->address_actual ?? __('Not specified')}} </td>
                            </tr>
                            @if ($user->employee_type !== config('enums.employee_type.partners'))
                                <tr>
                                    <td>{{ __('Homeport') }}:</td>
                                    <td> {{ $user->homeport ? $user->homeport->short_name . ' - ' . $user->homeport->name : __('Not specified')}} </td>
                                </tr>
                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row pe-0">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-borderless table-padding-sm">
                            @if ($user->employee_type !== config('enums.employee_type.partners'))
                                <tr>
                                    <td>{{ __('Height') }}:</td>
                                    <td> {{ $user->height ??  __('Not specified')}} </td>
                                    <td>{{ __('Trousers size') }}:</td>
                                    <td> {{ $user->trousers_size ??  __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Weight') }}:</td>
                                    <td> {{ $user->weight ??  __('Not specified')}} </td>
                                    <td>{{ __('Jacket size') }}:</td>
                                    <td> {{ $user->jacket_size ??  __('Not specified')}} </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Hair color') }}:</td>
                                    <td> {{ $user->hair_color ??  __('Not specified')}} </td>
                                    <td>{{ __('Shoe size') }}:</td>
                                    <td> {{ $user->shoe_size ??  __('Not specified')}} </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>{{ __('Eye color') }}:</td>
                                    <td> {{ $user->eye_color ?? __('Not specified')}} </td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ __('Comment') }}:</td>
                                <td> {{ $user->comment ?? __('')}} </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($user->employee_type !== config('enums.employee_type.partners'))
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-borderless table-padding-sm">

                                <tr>
                                    <td>{{ __('Company') }}:</td>
                                    <td>
                                        <a href="{{ $user->company ? route('companies.show', ['company' => $user->company]) : '#'}}">{{ $user->company?->displayName }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Experience in company') }}:</td>
                                    <td> {{ $user->innerExperienceYears }} {{ __('years') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Start date') }}:</td>
                                    <td> {{ $user->startDateAtCompany?->format('d.m.Y') ??  __('Not specified')}} </td>
                                </tr>
                                @can('seeComment', \App\Models\User::class)
                                    <tr>
                                        <td>{{ __('Comment') }}:</td>
                                        <td> {{ $user->comment ??  __('Not specified')}} </td>
                                    </tr>
                                @endcan
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif
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

</div>
