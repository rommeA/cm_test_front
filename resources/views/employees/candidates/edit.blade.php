@extends('profile.edit-template')

@section('modal-edit-label')
    {{ __("Edit Employee Candidate Card") }}
@endsection

@section('modal-add-label')
    {{ __("Add Employee Candidate Card") }}
@endsection


@section('user-type')
    <div class="divider">
        <div class="divider-text">
            {{ __("Employee type") }}

        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>{{ __("Employee type")  }}: </label>

            <div class="form-group">
                <select class="form-select" name="employee_type" id="edit-employee_type">
                    <option value="{{ config('enums.employee_type.office_candidates') }}">{{ config('enums.employee_type.office_candidates') }}</option>
                    <option value="{{ config('enums.employee_type.office_employees') }}">{{ config('enums.employee_type.office_employees') }}</option>
                    <option value="{{ config('enums.employee_type.partners') }}">{{ config('enums.employee_type.partners') }}</option>

                </select>
            </div>
        </div>
    </div>
@endsection
