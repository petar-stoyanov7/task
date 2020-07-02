var $form;
var $password1;
var $password2;
var $email1;
var $email2;

var _resetForm = function(){
    $('#register-form input').removeClass('form-error');
};

$(function() {
    $form = $('#register-form');
    $password1 = $('#password');
    $password2 = $('#password2');
    $email1 = $('#email');
    $email2 = $('#email2');

    $form.submit(function (e) {
        _resetForm();
        e.preventDefault();
        var isValid = _checkForm($form);
        if ($password1.val() !== $password2.val()) {
            $password1
                .val('')
                .addClass('form-error')
                .attr('placeholder', 'passwords don\'t match');
            $password2
                .val('')
                .addClass('form-error')
                .attr('placeholder', 'passwords don\'t match');
            isValid = false;
        }

        if ($email1.val() !== $email2.val()) {
            $email1
                .val('')
                .addClass('form-error')
                .attr('placeholder', 'email addresses don\'t match');
            $email2
                .val('')
                .addClass('form-error')
                .attr('placeholder', 'email addresses don\'t match');
            isValid = false;
        }

        if (isValid) {
            $(this).unbind('submit').submit();
        }
    });
});

