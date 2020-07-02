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
        isValid = isValid && _checkRegForm($password1, $password2, $email1, $email2);

        if (isValid) {
            $(this).unbind('submit').submit();
        }
    });
});

