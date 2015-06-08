<?php
ini_set('display_errors',1);
include "storedInfo.php";
ini_set('session.save_path', '/nfs/stak/students/w/weckwera');
//start session
session_start();

//if session 'user' is not set, invalid navigation to page
if (!(isset($_SESSION['userID'])) && !(isset($_POST['test']) ||
    isset($_GET['test']) || isset($_GET['login']) ||
    isset($_POST['login']))) {
    logoutUser();
}

//if logout parameter is posted, logout
if(isset($_POST['logout'])) {
    if($_POST['logout'] == 'true') {
        logoutUser($_GET);
        echo 1;
        die();
    }
}
if(isset($_GET['logout'])) {
    if($_GET['logout'] == 'true') {
        logoutUser($_GET);
        echo 1;
       die();
    }
}

# initialize mysqli, with error reporting
$mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);
if ($mysqli->connect_errno) {
    echo "MySQL Connection error";
}
#else {
#    echo "Connection successful!<br>";
#}

//if test is posted to server, check whether username is taken, login if not
// if user login fails, kill session and return false
if (isset($_POST['test'])) {
    if (checkUsername($_POST) == 1) {
        addUser($_POST);
        loginUser($_POST);
        if(loginUser($_POST) == 1) {
            echo 1;
        }
        else {
            //echo "Login Failed!";
            logoutUser($_POST);
        }
    } else {
        echo 0;
    }
}
if (isset($_GET['test'])) {
    if (checkUsername($_GET) == 1) {
        addUser($_GET);
        loginUser($_GET);
        if(loginUser($_GET) == 1) {
		echo 1;
        }
        else {
            //echo "Login Failed!";
            logoutUser($_GET);
        }
    } else {
        echo 0;
    }
}

//conditional cue for function to return all active non-private sites
if (isset($_GET['allSites']) || isset($_POST['allSites'])) {
     getAllSites();
}

//conditional cue for function to get sites matching user
if (isset($_GET['mySites']) || isset($_POST['mySites'])) {
     getMySites();
}

//conditional cue for function to add a site
if (isset($_GET['addSite'])) {
    $siteID = addSite($_GET);
    //echo "toploop siteID = $siteID ";
    foreach ($_GET['species'] as $spp) {
        //echo "toploop spp = $spp";
        addSiteSpecies($spp, $siteID);
    }
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/landing.php", true);
}
if (isset($_POST['addSite'])) {
    $siteID = addSite($_POST);
    //echo "toploop siteID = $siteID ";
    foreach ($_GET['species'] as $spp) {
        //echo "toploop spp = $spp";
        addSiteSpecies($spp, $siteID);
    }
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/landing.php", true);
}

//conditional cue for function to add a user site
if (isset($_GET['adoptSite'])) {
    $siteID = $_GET['siteID'];
    $userID = $_SESSION['userID'];
    adoptSite($siteID, $userID);
}
if (isset($_POST['adoptSite'])) {
    $siteID = $_POST['siteID'];
    $userID = $_SESSION['userID'];
    adoptSite($siteID, $userID);
}

//conditional cue for function to add invasive species
if (isset($_GET['addSpp'])) {
    addSpp($_GET);
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/landing.php", true);
}
if (isset($_POST['addSpp'])) {
    addSpp($_POST);
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/landing.php", true);
}

//conditional cue for function to get invasive info
if (isset($_GET['getInvInfo'])) {
    getInvInfo();
}
if (isset($_POST['getInvInfo'])) {
    getInvInfo();
}

// if login is posted to server, verify correct entry and login
// if invalid, return 0, otherwise, redirect to landing page
if (isset($_POST['login'])) {
    if(loginUser($_POST) == 1) {
        //echo "Login Successful!";
        //echo "Session id = " . session_id();
        echo 1;
    }
    else {
        //echo "Login Failed!";
        echo 0;
        logoutUser($_POST);
    }
}
if (isset($_GET['login'])) {
    if(loginUser($_GET) == 1) {
        //echo "Login Successful!";
        //echo "Session id = " . session_id();
        echo 1;
    }
    else {
        //echo "Login Failed!";
        echo 0;
        logoutUser($_GET);
    }
}

function logoutUser () {
    $_SESSION = array();
    session_destroy();
    //$filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    //$filePath = implode('/', $filePath);
    //$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    //header("Location: {$redirect}/login.html", true);
    //die();
}

