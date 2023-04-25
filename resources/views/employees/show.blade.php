@extends('profile.show')
@section('title')
    {{$user->displayName}} — Crew Master
@endsection
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item">
                @if($user->employee_type == config('enums.employee_type.office_employees'))
                    <a href="{{ route('employees.index') }}">{{ trans_choice("Employees", 2) }}</a>
                @elseif($user->employee_type == config('enums.employee_type.office_archive'))
                    <a href="{{ route('employees.index') }}">{{ trans_choice("Archived Employees", 2) }}</a>
                @elseif($user->employee_type == config('enums.employee_type.partners'))
                    <a href="{{ route('partners.index') }}">{{ trans_choice("Partners", 2) }}</a>
                @endif
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->displayName }}</li>
        </ol>
    </nav>
@endsection


@section('profile_info')
    @if($user->positionName)
    <span class='text-sm'>
        <b>{{ $user->positionName ?? ''}} | </b>
        <b class="company-row" onclick="window.location='{{route('companies.show', ['company'=>$user->company])}}/#employees#list-{{Str::slug($user->position->department->name)}}'">{{ $user->departmentName ?? ''}} </b>
        @if($user->is_seaman)
            <a class="btn btn-sm btn-outline-light icon icon-left" href="{{ route('seamen.show', ['seaman' => $user->slug]) }}" ><i class="fa-solid fa-anchor"></i> {{ __('Seaman profile') }}</a>
        @endif
    </span>
    @else
    <span class='text-sm'>
        @if($user->is_seaman)
            <a class="btn btn-sm btn-outline-light icon icon-left" href="{{ route('seamen.show', ['seaman' => $user->slug]) }}" ><i class="fa-solid fa-anchor"></i> {{ __('Seaman profile') }}</a>
        @endif
    </span>
    @endif


    @if($user->employee_type !== config('enums.employee_type.partners'))
    <br>

    @endif

@endsection


@section('nav-tabs')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile"
           role="tab" aria-controls="profile" aria-selected="true">{{ __('Profile') }}</a>
    </li>

    @can('viewAny', \App\Models\Document::class)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents"
               role="tab" aria-controls="documents" aria-selected="false"> {{ trans_choice('Documents', 2) }}</a>
        </li>
    @elseif(auth()->user()->id == $user->id)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents"
               role="tab" aria-controls="documents" aria-selected="false"> {{ trans_choice('Documents', 2) }}</a>
        </li>
    @endcan

    @if ($user->employee_type !== config('enums.employee_type.partners'))
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="previous-service-tab" data-bs-toggle="tab" href="#previous-service"
           role="tab" aria-controls="previous-service" aria-selected="false"> {{ trans_choice('Previous service', 2) }}</a>
    </li>


        @if(auth()->user()->can('viewAny', \App\Models\UserRelative::class) or auth()->user()->id == $user->id)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="relatives-tab" data-bs-toggle="tab" href="#relatives"
                   role="tab" aria-controls="relatives" aria-selected="false"> {{ __('Relatives') }}</a>
            </li>
        @endif

        @if(auth()->user()->can('viewAny', \App\Models\Document::class) or auth()->user()->id == $user->id)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="bank-cards-tab" data-bs-toggle="tab" href="#bank-cards"
                   role="tab" aria-controls="bank-cards" aria-selected="false"> {{ trans_choice('Bank cards', 2) }}</a>
            </li>
        @endif
    @else
        @can('seeNotes', \App\Models\User::class)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="notes-tab" data-bs-toggle="tab" href="#notes"
                   role="tab" aria-controls="notes" aria-selected="false"> {{ __('Notes') }}
                    @if(count($notes = $user->notes?->sortByDesc('created_at')))
                        <span class="badge {{ $notes->where('attention', true)->count() > 0 ? 'bg-danger' : 'bg-light' }}">{{ count($notes) }}</span>
                    @endif
                </a>

            </li>
        @endcan
    @endif
</ul>
@endsection

@section('tab-content')
    @include('employees.show-profile')

    @if(auth()->user()->can('viewAny', \App\Models\Document::class) or auth()->user()->id == $user->id)
        @include('profile.show-documents')
        @include('profile.show-bank-cards')
    @endif

    @if(auth()->user()->can('viewAny', \App\Models\UserRelative::class) or auth()->user()->id == $user->id)
        @include('profile.show-relatives')
    @endif

    @include('profile.show-previous-service')
    @include('employees.edit', ['data' => $user, 'photo' => $user->photo])

    @if ($user->employee_type === config('enums.employee_type.partners'))
    @can('seeNotes', \App\Models\User::class)
    @include('seamen.show-notes')
    @endcan
    @endif
@endsection
