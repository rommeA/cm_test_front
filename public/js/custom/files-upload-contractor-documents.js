let pond = null;
let docID = null;

document.addEventListener('FilePond:init', (e) => {
//     docID = $('#input-document-id').val()
//     let url = '/documents/' + docID + '/scans';
    pond = e.detail.pond;
    pond.dropOnPage = true;
    pond.setOptions({
        allowImagePreview: true,
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
                request.open("POST", '/contractor-documents/' + docID + '/scans');
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
});

document.addEventListener('FilePond:addfile', (e) => {
    docID = $('#input-document-id').val()
});

document.addEventListener('FilePond:processfiles', (e) => {
    pond.removeFiles()
});
