@extends('profile.edit-template')

@section('modal-edit-label')
    {{ __("Edit Employee Card") }}
@endsection

@section('modal-add-label')
    {{ __("Add Employee Card") }}
@endsection

@section('position-and-rank')
    <div class="col-md-6 col-12" id="department-div" style="display: none">
        <label>{{ __("Department")  }}: </label>

        <div class="form-group">
            <select class="form-select" form="editUserForm" name="department_id" id="edit-department_id">

            </select>
        </div>
    </div>

    <div class="col-md-6 col-12" id="position-div" style="display: none">
        <label>{{ __("Position")  }}: </label>

        <div class="form-group" >
            <select class="form-select" form="editUserForm" name="position_id" id="edit-position_id">

            </select>
        </div>
    </div>
@endsection



@push('scripts-body')
    <script>
        const departmentsChoices = new Choices(document.getElementById('edit-department_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        const positionChoices = new Choices(document.getElementById('edit-position_id'), {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });
    </script>
@endpush
