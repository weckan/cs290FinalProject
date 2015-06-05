<?php
ini_set('display_errors',1);
include "storedInfo.php";
ini_set('session.save_path', '/nfs/stak/students/w/weckwera');
//start session
session_start();
//if session 'user' is not set, invalid navigation to page
#if ($_SESSION['user'] == NULL && !(isset($_POST['testUsername']) ||
#            isset($_GET['testUsername']))) {
#    logoutUser();
#}

#if(isset($_GET['logout'])) {
#    if($_GET['logout'] == 'true') {
#        logoutUser($_GET);
#}

#echo "in php";

# initialize mysqli, with error reporting
$mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);
if ($mysqli->connect_errno) {
    echo "MySQL Connection error";
}
#else {
#    echo "Connection successful!<br>";
#}

//if test is posted to server, check whether username is taken
if (isset($_POST['testUsername'])) {
    if (checkUsername($_POST) == 1) {
        addUser($_POST);
        //loginUser($_POST);
        //call header with landing page details
    }
}

if (isset($_GET['testUsername'])) {
    if (checkUsername($_GET) == 1) {
        addUser($_GET);
        //loginUser($_GET);
        //call header with landing page details
    }
}

// if login is posted to server, verify correct entry and login
// if invalid, return 0, otherwise, redirect to landing page
if (isset($_POST['login'])) {
    loginVerify($_POST);
}
if (isset($_POST['login'])) {
    loginVerify($_POST);
}

function logoutUser () {
    $_SESSION = array();
    session_destroy();
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/login.html", true);
    die();
}

function checkUsername ($array) {
    global $mysqli;

    //this value will be returned to calling statement
    $returnVal = 0;

    // search name is tested username
    $search_name = $mysqli->real_escape_string($array['testUsername']);

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
        echo '1';
        $returnVal = 1;
    }
    else {
        //echo 0, 'username is available' == FALSE
        echo '0';
#        logoutUser();
    }
    if (!$stmt->close()) {
        echo ":Statment not closed.";
    }
    return $returnVal;
}

#function loginUser ($array) {
#    //check for valid login with prepared statement
#    // if valid, set session id to user id
#    // if invalid, logout and return false
#    global $mysqli;
#
#    // search name is tested username
#    if ($array['testUsername']) {
#        $name = $mysqli->real_escape_string($array['testUsername']);
#        $pass = $mysqli->real_escape_string($array['testPass']);
#    }
#    else {
#        $name = $mysqli->real_escape_string($array['loginUsername']);
#        $pass = $mysqli->real_escape_string($array['loginPass']);
#    }
#
#    //text for prepared query
#    $query = "SELECT id FROM users WHERE username=? AND password=?";
#
#    //prepared statement details
#    /*prepare statement stage 1*/
#    if (!($stmt = $mysqli->prepare($query))) {
#       echo "FAILED USERNAME LOOKUP";
#    }
#    /*prepared statement bind/execute*/
#    if (!($stmt->bind_param('ss', $name, $pass))) {
#        echo "Binding parameters failed";
#    }
#    if (!$stmt->execute()) {
#        echo "Execute failed";
#    }
#    //result will be any matching ID
#    if (!$stmt->bind_result($id)) {
#        echo "Result bind failed";
#    }
#    //if the statement does not return an ID, user has logged in incorrectly
#    $stmt->fetch();
#    if ($id == NULL) {
#        echo '1';
#        logoutUser();
#    }
#    //if statement returns ID, assign as session user
#    //in prepared statements
#    else {
#        echo '0';
#        addUser($array);
#        loginUser($array);
#        //call header with landing page details
#    }
#}

function addUser ($array) {
    global $mysqli;

    // search name is tested username
    $name = $mysqli->real_escape_string($array['testUsername']);
    $pass = $mysqli->real_escape_string($array['testPass']);
    $county = $mysqli->real_escape_string($array['county']);
    $state = $mysqli->real_escape_string($array['state']);
    $fname = $mysqli->real_escape_string($array['fName']);
    $lname = $mysqli->real_escape_string($array['lName']);
    $email = $mysqli->real_escape_string($array['email']);
    $phone = $mysqli->real_escape_string($array['phone']);

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
    //$mysqli_result->free();
}
?>
