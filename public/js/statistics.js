var expenseCache = {};

var _closeDeleteConfirmation = function()
{
    $('#delete-stat-confirm').hide();
    _toggleBlack2();
    $('#delete-stat-confirm button.delete-yes').attr('onclick', '');
};

var showDeleteConfirmation = function(index)
{
    var cacheString = $('#detailed-expenses-table').attr('cacheString');
    var data = expenseCache[cacheString]['allExpenses'][index];
    _toggleBlack2();
    $('#delete-stat-confirm').toggle();
    $('#delete-stat-confirm button.delete-yes')
        .attr(
            'onclick',
            'deleteExpense(\'' + data['Date'] + '_' + data['ID'] + '\')'
        );
};

var showDetailedInformation = function(index)
{
    var cacheString = $('#detailed-expenses-table').attr('cacheString');
    var data = expenseCache[cacheString]['allExpenses'][index];
    var detailedInfo = $('#detailed-info');
    var deleteButton = $('a.delete-expense');
    $('#detailed-info span.data').val('');
    $('#detailed-info div.dynamic-content').remove();
    $('#details-car').text(data['car_brand'] + ' ' + data['car_model']);
    $('#details-date').text(data['Date']);
    $('#details-expense-type').text(data['expense_name']);
    $('#details-mileage').text(data['Mileage']);
    $('#details-value').text(data['Price']);
    deleteButton.attr('onclick', 'showDeleteConfirmation('+data['ID']+')');
    var added = '';
    switch(data['Expense_ID']) {
        case 1:
            added = '<div class="flex-wrapper dynamic-content">' +
                '<span class="label">Fuel type:</span>' +
                '<span class="data">' + data['fuel_name'] + '</span>' +
                '</div>' +
                '<div class="flex-wrapper dynamic-content">' +
                '<span class="label">Liters:</span>' +
                '<span class="data">' + data['Liters'] + '</span>' +
                '</div>';
            break;
        case 2:
            added = '<div class="flex-wrapper dynamic-content">' +
                '<span class="label">Insurance Type:</span>' +
                '<span class="data">' + data['insurance_name'] + '</span>' +
                '</div>';
            break;
        default:
            added = '';
    }
    $('div.separator').after(added);
    detailedInfo.toggle();
    _toggleBlack1();
};

var showDeleteExpense = function(index)
{
    showDetailedInformation(index);
    showDeleteConfirmation(index);
};

var renderCars = function(cars)
{
    var containerDiv = $('#overall div.flex-wrapper');
    $('div.element').remove();
    $.each(cars, function(i,car){
        var ratio = 0;
        if (null === car['Distance'] || null === car['Overall']) {
            ratio = 'none';
        } else if (car['Distance'] <= 0) {
            ratio = car['Overall'];
        } else {
            ratio = parseFloat(car['Overall']) / parseFloat(car['Distance']);
            ratio = ratio.toFixed(2);
        }
        var element = $('<div class="element">' +
            '<h4>' + car['Brand'] + ' ' + car['Model'] + '</h4>' +
            '<span class="element-entry"><b>Kilometers passed: </b>' + car['Distance'] + '</span>' +
            '<span class="element-entry"><b>Spent: </b>' + car['Overall'] + '</span>' +
            '<span class="element-entry"><b>Ratio: </b>' + ratio + '</span></div>');
        containerDiv.append(element);

    });
};

var deleteExpense = function(expenseString)
{
    var expenseDetails = expenseString.split('_');
    var cacheString = $('#detailed-expenses-table').attr('cacheString');
    var success = false;
    var date = expenseDetails[0];
    var expenseId = expenseDetails[1];
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/expense/remove',
        dataType: 'JSON',
        data: {
            date: date,
            expenseId: expenseId
        },
        error: function(response) {
            console.log('Error with deletion');
            console.log(response);
            _stopLoading();
        },
        success: function(data) {
            success = data['success'];
        }
    }).done(function(){
        if (success) {
            var row = $('#' + expenseString);
            var arrayIndex = row.attr('rowIndex');
            row.remove();
            expenseCache[cacheString]['allExpenses'].splice(arrayIndex, 1);
        }
        _closeAll();
        _stopLoading();
    });
};