function checkUsername ($array) {
    //echo "In checkUsername ";
    global $mysqli;

    //this value will be returned to calling statement
    $returnVal = 0;

    // search name is tested username
    $search_name = $mysqli->real_escape_string($array['username']);

    //text for prepared query
    $query = "SELECT fname, lname FROM users WHERE username=?";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo "FAILED USERNAME LOOKUP";
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('s', $search_name))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed username lookup";
    }
    if (!$stmt->bind_result($first, $last)) {
        echo "Result bind failed";
    }
    $stmt->fetch();

    if ($first == NULL) {
        //echo 1, 'username is available' == TRUE
        //echo '1';
        $returnVal = 1;
    }
    else {
        //echo 0, 'username is available' == FALSE
        //echo '0';
    }
    if (!$stmt->close()) {
        echo ":Statment not closed.";
    }
    return $returnVal;
}

function loginUser ($array) {
    //check for valid login with prepared statement
    // if valid, set session id to user id
    // if invalid, logout and return false

    //echo "In loginUser ";

    global $mysqli;
    $returnVar = 0;

    $name = $mysqli->real_escape_string($array['username']);
    $pass = $mysqli->real_escape_string($array['password']);
    // search name is tested username

    //text for prepared query
    $query = "SELECT id FROM users WHERE username=? AND password=?";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo "FAILED USERNAME LOOKUP";
    }
//echo ">4: ";
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('ss', $name, $pass))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed";
    }
    //result will be any matching ID
    if (!$stmt->bind_result($userID)) {
        echo "Result bind failed";
    }
    //if the statement does not return an ID, user has logged in incorrectly
    $stmt->fetch();
    if ($userID == NULL) {
        //incorrect login info, login == FALSE
        $returnVar = 0;
        //echo '0';
    }
    //if statement returns ID, assign as session user
    //in prepared statements
    else {
        //correct login info, login == TRUE
        //echo '1';
        $returnVar = 1;
        //set session ID for use in referring to user

        //session_id($id);
        $_SESSION['userID'] = $userID;
    }
    return $returnVar;
}

function addUser ($array) {

    //echo "In addUser ";

    global $mysqli;

    // search name is tested username
    $name = $mysqli->real_escape_string($array['username']);
    $pass = $mysqli->real_escape_string($array['password']);
    $county = $mysqli->real_escape_string($array['county']);
    $state = $mysqli->real_escape_string($array['state']);
    $fname = $mysqli->real_escape_string($array['fName']);
    $lname = $mysqli->real_escape_string($array['lName']);
    $email = $mysqli->real_escape_string($array['email']);
    $phone = $mysqli->real_escape_string($array['phone']);
    if ($phone == '') {
        //this handles an empty string if the user left HTML form blank
        $phone = NULL;
    }

    //text for prepared query
    $query = "INSERT INTO users (username, password, county, state, fname, lname,
        email, phone) values (?, ?, ?, ?, ?, ?, ?, ?)";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('sssssssi', $name, $pass, $county, $state, $fname,
                $lname, $email, $phone))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed insert user";
        echo $stmt->error;
    }
    if (!$stmt->close()) {
        echo "Statment not closed.";
    }
}

function getAllSites() {
    global $mysqli;

    $result = $mysqli->query("SELECT sites.id, sites.name, sites.county,
        sites.state, species.commonName FROM sites INNER JOIN site_species ON
        sites.id = site_species.siteID INNER JOIN species ON
        site_species.speciesID = species.id WHERE sites.private=0 ORDER
        BY sites.id");

    $allRows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$allRows = mysqli_fetch_all($result, MYSQLI_NUM);

    //return array to ajax call
    echo json_encode($allRows);
}

function getMySites() {
    global $mysqli;

    $userID = $mysqli->real_escape_string($_SESSION['userID']);

    //text for prepared query
    //
    $query = "SELECT sites.id, sites.name, sites.county,
        sites.state, species.commonName, sites.notes, sites.lastEdited
        FROM sites INNER JOIN site_species ON
        sites.id = site_species.siteID INNER JOIN species ON
        site_species.speciesID = species.id INNER JOIN user_sites ON
        sites.id = user_sites.siteID WHERE sites.private=0 AND
        user_sites.userID = ? ORDER BY sites.id";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('i', $userID))) {
        echo "Binding parameters failed";
    }
    if (!($result = $stmt->execute())) {
        echo "Execute failed insert user";
        echo $stmt->error;
    }

    // code section taken from user "Chris" at stack overflow:
    // http://stackoverflow.com/questions/994041/how-can-i-put-the-results-of-a-mysqli-prepared-statement-into-an-associative-arr
    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $params);
    while ($stmt->fetch()) {
            foreach($row as $key => $val) {
                        $c[$key] = $val;
                            }
                $hits[] = $c;
    }
    echo json_encode($hits);
    $stmt->close();
    //end SO code
}

