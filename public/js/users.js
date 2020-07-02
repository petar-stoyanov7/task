var $formUser;
var $formEmail;
var $formEmail2;
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

var showEditUser = function() {
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
        },
        success: function() {
            _changeProfilePicture(path);
        }
    }).done(function(){
        _stopLoading();
    });
}

// var editUser = function()
// {
//     _startLoading();
//     $.ajax({
//         type: 'POST',
//         url: '/user/edit-profile',
//         data {
//             'user_id': $('#user_id').val(),
//
//         }
//     });
// };

$(function(){
    $formUser = $('#username');
    $formEmail = $('#email');
    $formEmail2 = $('#email2');
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
});