var renderPartsData = function(data, cacheString)
{
    var table = $('#detailed-parts-table');
    table.attr('cacheString', cacheString);
    $('#detailed-parts-table tr.parts-row').remove();
    if (data.length > 0) {
        $.each(data, function (index, datarow) {
            var element = '' +
                '<tr class="parts-row" id="' + datarow['Date'] + '_' + datarow['ID'] + '" rowIndex="' + index + '">' +
                '<td>' + datarow['Name'] + '</td>' +
                '<td>' + datarow['Date'] + '</td>' +
                '<td>' + datarow['car_name'] + '</td>' +
                '<td>' + datarow['part_mileage'] + '</td>' +
                '<td>' + datarow['part_age'] + '</td>' +
                '</tr>';
            table.append(element);
        })
    }
    _showBlack1();
    $('#parts-info').show();
};

var renderDetailedStatistics = function(data, cacheString)
{
    var table = $('#detailed-expenses-table');
    table.attr('cacheString', cacheString);
    $('#detailed-expenses-table tr.statistic-row').remove();
    $.each(data, function(index,datarow){
        var element = '' +
            '<tr class="statistic-row" id="'+datarow['Date']+'_'+datarow['ID']+'" rowIndex="'+index+'">' +
            '<td>' + datarow['Mileage'] + '</td>' +
            '<td>' + datarow['Date'] + '</td>' +
            '<td>' + datarow['car_brand'] + ' ' + datarow['car_model'] + '</td>' +
            '<td>' + datarow['expense_name'] + '</td>' +
            '<td>' + $.trim(datarow['fuel_name']) + '</td>' +
            '<td>' + $.trim(datarow['Liters']) + '</td>' +
            '<td>' + datarow['Price'] + '</td>' +
            '<td>' + $.trim(datarow['Notes']) + '</td>' +
            '<td>' +
            '<a onclick="showDetailedInformation('+ index +')">' +
            '<i class="fas fa-info-circle"></i>' +
            '</a>  ' +
            '<a onclick="showDeleteExpense(' + index + ')">' +
            '<i class="fas fa-trash-alt"></i>' +
            '</a>' +
            '</td>' +
            '</tr>';
        table.append(element);
    })
};

var renderStatisticsData = function(data, cacheString)
{
    renderCars(data['cars']);
    renderDetailedStatistics(data['allExpenses'], cacheString);
};

var getPartsData = function()
{
    var selectedCar = $('#car').val();
    var userId = $('#user-id').val();
    var cacheString = 'stat_' + selectedCar + '_' + userId;
    _startLoading();
    if (expenseCache[cacheString] !== undefined) {
        renderPartsData(expenseCache[cacheString], cacheString);
        _stopLoading();
    } else {
        $.ajax({
            type: 'POST',
            url: '/statistics/get-parts',
            dataType: 'JSON',
            data: {
                'user-id': userId,
                'car': selectedCar,
                'ajax': 1
            },
            success: function(data) {
                expenseCache[cacheString] = data;
            },
            error: function(response) {
                console.log('Error with parts data');
                console.log(response);
                _stopLoading();
            }
        }).done(function(){
            renderPartsData(expenseCache[cacheString], cacheString);
            _stopLoading();
        });
    }
};

var getStatisticsData = function()
{
    var selectedCar = $('#car').val();
    var expenseType = $('#expense-type').val();
    var startDate = $('#from').val();
    var endDate = $('#to').val();
    var userId = $('#user-id').val();
    var cacheString = 'stat_' +
        selectedCar + '_' +
        expenseType + '_ ' +
        startDate + '_' +
        endDate + '_' +
        userId;
    _startLoading();
    if (expenseCache[cacheString] !== undefined) {
        renderStatisticsData(expenseCache[cacheString], cacheString);
        _stopLoading();
    } else {
        $.ajax({
            type: 'POST',
            url: '/statistics',
            dataType: 'JSON',
            data: {
                'from': startDate,
                'to': endDate,
                'user-id': userId,
                'car': selectedCar,
                'expense-type': expenseType,
                'ajax': 1
            },
            success: function(data) {
                expenseCache[cacheString] = data;
            },
            error: function(response) {
                console.log('Error with statistics data');
                console.log(response);
                _stopLoading();
            }
        }).done(function(){
            renderStatisticsData(expenseCache[cacheString], cacheString);
            _stopLoading();
        });
    }
};

$(function(){
    var form = $('#get-statistics-form');

    form.submit(function(event){
        event.preventDefault();
        getStatisticsData();
    });

    $('.modal-close').click(function(){
        $(this).closest('div.expense-details-modal').hide();
        _toggleBlack1();
    });

    $('#delete-stat-confirm .modal-close').click(function(){
        $(this).closest('div.expense-details-modal').hide();
        _toggleBlack2();
    });

    $('#display-parts').click(getPartsData);
});