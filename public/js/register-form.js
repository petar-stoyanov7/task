var form = $('#register-form');
var button = $('#submit');
var spanUser = $('#user-warning');
var spanPass = $('#password-warning');
var spanEmail = $('#email-warning');
var spanFname = $('#fname-warning');
var spanLname = $('#lname-warning');
var spanCity = $('#city-warning');
var spanGeneral = $('#general-warning');

var user = $('#username');
var pwd1 = $('#password1');
var pwd2 = $('#password2');
var email1 = $('#email1');
var email2 = $('#email2');
var fName = $('#firstname');
var lName = $('#lastname');
var city = $('#city');
var check = $('#check');

disableButton();

form.on('keyup change paste', 'input, select, textarea', function(){
    if (!onlyLettersAndNumbers(user.val())){
        displayWarning(spanUser, "Невалидни символи");
        disableButton();
    } else {
        enableButton();
        clearWarning(spanUser);
    }

    if (pwd1.val().length < 6 || pwd2.val().length < 6) {
        displayWarning(spanPass, "Паролата е прекалено къса")
        disableButton();
    } else {
        if (pwd1.val() !== pwd2.val()) {
            displayWarning(spanPass, "Паролите не съвпадат");
            disableButton();
        } else {
            clearWarning(spanPass);
            enableButton();
        }
    }

    if (!checkEmail(email1.val()) || !checkEmail(email2.val())) {
        displayWarning(spanEmail, "Невалиден e-mail");
        disableButton();        
    } else {
        if (email1.val() !== email2.val()) {
            displayWarning(spanEmail, "E-mail-ите не съвпадат");
            disableButton();
        } else {
            clearWarning(spanEmail);
            enableButton();
        }
    }

    if (fName.val().length === 0) {
        displayWarning(spanFname, "Невалидно име!");
        disableButton();
    } else {
        clearWarning(spanFname);
        enableButton();
    }

    if (lName.val().length === 0) {
        displayWarning(spanLname, "Невалидна фамилия!");
        disableButton();
    } else {
        clearWarning(spanLname);
        enableButton();
    }

    if (city.val().length === 0) {
        displayWarning(spanCity, "Невалиден град!");
        disableButton();
    } else {
        clearWarning(spanCity);
        enableButton();
    }

});

function disableButton() {
    button.prop('disabled', true);
}

function enableButton() {
    button.prop('disabled', false);
}

function displayWarning(span, warning) {
    span.text(warning);
}

function clearWarning(span) {
    span.text('');
}

function onlyLettersAndNumbers(str, onlyLetters = false) {
    regexString = '^[a-zA-Z';
    if (!onlyLetters) {
        regexString += '0-9';
    }
    regexString += ']+$';
    return str.match(regexString);
}

function onlyLettersAndNumbers2(str, onlyLetters = false, cyrillic = false) {
    regexString = '^[a-zA-Z';
    if (!onlyLetters) {
        regexString += '0-9';
    }
    if (cyrillic) {
        regexString += 'а-яА-Я';
    }
    regexString += ']+$';
    console.log(regexString);
    return str.match(regexString);
}

function checkEmail(str) {
    return str.match("^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$");
}