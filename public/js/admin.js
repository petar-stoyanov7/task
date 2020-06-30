var _oldHide = $.fn.hide;

$.fn.hide = function(speed,callback)
{
    $(this).trigger('hide');
    return _oldHide.apply(this,arguments);
};
var _closeModal = function(span)
{
    span.closest('div.modal-lvl-1').hide();
    if ($('div.modal-lvl-1:visible').length === 0) {
        _closeBlack1();
    }
};

var _closeUserDeleteConfirmation = function()
{
    $('#delete-user-confirm').hide();
    _closeBlack3();
};

var _resetUserFields = function()
{
    $('#profile-user-id').val('');
    $('#profile-username').text('');
    $('#profile-name').text('');
    $('#profile-email').text('');
    $('#profile-city').text('');
    $('#edit-user').attr('onclick','');
};

var _removeUserFromTable = function(userId)
{
    $('#user-row-'+userId).remove();
};

var showUserModal = function(userId)
{
    _resetUserFields();
    _showBlack1();
    $('#user-summary-modal').show();
    renderUser(userId);
};

var showCars = function(userId)
{
    _showBlack1();
    renderUserCars(userId);
    $('#cars-user-id').val(userId);
    $('#user-cars-modal').show();
};

var adminEditUser = function(userId)
{
    showUserModal(userId);
    drawUserForm(userId);
};

var adminDeleteUser = function(userId)
{
    showUserModal(userId);
    _showBlack3();
    $('#delete-user-confirm').show();
    $('#delete-user-confirm button.delete-yes').attr('onclick','deleteUser("'+userId+'")');
};

var deleteUser = function(userId)
{
    var deleteData = {
        'userId': userId,
        'deleteExpenses': null,
        'deleteCars': null,
    };

    if ($('#delete-user-expenses').is(":checked")) {
        deleteData['deleteExpenses'] = true
    }

    if ($('#delete-user-cars').is(":checked")) {
        deleteData['deleteCars'] = true
    }

    _startLoading();
    var success = false;
    $.ajax({
        type: 'POST',
        url: '/account/delete',
        dataType: 'JSON',
        data: deleteData,
        error: function(response) {
            console.log('Error with user deletion');
            console.log(response);
            _stopLoading();
        },
        success: function(data) {
            success = data['success'];
        }
    }).done(function(){
        console.log(success);
        if (success) {
            _removeUserFromTable(userId);
        }
        _closeAll();
        _stopLoading();
    });
};

var editUserTable = function(userId) {
    if (userCache.hasOwnProperty(userId)) {
        user = userCache[userId];
        $row = $('#user-row-' + userId);
        $row.find('td.table-user-username').html(user['username']);
        $row.find('td.table-user-city').html(user['city']);
        $row.find('td.table-user-gender').html(user['sex']);
        $row.find('td.table-user-firstname').html(user['firstname']);
        $row.find('td.table-user-lastname').html(user['lastname']);
        $row.find('td.table-user-email').html(user['email']);
    }
};

$(function(){
    $(
        'td.table-user-id,' +
        'td.table-user-username,' +
        'td.table-user-cars,' +
        'td.table-user-email,' +
        'td.table-user-firstname,' +
        'td.table-user-lastname,' +
        'td.table-user-gender'
    ).click(function(){
        var userId = $(this).closest('tr.table-user-row').attr('userId');
        $('#delete-user').attr('onclick', 'adminDeleteUser("' + userId + '")');
        showUserModal(userId);
        showCars(userId);
    });

    $('span.modal-close').click(function(){
        _closeModal($(this));
    });

    $('#user-summary-modal').on('hide', function(){
        var userId = $(this).find('#profile-user-id').val();
        if (undefined !== userId && '' !== userId) {
            editUserTable(userId);
        }
    });
});