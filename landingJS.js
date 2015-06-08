// Contains functions relevant to landing page of final project
//
//window onload operations:
window.onload = function() {
    getAllSites();
}

function drawInvInfo(data) {
    clearMainTable();
    var thead = document.getElementById('dataHeader');

    var title = document.createElement("tr");
    var titleTextFill = document.createTextNode("Invasives Info");
    title.appendChild(titleTextFill);
    
    var labels = document.createElement("tr");
    
    var commonName = document.createElement("td");
    var commonNameText = document.createTextNode("Common Name");
    commonName.appendChild(commonNameText);
    var genus = document.createElement("td");
    var genusText = document.createTextNode("Genus");
    genus.appendChild(genusText);
    var species = document.createElement("td");
    var speciesText = document.createTextNode("Species");
    species.appendChild(speciesText);
    var notes= document.createElement("td");
    var notesText= document.createTextNode("Notes");
    notes.appendChild(notesText);
    var link= document.createElement("td");
    var linkText= document.createTextNode("More Info");
    link.appendChild(linkText);

    labels.appendChild(commonName);
    labels.appendChild(genus);
    labels.appendChild(species);
    labels.appendChild(notes);
    labels.appendChild(link);

    thead.appendChild(title);
    thead.appendChild(labels);

    var tbody = document.getElementById('allSites');
    var siteData = JSON.parse(data);
    for (var i = 0; i < siteData.length; i++) {
        //create row
        var tr = document.createElement("tr");
        //hold site ID
        var siteID = siteData[i].id;

        //create cells for each field, append each
        var commonName = document.createElement("td");
        var commonNameText = document.createTextNode(siteData[i].commonName);
        commonName.appendChild(commonNameText);
        
        var genus = document.createElement("td");
        var genusText = document.createTextNode(siteData[i].genus);
        genus.appendChild(genusText);
       
        var species = document.createElement("td");
        var speciesText = document.createTextNode(siteData[i].species);
        species.appendChild(speciesText);
       
        var notes= document.createElement("td");
        var notesText= document.createTextNode(siteData[i].notes);
        notes.appendChild(notesText);
       
        var link= document.createElement("td");
        var linkText= document.createElement('a');
        var linkDisplay = document.createTextNode(siteData[i].link);
        linkText.appendChild(linkDisplay);
        linkText.href= (siteData[i].link);
        link.appendChild(linkText);

        tr.appendChild(commonName);
        tr.appendChild(genus);
        tr.appendChild(species);
        tr.appendChild(notes);
        tr.appendChild(link);

    tbody.appendChild(tr);
    }
}

function drawMySites(data) {
    clearMainTable();
    var thead = document.getElementById('dataHeader');

    var title = document.createElement("tr");
    var titleTextFill = document.createTextNode("My Sites");
    title.appendChild(titleTextFill);
    
    var labels = document.createElement("tr");
    
    var siteID  = document.createElement("td");
    var siteIDText = document.createTextNode("Site ID");
    siteID.appendChild(siteIDText);
    var siteName = document.createElement("td");
    var siteNameText = document.createTextNode("Site Name");
    siteName.appendChild(siteNameText);
    var county = document.createElement("td");
    var countyText = document.createTextNode("County");
    county.appendChild(countyText);
    var state = document.createElement("td");
    var stateText = document.createTextNode("State");
    state.appendChild(stateText);
    var species= document.createElement("td");
    var speciesText= document.createTextNode("Species Present");
    species.appendChild(speciesText);
    var notes= document.createElement("td");
    var notesText= document.createTextNode("Site Notes");
    notes.appendChild(notesText);
    var lastE= document.createElement("td");
    var lastEText= document.createTextNode("Last Edited");
    lastE.appendChild(lastEText);

    labels.appendChild(siteID);
    labels.appendChild(siteName);
    labels.appendChild(county);
    labels.appendChild(state);
    labels.appendChild(species);
    labels.appendChild(notes);
    labels.appendChild(lastE);

    thead.appendChild(title);
    thead.appendChild(labels);


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

        var notes = document.createElement("td");
        var notesText = document.createTextNode(siteData[i].notes);
        notes.appendChild(notesText);
        
        var lastE = document.createElement("td");
        var lastEText = document.createTextNode(siteData[i].lastEdited);
        lastE.appendChild(lastEText);


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
        tr.appendChild(notes);
        tr.appendChild(lastE);
        
        //

    tbody.appendChild(tr);
    }
}

