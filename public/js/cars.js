var $formBrand;
var formModel;
var $formYear;
var $formColor;
var $formMileage;
var $formFuel;
var $formFuel2;
var $formUserId;
var $formCarId;
var $formNotes;
var $currentCarUser;

var carCache = {};

var _showCarEditForm = function()
{
    _showBlack3();
    $('#car-form-modal').show();
};

var _hideCarEditForm = function()
{
    _closeBlack3();
    $('#car-form-modal').hide();
};

var _toggleCarModal = function()
{
    _toggleBlack3();
    $('#car-form-modal').toggle();
};

var _resetCarForm = function()
{
    $formBrand.val('');
    formModel.val('');
    $formColor.val('');
    $formYear.val('');
    $formFuel.val(1);
    $formFuel2.val('');
    $formMileage.val('');
    $formNotes.val('');
    $formUserId.val('');
    $formCarId.val('');
};

var _resetDeleteModal = function()
{
    $('div.profile-car').removeClass('delete-car-overlay').removeClass('hide-car-overlay');
};

var _closeDeleteConfirmation = function()
{
    $('#delete-car-confirm').hide();
    $('#car-black-overlay').hide();
    _resetDeleteModal();
};

var _resetCarsContainer = function()
{
    $('div.cars-list').empty();
    $('#add-car').attr('onclick', '');
};

var drawCars = function(cars, userId) {
    console.log(cars);
    _resetCarsContainer();
    $.each(cars, function (i, data) {
        editCarDiv(data, false);
    });
    $('#add-car').attr('onclick', 'showNewCar("'+ userId +'")');
};

var renderUserCars = function(userId)
{
    _startLoading();
    if (undefined === carCache[userId]) {
        $.ajax({
            type: 'POST',
            url: '/cars/list-user-cars/',
            dataType: 'JSON',
            data: {
                userid: userId
            },
            success: function(data) {
                carCache[userId] = data;
            },
            error: function(response) {
                _stopLoading();
                console.log('error with request');
                console.log(response);
            }

        }).done(function(data){
            drawCars(carCache[userId], userId);
            _stopLoading();
        });
    } else {
        drawCars(carCache[userId], userId);
        _stopLoading();
    }
};

var showEditCar = function(userId,carId)
{
    _resetCarForm();
    var car;
    if (undefined !== carCache[userId]) {
        car = carCache[userId][carId];
    } else {
        console.log("Error with car")
        return;
    }
    console.log($formUserId);
    $formUserId.val(userId);
    $formCarId.val(carId);
    $formYear.val(car['Year']);
    $formBrand.val(car['Brand']);
    formModel.val(car['Model']);
    $formColor.val(car['Color']);
    $formFuel.val(car['Fuel_ID']);
    $formFuel2.val(car['Fuel_ID2']);
    $formMileage.val(car['Mileage']);
    $formNotes.val(car['Notes']);
    _showCarEditForm();
};

var showNewCar = function(userId)
{
    _resetCarForm();
    _showCarEditForm();
    $formUserId.val(userId);
};

var showDeleteCar = function(carId)
{
    _resetDeleteModal();
    $('div.profile-car').addClass('hide-car-overlay');
    $('#car-id-'+carId).removeClass('hide-car-overlay').addClass('delete-car-overlay');
    $('#car-black-overlay').show();
    $('#delete-car-confirm').toggle();
    $('#delete-car-confirm button.delete-yes')
        .attr(
            'onclick',
            'deleteCar(\'' + carId + '\')'
        );
};