function addSite($array) {

    //echo "In addUser ";

    global $mysqli;

    // search name is tested username
    $name = $mysqli->real_escape_string($array['name']);
    $county = $mysqli->real_escape_string($array['county']);
    $state = $mysqli->real_escape_string($array['state']);
    $notes = $mysqli->real_escape_string($array['notes']);
    $lastEdit = $mysqli->real_escape_string($array['lastEdit']);
    if ($array['lastEdit'] == "") {
        $lastEdit = NULL;
    }

    //text for prepared query
    $query = "INSERT INTO sites (name, county, state, notes, lastEdited)
        values (?, ?, ?, ?, ?)";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('sssss', $name, $county, $state, $notes,
            $lastEdit))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed insert site";
        echo $stmt->error;
    }
    if (!$stmt->close()) {
        echo "Statment not closed.";
    }

    $query = ("SELECT sites.id FROM sites
        WHERE sites.name=?");

    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED select site id PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('s', $name))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed get siteID";
        echo $stmt->error;
    }

    $result = $stmt->get_result();
    $siteID = $result->fetch_assoc();

    if (!$stmt->close()) {
        echo "Statment not closed.";
    }

    //$siteID = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //echo $siteID['id'];
    return $siteID['id'];
}

function addSiteSpecies($spp, $siteID) {

    //echo "In addSiteSpecies ";

    global $mysqli;

    // search name is tested username
    $speciesID = $mysqli->real_escape_string($spp);
    $siteID = $mysqli->real_escape_string($siteID);

    //text for prepared query
    $query = "INSERT INTO site_species (siteID, speciesID)
        values (?, ?)";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('ii', $siteID, $speciesID))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed insert site spp";
        echo $stmt->error;
    }
    if (!$stmt->close()) {
        echo "Statment not closed.";
    }
}

function adoptSite($siteID, $userID) {

    echo "In addUser ";
    echo "siteID = $siteID, userID = $userID ";

    global $mysqli;

    //text for prepared query
    $query = "INSERT INTO user_sites (userID, siteID) values (?, ?)";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER_Sites PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('ii', $userID, $siteID))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed insert user_site";
        echo $stmt->error;
    }
    if (!$stmt->close()) {
        echo "Statment not closed.";
    }
}

function getInvInfo() {
    global $mysqli;

    $result = $mysqli->query("SELECT species.commonName, species.genus,
        species.species, species.notes, species.link FROM species");

    $allRows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$allRows = mysqli_fetch_all($result, MYSQLI_NUM);

    //return array to ajax call
    echo json_encode($allRows);
}

function addSpp($array) {

    //echo "In addUser ";

    global $mysqli;

    // search name is tested username
    $name = $mysqli->real_escape_string($array['commonName']);
    $genus = $mysqli->real_escape_string($array['genus']);
    $species = $mysqli->real_escape_string($array['species']);
    $notes = $mysqli->real_escape_string($array['notes']);
    $link = $mysqli->real_escape_string($array['link']);

    //text for prepared query
    $query = "INSERT INTO species (commonName, genus, species, notes, link)
        values (?, ?, ?, ?, ?)";

    //prepared statement details
    /*prepare statement stage 1*/
    if (!($stmt = $mysqli->prepare($query))) {
       echo ":FAILED INSERT USER PREPARE";
        echo $mysqli->error;
    }
    /*prepared statement bind/execute*/
    if (!($stmt->bind_param('sssss', $name, $genus, $species, $notes,
            $link))) {
        echo "Binding parameters failed";
    }
    if (!$stmt->execute()) {
        echo "Execute failed insert site";
        echo $stmt->error;
    }
    if (!$stmt->close()) {
        echo "Statment not closed.";
    }
}
?>
