$(document).ready(function () {
    $('.password-control').on('click', function () {
        let $input = $(this).parent().find('input').first();
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $(this).addClass('view');
        } else {
            $input.attr('type', 'password');
            $(this).removeClass('view');
        }
        return false;
    });

    $('#inputPassword1, #inputPassword2').on('change', function () {
        let pass1 = $('#inputPassword1');
        let pass2 = $('#inputPassword2');
        if (pass1.val() !== pass2.val())
            pass2[0].setCustomValidity("Passwords Don't Match");
        else
            pass2[0].setCustomValidity('');
    });
});
