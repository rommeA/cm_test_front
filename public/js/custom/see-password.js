
$(document).ready(function () {
    $('#toggle-password').click(function () {
        $(this).is(':checked') ? $('#password').attr('type', 'text') : $('#password').attr('type', 'password');
    });
});

