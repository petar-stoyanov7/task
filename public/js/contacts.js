var form = $('#contacts-form');

form.submit(function(e){
    _resetForm(form);
    e.preventDefault();
    if (_checkForm(form)) {
        $(this).unbind('submit').submit();
    }
});