function drawAllSites(data) {
    clearMainTable();
    var thead = document.getElementById('dataHeader');

        var title = document.createElement("tr");
        var titleText = document.createElement("td");
        var titleTextFill = document.createTextNode("All Cleanup Sites");
        titleText.appendChild(titleTextFill);
        title.appendChild(titleText);
        
        var labels = document.createElement("tr");
        
        var siteID  = document.createElement("td");
        var siteIDText = document.createTextNode("Site ID");
        siteID.appendChild(siteIDText);
        var siteName = document.createElement("td");
        var siteNameText = document.createTextNode("Site Name");
        siteName.appendChild(siteNameText);
        var county = document.createElement("td");
        var countyText = document.createTextNode("County");
        county.appendChild(countyText);
        var state = document.createElement("td");
        var stateText = document.createTextNode("State");
        state.appendChild(stateText);
        var species = document.createElement("td");
        var speciesText = document.createTextNode("Species Present");
        species.appendChild(speciesText);
        var addSite= document.createElement("td");
        var addSiteText= document.createTextNode("Volunteer at Site");
        addSite.appendChild(addSiteText);

        labels.appendChild(siteID);
        labels.appendChild(siteName);
        labels.appendChild(county);
        labels.appendChild(state);
        labels.appendChild(species);
        labels.appendChild(addSite);

        thead.appendChild(title);
        thead.appendChild(labels);

    var tbody = document.getElementById('allSites');
    var siteData = JSON.parse(data);
    for (var i = 0; i < siteData.length; i++) {
        //create row
        var tr = document.createElement("tr");
        //hold site ID
        var siteID = siteData[i].id;

        //create cells for each field, append each
        var siteid = document.createElement("td");
        var siteidText = document.createTextNode(siteData[i].id);
        siteid.appendChild(siteidText);

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
        
        var mySite = document.createElement("td");
        var mySiteButton= document.createElement('input');
        mySiteButton.id = siteidText.nodeValue;
        mySiteButton.type = 'button';
        mySiteButton.value = 'Adopt Site';
        mySiteButton.onclick = function() {
            adoptSite(this.id);
            this.disabled = true;
            this.value = "Thanks!";
        }
        mySite.appendChild(mySiteButton);

        state.appendChild(stateText);

        tr.appendChild(siteid);
        tr.appendChild(name);
        tr.appendChild(county);
        tr.appendChild(state);
        tr.appendChild(comName);
        tr.appendChild(mySite);
        
        //

    tbody.appendChild(tr);
    }
}

function clearMainTable() {
    var thead = document.getElementById('dataHeader');
    while (thead.firstChild) {
        thead.removeChild(thead.firstChild);
    }
    var tbody = document.getElementById('allSites');
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
}

//AJAX for invasive information 
function getInvInfo() {
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
            drawInvInfo(response);
        }
    }

    var args = "getInvInfo=true";
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}
//AJAX call to get list of user sites, encoded as an associative array
function getMySites() {
    //create new request
    var req = new XMLHttpRequest();
    if (!req) {
        throw "Unable to create HttpRequest.";
    }
    //url should be appropriate php reference
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/lab.php';
    req.onload = function () {
        if (this.readyState === 4) {
            //console.log(this.status); //tell me that you're doing something
            console.log(this.responseText);//check out server response
            
            var response = (this.responseText);
            
            //call function with results
            drawMySites(response);
        }
    }
    var args = "mySites=true";
    
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}

//AJAX call to get list of all sites, encoded as an associative array
function getAllSites() {
    //create new request
    var req = new XMLHttpRequest();
    if (!req) {
        throw "Unable to create HttpRequest.";
    }
    //url should be appropriate php reference
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/lab.php';
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

function adoptSite(siteID) {
    var req = new XMLHttpRequest();
    var url = 'http://web.engr.oregonstate.edu/~weckwera/290/wk10/lab.php';
    var args = "siteID=" + siteID + "&adoptSite=true";
    req.open('POST', url);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(args);
}


