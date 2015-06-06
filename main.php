<?php
ini_set('display_errors',1);
include "storedInfo.php";
ini_set('session.save_path', '/nfs/stak/students/w/weckwera');
//start session
session_start();

//if session 'user' is not set, invalid navigation to page
if (session_name() == NULL && !(isset($_POST['testUsername']) ||
    isset($_GET['testUsername']) || isset($_GET['login']) ||
    isset($_POST['login']))) {
    logoutUser();
}

if(isset($_GET['logout'])) {
    if($_GET['logout'] == 'true') {
        logoutUser($_GET);
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


function logoutUser () {
    $_SESSION = array();
    session_destroy();
    $filePath = explode('/',$_SERVER['PHP_SELF'], -1);
    $filePath = implode('/', $filePath);
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
    header("Location: {$redirect}/login.html", true);
    die();
}

function addUser ($array) {

    //echo "In addUser ";

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
