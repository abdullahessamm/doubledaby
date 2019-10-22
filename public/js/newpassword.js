$(document).ready(function () {
    $('.form-control').on('keyup', function () {
        var length = $(this).val().length;
        if (length >= 8)
        {
            $('.btn').removeAttr('disabled');
            $('.btn').css('cursor', 'pointer')
        }
        else
        {
            $('.btn').attr('disabled', 'yes');
            $('.btn').css('cursor', 'not-allowed');
        }
    });
});