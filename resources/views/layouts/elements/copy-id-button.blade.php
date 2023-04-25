
<a href="#" class="btn btn-outline-light icon" id="btn-copy-doc-id"
   data-bs-toggle="tooltip" data-bs-placement="top" title="Copied!" data-bs-trigger="click"
>
    <i class="fa-solid fa-copy"></i>
    <span class="copy-doc-text">{{ __("ID") }}</span>

</a>



@push('scripts-body')
    <script>
        $('#btn-copy-doc-id')
            .on('click', function(e){

                e.preventDefault();
                $(this).tooltip('hide');
                let docID = $('#input-document-id').val();
                const cb = navigator.clipboard;
                cb.writeText(docID).then($(this).tooltip('show'));
            })
            .blur(function() {
                $(this).tooltip('hide');
            });

        $('#btn-edit-document').on('click', function (e) {

            $('#btn-copy-doc-id').hide();
        })

        $('#btn-reset').on('click', function (e) {

            $('#btn-copy-doc-id').show();
        })
    </script>
@endpush
