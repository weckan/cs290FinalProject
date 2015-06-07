
function checkUsernameAjaxResponse(testResult) {
    // testResult is supplied data from AJAX
    var ajaxResults = document.getElementById('UsernameAjaxDump');
    if(testResult == 1) {
        //console.log("<p> USERNAME AVAILABLE </p>");
        //ajaxResults.innerHTML = "Available"
        //
        //navigate to landing page
        //window.location.href = "http://web.engr.oregonstate.edu/~weckwera/290/wk10/landing.html";
        window.location.assign(
                "http://web.engr.oregonstate.edu/~weckwera/290/wk10/landing.php");
        return false;
    }
    else {
        console.log("ALREADY TAKEN");
        ajaxResults.innerHTML = "Taken"
    }
}   

function loginResults(response) {
    // response is supplied data from AJAX
    var ajaxResults = document.getElementById('LoginAjaxDump');
    if(response == 1) {
        //console.log("<p> USERNAME AVAILABLE </p>");
        //ajaxResults.innerHTML = "Available"
        //
        //navigate to landing page
        //window.location.href = "http://web.engr.oregonstate.edu/~weckwera/290/wk10/landing.html";
        window.location.assign(
                "http://web.engr.oregonstate.edu/~weckwera/290/wk10/landing.php");
    }
    else {
        console.log("ALREADY TAKEN");
        ajaxResults.innerHTML = "Login credentials invalid"
    }
}


//AJAX for username check
function checkUsername() {
    //create new request
    var req = new XMLHttpRequest();
    if (!req) {
        throw "Unable to create HttpRequest.";
    }
    //url should be appropriate php reference
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/lab.php';
    req.onload = function () {
        if (this.readyState === 4) {
            console.log(this.status); //tell me that you're doing something
            console.log(this.responseText);//check out server response
            
            //var response = JSON.parse(this.responseText);
            var response = (this.responseText);
            
            //call function with results
            checkUsernameAjaxResponse(response);
        }
    }
    var username = "username=" + document.getElementById('testUsername').value;
    var password = "password=" + document.getElementById('testPass').value;
    var county = "county=" + document.getElementById('county').value;
    var state = "state=" + document.getElementById('state').value;
    var fName = "fName=" + document.getElementById('fName').value;
    var lName = "lName=" + document.getElementById('lName').value;
    var email = "email=" + document.getElementById('email').value;
    var phone = "phone=" + (document.getElementById('phone').value).replace(/[^0-9.]/g, "");
    var test = "test=true";

    var args = username + "&" + password + "&" + county + "&" + state 
            + "&" + fName + "&" + lName + "&" + email + "&" + phone + "&" + test;
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}

function loginSubmit() {
    //create new request
    var req = new XMLHttpRequest();
    if (!req) {
        throw "Unable to create HttpRequest.";
    }
    //url should be appropriate php reference
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/lab.php';
    req.onload = function () {
        if (this.readyState === 4) {
            console.log(this.status); //tell me that you're doing something
            console.log(this.responseText);//check out server response
            
            //var response = JSON.parse(this.responseText);
            var response = (this.responseText);
            
            //call function with results
            loginResults(response);
        }
    }
    var username = "username=" + document.getElementById('loginUsername').value;
    var password = "password=" + document.getElementById('loginPass').value;
    var login = "login=true";

    var args = username + "&" + password + "&" + login;

    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}
