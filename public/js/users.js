var $form;
var $formUser;
var $formEmail;
var $formEmail2;
var $formPassword1
var $formPassword2
var $formFirstname;
var $formLastname;
var $username;
var $firstname;
var $lastname;
var $email;

var _changeProfilePicture = function(path)
{
    $('#profile-picture-img').attr('src', path);
}

var _editUserFormCheck = function()
{
    var isValid = true;
    if ($formPassword1.val() !== $formPassword2.val()) {
        $formPassword1
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'passwords don\'t match');
        $formPassword2
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'passwords don\'t match');
        isValid = false;
    }

    if ($formEmail.val() !== $formEmail2.val()) {
        $formEmail
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'email addresses don\'t match');
        $formEmail2
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'email addresses don\'t match');
        isValid = false;
    }
    if (!$formFirstname.val().length) {
        $formFirstname
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'can\'t be empty');
        isValid = false;
    }
    if (!$formLastname.val().length) {
        $formLastname
            .val('')
            .addClass('form-error')
            .attr('placeholder', 'can\'t be empty');
        isValid = false;
    }
    return isValid;
}

var showEditUser = function() {
    _resetForm($form);
    $formUser.val($username.text().trim());
    $formFirstname.val($firstname.text().trim());
    $formLastname.val($lastname.text().trim());
    $formEmail.val($email.text().trim());
    $formEmail2.val($email.text().trim());
    $('#register-submit').html('Save');
    $('#black-lvl-1').show();
    $('#user-form-container').show();
}

var setProfilePicture = function(userId, path)
{
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/user/set-profile',
        data: {
            'path': path,
            'user-id': userId
        },
        error: function(response) {
            console.log('Error with processing');
            console.log(response)
            _stopLoading();
        },
        success: function() {
            _changeProfilePicture(path);
        }
    }).done(function(){
        _stopLoading();
    });
}

var editProfileData = function()
{
    $firstname = $formFirstname.val();
    $lastname = $formLastname.val();
    $email = $formEmail.val();
}

var editUser = function()
{
    var formData = {
        'user_id': $('#user_id').val(),
        'firstname': $formFirstname.val(),
        'lastname': $formLastname.val(),
        'email': $formEmail.val()
    };
    if ($formPassword1.val().length) {
        formData['password'] = $formPassword1.val();
    }
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/user/edit-profile',
        data: formData,
        error: function(resp) {
            console.log('error with edit');
            console.log(resp);
            $('#black-lvl-1').hide();
            $('#user-form-container').hide();
            _stopLoading();
        },
        success: function(resp) {
            if (resp['success']) {
                editProfileData();
            }
        }
    }).done(function(){
        $('#black-lvl-1').hide();
        $('#user-form-container').hide();
        _stopLoading();

    });
};

$(function(){
    $form = $('#register-form');
    $formUser = $('#username');
    $formEmail = $('#email');
    $formEmail2 = $('#email2');
    $formPassword1 = $('#password');
    $formPassword2 = $('#password2');
    $formFirstname = $('#firstname');
    $formLastname = $('#lastname');
    $username = $('#user-details-username');
    $firstname = $('#user-details-firstname');
    $lastname = $('#user-details-lastname');
    $email = $('#user-details-email');

    if ($('#user-list-table').length) {
        $('#user-list-table tr').click(function () {
            var userId = $(this).attr('user-id');
            $(location).attr('href', '/user/show/id/' + userId);
        });
    }

    if ($form.length) {
        $form.submit(function(e){
            e.preventDefault();
            if (_editUserFormCheck()) {
                editUser();
            }
        });
    }
});