editCarDiv = function(data, isEdit)
{
    console.log(data);
    var carId = data['ID'];
    var userId = data['UID'];

    if (isEdit) {
        $divObject = $('#car-id-' + carId);
    } else {
        $divObject = $('#car-template').clone(true);
        $divObject.attr('id', 'car-id-' + carId);
        $divObject.find('a.edit-car')
            .attr(
                'onclick',
                "showEditCar('" + userId + "','" + carId + "')"
            );
        $divObject.find('a.delete-car')
            .attr(
                'onclick',
                "showDeleteCar('" + carId + "')"
            );
    }
    $divObject.find('h4').text(data['Brand'] + ' ' + data['Model']);
    $divObject.find('span.car-year').text(data['Year']);
    $divObject.find('span.car-color').text(data['Color']);
    $divObject.find('span.car-mileage').text(data['Mileage']);
    $divObject.find('span.car-fuel').text(data['fuel_name1']);
    var $fuel2Parent = $divObject.find('span.car-fuel2').closest('div.flex-wrapper');
    if (
        undefined === data['Fuel_ID2'] ||
        '' === data['Fuel_ID2'] ||
        null === data['Fuel_ID2']
    ) {
        if (!$fuel2Parent.hasClass('hidden')) {
            $fuel2Parent.addClass('hidden');
        }
    } else {
        if ($fuel2Parent.hasClass('hidden')) {
            $fuel2Parent.removeClass('hidden');
        }
    }
    $divObject.find('span.car-fuel2').text(data['fuel_name2']);
    $divObject.find('span.car-notes').text(data['Notes']);
    if (!isEdit) {
        $('div.cars-list').append($divObject);
    }
};

var deleteCar = function(carId)
{
    _startLoading();
    var success = false;
    var deleteData = {'car-id': carId};
    if ($('#delete-car-expenses').is(":checked")) {
        deleteData['delete-expenses'] = 1;
    }
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/cars/delete',
        data: deleteData,
        error: function(response) {
            console.log('Error with deletion: ');
            console.log(response);
            _stopLoading();
        },
        success: function(data) {
            if (data['success'] === true) {
                success = true;
            }
        }
    }).done(function(){
        if (success) {
            $('#car-id-' + carId).remove();
        }
        _closeDeleteConfirmation();
        _resetDeleteModal();
        _stopLoading();
    });
};

var processCar = function()
{
    _startLoading();
    var userId = $formUserId.val();
    var formData = {
        'brand': $formBrand.val(),
        'model': formModel.val(),
        'year': $formYear.val(),
        'color': $formColor.val(),
        'mileage': $formMileage.val(),
        'fuel_id1': $formFuel.val(),
        'fuel_id2': $formFuel2.val(),
        'notes': $formNotes.val(),
        'user-id': userId
    };
    var success = false;
    var isEdit = false;
    var newCar;
    if ($formCarId.val() !== '') {
        formData['car-id'] = $formCarId.val();
        isEdit = true;
    }
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/cars/process',
        data: formData,
        error: function(response) {
            console.log('error with processing car');
            console.log(response);
            _stopLoading();
        },
        success: function(response) {
            if (response['success']) {
                success = true;
                newCar = response['car'];
            }
        }
    }).done(function(){
        if (success) {
            if (isEdit) {
                editCarDiv(newCar, true);
            } else {
                editCarDiv(newCar, false);
            }
            carCache[userId][newCar['ID']] = newCar;
        }
        _hideCarEditForm();
        _stopLoading();
    });

};

$(function(){
    $formBrand = $('#brand');
    formModel = $('#model');
    $formYear = $('#year');
    $formColor = $('#color');
    $formMileage = $('#mileage');
    $formFuel = $('#fuel_id1');
    $formFuel2 = $('#fuel_id2');
    $formNotes = $('#notes');
    $formUserId = $('#car-user-id');
    $formCarId = $('#car-id');
    $currentCarUser = $('#cars-user-id');

    $('#car-form #submit').click(function(e){
        e.preventDefault();
        processCar();
    });

    /** Intended to move it outside its container so the z-index of the parent don't apply */
    $formModal = $('#car-form-modal');
    $formModal.parent().after($formModal);


    if ($currentCarUser.val() !== '') {
        renderUserCars($currentCarUser.val());
    }
});
