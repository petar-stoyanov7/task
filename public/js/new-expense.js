var expenseCache = {};
var cars = {};
var lastFive = {};

var _hideOptionals = function()
{
    $.each($('.optional-select'), function(i, value){
        $(value).closest('.form-wrapper').hide();
    });
};

var _checkExpenseForm = function()
{
    _resetFormErrors('#new-expense-form');
    isValid = true;
    if ($("#value").val() === '' || $("#value").val() === null) {
        _addError($('#value'), 'No value provided!');
        isValid = false;
    }

    if (
        parseInt($('#expense-type').val()) === 1 &&
        ($('#liters').val() === '' || $('#liters').val() === null)
    ) {
        _addError($('#liters'), "Can't be empty!");
        isValid = false;
    }

    if (
        parseInt($('#expense-type').val()) === 5 &&
        ($('#part-name').val() === '' || $('#part-name').val() === null)
    ) {
        _addError($('#part-name'), "Can't be empty!");
        isValid = false;
    }
    return isValid;
};

var _showAllOptions = function(selector)
{
    $.each($(selector + ' option'), function(){
        $(this).show();
    });
};

var _resetForm = function()
{
    $('#description').val('');
    $('#value').val('');
    $('#liters').val('');
    $('#part-name').val('');
    $('#replacement-part-name').val('0');
};

var displayOption = function(selector)
{
    $(selector).closest('.form-wrapper').show();
};

var filterExpenses = function() {
    var selectedExpense = $('#expense-type').val();
    _hideOptionals();
    switch(selectedExpense) {
        case "1":
            displayOption('#fuel-type');
            displayOption('#liters');
            break;
        case "2":
            displayOption('#insurance-type');
            break;
        case "5":
            displayOption('#part-name');
            displayOption('#replacement-part-name');
            break;
        default:
            break;
    }
};

var filterFuels = function(cars)
{
    var selectedCar = $('#car-id').val();
    var fuels = [cars[selectedCar]['Fuel_ID']];
    if (null !== cars[selectedCar]['Fuel_ID2']) {
        fuels.push(cars[selectedCar]['Fuel_ID2']);
    }
    _showAllOptions('#fuel-type');
    $.each($('#fuel-type option'), function(){
        if (fuels.indexOf($(this).val()) === -1) {
            $(this).hide();
        }
    });
    $('#fuel-type').val(cars[selectedCar]['Fuel_ID']);
};

var filterParts = function(cars)
{
    var selectedCar = $('#car-id').val();
    var parts = cars[selectedCar]['parts'];
    _showAllOptions('#replacement-part-name');
    $.each($('#replacement-part-name option'), function(i,v){
        if (!parts.hasOwnProperty($(this).val())) {
            $(this).hide();
        }
    });
};

var setMileage = function(cars) {
    selectedCar = $('#car-id').val();
    $('#mileage').val(cars[selectedCar]['Mileage']);
};

var getCars = function(userId) {
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/cars/list-user-cars/',
        dataType: 'JSON',
        data: {
            userid: userId
        },
        success: function(data) {
            cars = data;
        },
        error: function(response) {
            _stopLoading();
            console.log('error with request');
            console.log(response);
        }

    }).done(function(data){
        drawPartsOptions(cars);
        filterFuels(cars);
        filterParts(cars);
        _stopLoading();
    });
};

var drawLastFive = function(data)
{
    $table = $('#last-five-expenses');
    $('#last-five-expenses tr.dynamic-row').remove();

    $.each(data, function(i,datum){
        if (null === datum['Notes'] || 'null' === datum['Notes']) {
            datum['Notes'] = '';
        }
        var row = '<tr class="dynamic-row">' +
            '<td>'+datum['Mileage']+'</td>' +
            '<td>'+datum['car_brand']+' '+datum['car_model']+'</td>' +
            '<td>'+datum['expense_name']+'</td>' +
            '<td>'+datum['Price']+'</td>' +
            '<td>'+datum['Notes']+'</td>' +
            '</tr>';
        $table.append(row);
    });
};

var drawPartsOptions = function(cars)
{
    $('#replacement-part-name option').remove();
    var $select = $('#replacement-part-name');
    $.each(cars, function(_,value){
        $.each(value['parts'], function(i,val) {
            $select.append(new Option(val['Name'], val['ID']));
        });
    });
};

var processExpense = function()
{
    _startLoading();
    var carId = $('#car-id').val();
    var mileage = $('#mileage').val();
    var userId = $('#user-id').val();
    formData = {
        "userId": userId,
        "carId": carId,
        "expenseType": $('#expense-type').val(),
        "fuelType": $('#fuel-type').val(),
        "insuranceType": $('#insurance-type').val(),
        "date": $('#date').val(),
        "mileage": mileage,
        "liters": $('#liters').val(),
        "value": $('#value').val(),
        "partName": $('#part-name').val(),
        "replacementParts": $('#replacement-part-name').val(),
        "description": $('#description').val(),
    };
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/expense/new-ajax-expense',
        data: formData,
        error: function (response) {
            _stopLoading();
            console.log('error with form execution');
            console.log(response);
        }
    }).done(function (data) {
        if (data['success']) {
            cars[carId]['Mileage'] = mileage;
            setMileage(cars);
            if (parseInt($('#expense-type').val()) === 5) {
                cars = {};
                getCars(userId);
            }
        }
        _resetForm();
        getLastFive(userId);
        _stopLoading();
    });
};

var getLastFive = function(userId) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/expense/get-last-five',
        data: {
            userId: userId
        },
        success: function(data) {
            lastFive = data;
        },
        error: function(response) {
            console.log('error with last five');
            console.log(response);
        }
    }).done(function(data){
        drawLastFive(lastFive);
    });
};

$(function(){
    var carSelect = $('#car-id');
    var expenseSelect = $('#expense-type');
    var userId = $('#user-id').val();
    var form = $('#new-expense-form');

    getCars(userId);
    getLastFive(userId);

    form.submit(function(e){
        e.preventDefault();
        if (_checkExpenseForm()) {
            processExpense();
        }
    });

    $('#car-id').change(function(){
        filterFuels(cars);
        filterParts(cars);
        setMileage(cars);
    });
    filterExpenses();
    expenseSelect.change(filterExpenses);
});