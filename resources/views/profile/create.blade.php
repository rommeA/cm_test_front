@push('scripts-body')
    <script>
        $('#createUserModal :input').on('change', function(e){
            $(this).removeClass('is-invalid');
            $(this).removeClass('is-valid');
        });

        $("#btn-create-save").click(function (e) {
            $('#employee-form-method').val('POST');
            $('#editUserForm').attr('id', 'createUserForm');
            $('select[form="editUserForm"]').attr('form', 'createUserForm');
            $('input[form="editUserForm"]').attr('form', 'createUserForm');


            let url = "employees";
            let modalId = "createUserModal";
            let formId = "createUserForm";

            sendForm(e, url, null, modalId, formId);
        });

        $('#btn-create-save-open-docs').click(function (e) {
            $('#editUserForm').attr('id', 'createUserForm');
            $('select[form="editUserForm"]').attr('form', 'createUserForm');
            $('input[form="editUserForm"]').attr('form', 'createUserForm');


            let url = "employees";
            let modalId = "createUserModal";
            let formId = "createUserForm";

            sendForm(e, url, null, modalId, formId, true);

        });

        $('#create-company_id').on('change', function (){
            let company_id = $(this).val();
            if (company_id === '') {
                $('#create-department_id').empty().attr('disabled', 'disabled');
                $('#create-position_id').empty().attr('disabled', 'disabled');
            } else {
                $('#create-department_id').removeAttr('disabled');
                $('#create-position_id').removeAttr('disabled');

                updateSelectField($('#create-department_id'), "/company/" + company_id + "/departments");
                updateSelectField($('#create-position_id'), "/company/" + company_id + "/positions");

            }
        });

        $('#create-department_id').on('change', function () {
            let department_id = $(this).val();
            if (department_id === '') {
                $('#create-position_id').empty().attr('disabled', 'disabled');
            } else {
                $('#create-position_id').removeAttr('disabled');
                updateSelectField($('#create-position_id'), "/department/" + department_id + "/positions");

            }

        });

        $('.add-extra-contact-select').change(function (){
            if ($(this).val() === '') {
                return;
            }
            let class_name = '.extra_'+$(this).val();
            let id = $(class_name).length;
            let label = $('.add-extra-contact-select :selected').text();
            let newDiv = '<div class="col-md-4 col-12 extra-contacts">' +
                '<label>Extra '+ label +':</label>' +
                '<div class="form-group"> ' +
                '<input class="form-control extra_'+ $(this).val()+'" form="createUserForm" type="'+
                ($(this).val() === "PHONE" ? "tel" : ($(this).val() === "EMAIL" ? "email" : "text")) +
                '" name="extra_'+ $(this).val() +'['+id+']" id="extra_'+ $(this).val() +'['+id+']">' +
                '</div> </div>';
            $('.add-extra-contact-div').after(newDiv);
            $(this).val('');
        });
    </script>
@endpush
