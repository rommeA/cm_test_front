
// let pond = null;
let uploadFileDocID = null;
let prevServID = null;



document.addEventListener('DOMContentLoaded', function() {

    FilePond.registerPlugin(FilePondPluginImagePreview);


    $('.multiple-files-filepond').filepond({
        credits: null,
        allowImagePreview: true,
        allowMultiple: true,
        acceptedFileTypes: ["image/*", "application/pdf"],
        allowImageExifOrientation: true,
        instantUpload: false,
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
                // Do custom type detection here and return with promise
                resolve(type);
            }),
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                // We ignore the metadata property and only send the file

                const formData = new FormData();
                formData.append('file', file);
                formData.append('filename', file.name);

                const request = new XMLHttpRequest();

                // you can change it by your client api key
                request.open("POST",'/documents/' + uploadFileDocID + '/scans');
                request.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector("[name=csrf-token]").content);
                request.upload.onprogress = (e) => {
                    progress(e.lengthComputable, e.loaded, e.total);
                };

                request.onload = function () {
                    if (request.status >= 200 && request.status < 300) {
                        load(request.responseText);
                    } else {
                        error("oh no");
                    }
                };

                request.onreadystatechange = function () {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            let response = JSON.parse(this.responseText);
                            // console.log(response);

                        } else {
                            console.log("Error", this.statusText);
                        }
                    }
                };
                request.send(formData);
            },
        },
    });
    $('.multiple-files-filepond').on('FilePond:addfile', function (e) {
        uploadFileDocID = $('#input-document-id').val()
    });
    $('.multiple-files-filepond').on('FilePond:processfiles', function (e) {
        $(this).filepond('removeFiles')
    });

    $('.multiple-files-filepond-ps').filepond({
        credits: null,
        allowImagePreview: true,
        allowMultiple: true,
        acceptedFileTypes: ["image/*", "application/pdf"],
        allowImageExifOrientation: true,
        instantUpload: false,
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                // We ignore the metadata property and only send the file

                const formData = new FormData();
                formData.append('file', file);
                formData.append('filename', file.name);

                const request = new XMLHttpRequest();

                // you can change it by your client api key
                request.open("POST",'/seamanPreviousService/' + prevServID + '/scans');
                request.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector("[name=csrf-token]").content);
                request.upload.onprogress = (e) => {
                    progress(e.lengthComputable, e.loaded, e.total);
                };

                request.onload = function () {
                    if (request.status >= 200 && request.status < 300) {
                        load(request.responseText);
                    } else {
                        error("oh no");
                    }
                };

                request.onreadystatechange = function () {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            let response = JSON.parse(this.responseText);
                            // console.log(response);

                        } else {
                            console.log("Error", this.statusText);
                        }
                    }
                };
                request.send(formData);
            },
        },
    });

    $('.multiple-files-filepond-ps').on('FilePond:addfile', function (e) {
        prevServID = $('#ps-edit-id').val()
    });

});











