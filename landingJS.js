// Contains functions relevant to landing page of final project
//
//window onload operations:
window.onload = function() {
    getAllSites();
}

function drawAllSites(data) {
    var tbody = document.getElementById('allSites');
    var siteData = JSON.parse(data);
    for (var i = 0; i < siteData.length; i++) {
        //create row
        var tr = document.createElement("tr");
        //hold site ID
        var siteID = siteData[i].id;

        //create cells for each field, append each
        var id = document.createElement("td");
        var idText = document.createTextNode(siteData[i].id);
        id.appendChild(idText);

        var name = document.createElement("td");
        var nameText = document.createTextNode(siteData[i].name);
        name.appendChild(nameText);

        var county = document.createElement("td");
        var countyText = document.createTextNode(siteData[i].county);
        county.appendChild(countyText);

        var state = document.createElement("td");
        var stateText = document.createTextNode(siteData[i].state);
        state.appendChild(stateText);

        var comName = document.createElement("td");
        //go through to get all species from each site by siteID
        var comNameList = (siteData[i].commonName);
        //while (siteData[i].id == siteID && ((i+1)!= siteData.length)) {
        //iterate through rest of array to find all species at a common site
        for(var j = i+1; j < siteData.length; j++) {
            if (siteData[j].id == siteID) {
                //concatenate next spp
                comNameList = comNameList + ", " + siteData[j].commonName;
            }
            else {
                //if ID is not the same, skip printing all rows of i that were
                //duplicates, jump to presently examined row
                i = j-1;
                break;
            }
        }
        var comNameText = document.createTextNode(comNameList);
        comName.appendChild(comNameText);

        tr.appendChild(id);
        tr.appendChild(name);
        tr.appendChild(county);
        tr.appendChild(state);
        tr.appendChild(comName);
    tbody.appendChild(tr);
    }
}

//AJAX call to get list of all sites, encoded as an associative array
function getAllSites() {
    //create new request
    var req = new XMLHttpRequest();
    if (!req) {
        throw "Unable to create HttpRequest.";
    }
    //url should be appropriate php reference
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/main.php';
    req.onload = function () {
        if (this.readyState === 4) {
            //console.log(this.status); //tell me that you're doing something
            console.log(this.responseText);//check out server response
            
            var response = (this.responseText);
            
            //call function with results
            drawAllSites(response);
        }
    }
    var args = "allSites=true";
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}




function logoutResponse(response) {
    // response is supplied data from AJAX
    if(response == 1) {
        window.location.assign("http://web.engr.oregonstate.edu/~weckwera/290/wk10/login.html");
    }
    else {
        console.log("Logout unsuccessful");
    }
}   

function logoutUser() {
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
            logoutResponse(response);
        }
    }
    var logout = "logout=true";
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(logout);
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
    var username = "testUsername=" + document.getElementById('testUsername').value;
    var password = "testPass=" + document.getElementById('testPass').value;
    var county = "county=" + document.getElementById('county').value;
    var state = "state=" + document.getElementById('state').value;
    var fName = "fName=" + document.getElementById('fName').value;
    var lName = "lName=" + document.getElementById('lName').value;
    var email = "email=" + document.getElementById('email').value;
    var phone = "phone=" + (document.getElementById('phone').value).replace(/[^0-9.]/g, "");

    var args = username + "&" + password + "&" + county + "&" + state 
            + "&" + fName + "&" + lName + "&" + email + "&" + phone;
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}

