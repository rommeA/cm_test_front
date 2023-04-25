@extends('layouts.app')
@section('title')
    Create Partner Form — Crew Master
@endsection

@section('content')
    @include('employees.partners.create')

@endsection

@push('scripts-body')
    <script>
        $('#partner-create-form').show();
        $('#partner-create-close-btn, #cancel-create-partner').on('click', function (e){
            e.preventDefault();
            window.location = "{{ route('partners.index') }}"
        })
    </script>
@endpush
