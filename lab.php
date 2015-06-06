<?php
ini_set('display_errors',1);
include "storedInfo.php";
ini_set('session.save_path', '/nfs/stak/students/w/weckwera');
//start session
session_start();

//if session 'user' is not set, invalid navigation to page
if (session_name() == NULL && !(isset($_POST['test']) ||
    isset($_GET['test']) || isset($_GET['login']) ||
    isset($_POST['login']))) {
    logoutUser();
}

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

#echo "in php";

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
            //echo "Login Successful!";
            //echo "Session id = " . session_id();
            //$filePath = explode('/',$_SERVER['PHP_SELF'], -1);
            //$filePath = implode('/', $filePath);
            //$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
            //header("Location: {$redirect}/landing.html", true);
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
            //echo "Login Successful!";
            //echo "Session id = " . session_id();
            //$filePath = explode('/',$_SERVER['PHP_SELF'], -1);
            //$filePath = implode('/', $filePath);
            //$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
            //header("Location: {$redirect}/landing.html", true);
        }
        else {
            //echo "Login Failed!";
            logoutUser($_GET);
        }
    } else {
        echo 0;
    }
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

//var_dump($_SESSION);

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
?>
