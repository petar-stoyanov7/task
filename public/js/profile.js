var $currentUser;

var userCache = {};

var _resetUserForm = function() {
    $userId.val('');
    $userId.val('');
    $pwd1.val('');
    $pwd2.val('');
    $firstName.val('');
    $lastName.val('');
    $email1.val('');
    $city.val('');
};

var _closeModal = function()
{
    _toggleBlack2();
    $('#user-form-modal').hide();
};

var _toggleUserModal = function()
{
    _toggleBlack3();
    $('#user-form-modal').toggle();
};

var _resetPage = function(data)
{
    $('#profile-user-id').val(data['user-id']);
    $('#profile-username').text(data['username']);
    $('#profile-name').text(data['firstname'] + ' ' + data['lastname']);
    $('#profile-email').text(data['email']);
    $('#profile-city').text(data['city']);
    $('#edit-user').attr(
        'onclick',
        'drawUserForm(' + data['user-id'] + ');'
    );
};

var renderUser = function(userId)
{
    _startLoading();
    if (undefined === userCache[userId]) {
        $.ajax({
            type: 'POST',
            url: '/account/get-user-info',
            dataType: 'JSON',
            data: {
                userId: userId
            },
            error: function(response) {
                console.log('Error with user data extraction');
                console.log(response);
                _stopLoading();
            },
            success: function(data) {
                userCache[userId] = data;
            }
        }).done(function(){
            _resetPage(userCache[userId]);
            _stopLoading();
        });
    } else {
        _resetPage(userCache[userId]);
        _stopLoading();
    }
};

var drawUserForm = function(userId)
{
    _toggleUserModal();
    var user = userCache[userId];
    $('#username').val(user['username']);
    $('#email1').val(user['email']);
    $('#firstname').val(user['firstname']);
    $('#lastname').val(user['lastname']);
    $('#city').val(user['city']);
    $('#sex').val(user['sex']);
    $('#user-id').val(user['user-id'])
};

var editUser = function()
{
    var success = false;
    if (_checkForm()) {
        _startLoading();
        var userId = $userId.val();
        var newValues = {
            'firstname': $firstName.val(),
            'lastname': $lastName.val(),
            'sex': $sex.val(),
            'city': $city.val(),
            'email': $email1.val(),
            'password1': $pwd1.val(),
            'password2': $pwd2.val(),
            'user-id': userId
        };
        $.ajax({
            type: 'POST',
            url: '/account/edit',
            dataType: 'JSON',
            data: newValues,
            error: function(response) {
                console.log('error with user edit');
                console.log(response);
                _stopLoading();
            },
            success: function(data){
                success = data['success'];
                userCache[userId] = newValues;
            }
        }).done(function(){
            _stopLoading();
            _toggleUserModal();
            _resetPage(newValues);
        });
    }
};

$(function(){
    $userId = $('#user-id');
    $currentUser = $('#profile-user-id');

    $('#account-edit-form').submit(function(e){
        e.preventDefault();
        editUser();
    });

    /** Intended to move it outside its container so the z-index of the parent don't apply */
    $formModal = $('#user-form-modal');
    $formModal.parent().after($formModal);

    if ($currentUser.val() !== '') {
        renderUser($currentUser.val());
    }
});