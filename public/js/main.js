var loadingCounter = 0;
var $blacklvl1;
var $blacklvl2;
var $blacklvl3;
var $loadingOverlay;
var $loadingAnimation;
var $confirmationDialogue;

var _hideLvl1 = function()
{
    $('.cover-lvl-1').hide();
    $blacklvl1.hide();
};

var _hideLvl2 = function()
{
    $('.cover-lvl-2').hide();
    $blacklvl2.hide();
};

var _hideLvl3 = function()
{
    $('.cover-lvl-3').hide();
    $blacklvl3.hide();
};


var _startLoading = function()
{
    loadingCounter++;
    if (loadingCounter <= 1) {
        $loadingOverlay.show();
        $loadingAnimation.show();
    }
};

var _stopLoading = function()
{
    loadingCounter--;
    if (loadingCounter <= 0) {
        $loadingOverlay.hide();
        $loadingAnimation.hide();
    }
};

var _disableForm = function(formSelector)
{
    formSelector.find('input').prop('disabled', true);
    formSelector.find('select').prop('disabled', true);
    formSelector.find('textarea').prop('disabled', true);
    formSelector.find('button').prop('disabled', true);
}

var _resetForm = function(formSelector)
{
    formSelector.find('input').removeClass('form-error');
    formSelector.find('textarea').removeClass('form-error');
};

var _checkForm = function(formSelector)
{
    isValid = true;
    $.each(formSelector.find('.form-control'), function(){
        if (null === $(this).val() || '' === $(this).val() || undefined === $(this).val()) {
            $(this).addClass('form-error');
            $(this).attr('placeholder', 'Can not be empty!');
            isValid = false;
        }
    });
    return isValid;
}

var _showConfirmation = function()
{
    $blacklvl1.show();
    $confirmationDialogue.show();
}

$(function(){
    $blacklvl1 = $('#black-lvl-1');
    $blacklvl2 = $('#black-lvl-2');
    $blacklvl3 = $('#black-lvl-3');
    $loadingOverlay = $('#loading-overlay');
    $loadingAnimation = $('#loading-cover');
    $confirmationDialogue = $('#confirmation-dialogue');

    $blacklvl1.click(_hideLvl1);

    $blacklvl2.click(_hideLvl2);

    $blacklvl3.click(_hideLvl3);
});