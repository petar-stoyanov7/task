var $userId;
var $pwd1;
var $pwd2;
var $firstName;
var $lastName;
var $email1;
var $email2;
var $city;
var $sex;
var $checkBox;
var $username;

var $loginUser;
var $loginPass;

var userListCache = {};

var _getUsers = function()
{
    if ($.isEmptyObject(userListCache)) {
        _startLoading();
        $.ajax({
            type: 'POST',
            url: '/account/get-users',
            data: {
                'isAjax': 1
            },
            error: function(response) {
                console.log('error with usr extraction');
                console.log(response);
                _stopLoading();
            },
            success: function(data) {
                userListCache = JSON.parse(data);
            }
        }).done(function(){
            _stopLoading();
        });
    }
};

var _resetRegForm = function()
{
    $('#register-form input').val('');
    $('#register-form select').val('male');
};

var _resetLoginForm = function()
{
    $('#login-form input').val('');
};

var _resetRegErrors = function()
{
    $('.user-form div.form-wrapper').removeClass('form-error');
    $('.user-form div.form-wrapper span.form-error-message').remove();
};

var _resetLoginErrors = function()
{
    $('#login-form div.form-wrapper').removeClass('form-error');
    $('#login-form div.form-wrapper span.form-error-message').remove();
};

var _addError = function(element, text)
{
    var parentDiv = element.closest('div.form-wrapper');
    parentDiv.addClass('form-error');
    if (undefined !== text) {
        parentDiv.append('<span class="form-error-message">' + text + '</span>');
    }
};

var _checkLoginForm = function(){
    _resetLoginErrors();
    if ($loginUser.val().length === 0) {
        _addError($loginUser, 'Empty username');
    } else if ($loginPass.val().length === 0) {
        _addError($loginPass, 'Empty password');
    } else {
        _startLoading();
        var loginSuccess = false;
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/account/login',
            data: {
                username: $loginUser.val(),
                password: $loginPass.val()
            },
            error: function(response) {
                console.log('Error with login');
                console.log(response);
                _addError($loginUser, 'Error with login, please try agaion later!');
                _stopLoading();
            },
            success: function(data) {
                loginSuccess = data['success'];
            }
        }).done(function(){
            if (loginSuccess) {
                _stopLoading();
                window.location.replace('/');
            } else {
                _addError($loginUser, 'Invalid login');
                _addError($loginPass);
            }
            _stopLoading();
        });
    }
};

var _checkForm = function(isReg = false)
{
    var isValid = true;
    _resetRegErrors();

    if (undefined !== isReg && isReg) {
        //actions only for registering
        var userList = userListCache['users'];
        var emailList = userListCache['emails'];
        console.log(userList);
        if (/[^A-Za-z0-9]/.test($username.val())) {
            _addError($username, 'Invalid username - use chars and numbers only!');
        }
        if ($username.val() === '') {
            _addError($username, 'No username put')
        } else if ($username.val().length < 3 ) {
            _addError($username, 'Username too short');
        }
        if ($.inArray($username.val(), userList) !== -1 ) {
            _addError($username, '<b>' + $username.val() + '</b> is already taken!');
        }

        if ($.inArray($email1.val(), emailList) !== -1) {
            _addError($email1, '<b>' + $email1.val() + '</b> is already taken');
        }

        if ($email1.val() === '') {
            _addError($email1, "Email can't be empty!");
        }

        if (!$checkBox.is(":checked")) {
            _addError($checkBox, 'you must agree to the terms and conditions!')
        }

    }


    if ($pwd1.val() !== $pwd2.val()) {
        _addError($pwd1, "passwords don't match");
        _addError($pwd2, "passwords don't match");
        isValid = false;
    }
    if ($firstName.val() === '') {
        _addError($firstName, "name can't be empty!");
        isValid = false;
    }
    if ($lastName.val() === '') {
        _addError($lastName, "name can't be empty!");
    }
    if ($city.val() === '') {
        _addError($city, "city can't be empty!");
        isValid = false;
    }
    return isValid;
};

$(function(){
    $pwd1 = $('#password1');
    $pwd2 = $('#password2');
    $firstName = $('#firstname');
    $lastName = $('#lastname');
    $email1 = $('#email1');
    $email2 = $('#email2');
    $city = $('#city');
    $sex = $('#sex');
    $username = $("#username");
    $checkBox = $('#check');
    $loginUser = $('#login-username');
    $loginPass = $('#password');

    $('.login-activator').click(function(){
        _resetLoginErrors();
        _resetLoginForm();
        _showBlack1();
        $('#user-login-modal').show();
    });

    $('.register-activator').click(function(){
        _resetRegForm();
        _resetRegErrors();
        _getUsers();
        _showBlack1();
        $('#user-register-modal').show();
    });

    $('#register-form').submit(function(e){
        e.preventDefault();
        if (_checkForm(true)) {
            $(this).unbind('submit').submit();
        }
    });

    $('#login-form').submit(function(e){
        e.preventDefault();
        if (_checkLoginForm()) {
        }
    });
});