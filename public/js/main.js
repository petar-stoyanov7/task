var loadingCounter = 0;
var $blackLvl1;
var $blackLvl2;
var $blackLvl3;
var $loadingBlack;
var $loadingMessage;

var _startLoading = function() {
    loadingCounter++;
    if (loadingCounter <= 1) {
        $loadingBlack.show();
        $loadingMessage.show();
    }
};

var _stopLoading = function() {
    loadingCounter--;
    if (loadingCounter <= 0) {
        $loadingBlack.hide();
        $loadingMessage.hide();
    }
};

var _toggleBlack1 = function()
{
    $blackLvl1.toggle();
};
var _showBlack1 = function()
{
    $blackLvl1.show();
};
var _closeBlack1 = function()
{
    $blackLvl1.hide();
};

var _toggleBlack2 = function()
{
    $blackLvl2.toggle();
};
var _showBlack2 = function()
{
    $blackLvl2.show();
};
var _closeBlack2 = function()
{
    $blackLvl2.hide();
};

var _toggleBlack3 = function()
{
    $blackLvl3.toggle();
};
var _showBlack3 = function()
{
    $blackLvl3.show();
};
var _closeBlack3 = function()
{
    $blackLvl3.hide();
};

var _closeAllBlack = function()
{
    $blackLvl1.hide();
    $blackLvl2.hide();
    $blackLvl3.hide();
};

var _closeAllModals = function()
{
    $('.modal-lvl-1,.modal-lvl-2,.modal-lvl-3').hide();
};

var _closeAll = function()
{
    _closeAllBlack();
    _closeAllModals();
};

var _resetFormErrors = function(formSelector)
{
    $(formSelector + ' div.form-wrapper').removeClass('form-error');
    $(formSelector + ' div.form-wrapper span.form-error-message').remove();
};

$(function(){
    $blackLvl1 = $('#black-lvl-1');
    $blackLvl2 = $('#black-lvl-2');
    $blackLvl3 = $('#black-lvl-3');
    $loadingBlack = $('#loading-black');
    $loadingMessage = $('#loading-message');


    $('.eula-activator').click(function(){
        $('#modal-eula').show();
        _showBlack1();
    });

    $blackLvl1.click(function(){
        $('.modal-lvl-1').hide();
        $blackLvl1.hide();
    });

    $blackLvl2.click(function(){
        $('.modal-lvl-2').hide();
        $blackLvl2.hide();
    });

    $blackLvl3.click(function(){
        $('.modal-lvl-3').hide();
        $blackLvl3.hide();
    });

    $(document).keyup(function(e){
        if (e.key === 'Escape') {
            _closeAllBlack();
            _closeAllModals();
        }
    });

});