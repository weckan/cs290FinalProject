<?php
ini_set('display_errors',1);
include "storedInfo.php";
ini_set('session.save_path', '/nfs/stak/students/w/weckwera');
//start session
session_start();

//if session 'user' is not set, invalid navigation to page
//if ($_SESSION['userID'] == NULL && !(isset($_POST['testUsername']) ||
if (!(isset($_SESSION['userID'])) && !(isset($_POST['testUsername']) ||
    isset($_GET['testUsername']) || isset($_GET['login']) ||
    isset($_POST['login']))) {
    logoutUser();
}

if(isset($_GET['logout'])) {
    if($_GET['logout'] == 'true') {
        logoutUser($_GET);
    }
}

$mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);
if ($mysqli->connect_errno) {
    echo "MySQL Connection error";
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
?>
<!DOCTYPE html>
<html>
<header>
    <meta charset="UTF-8">
    <title>Add a Species to Track</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
     <script src="landingJS.js"></script>

</header>
 <body>
  <br>
<form class="pure-form pure-form-stacked" action='lab.php' method='POST'>
    <fieldset>
        <div class="pure-control-group">
            <label for="commonName">Species Common Name</label>
            <input name="commonName" id="commonName" type="text" placeholder="Site Name">
        </div>

        <div class="pure-control-group">
            <label for="genus">Genus</label>
            <input name="genus" id="genus" type="text" placeholder="County">
        </div>

        <div class="pure-control-group">
            <label for="species">Species</label>
            <input name="species" id="species" type="text" placeholder="Site species">
        </div>

        <div class="pure-control-group">
            <label for="notes">General Notes</label>
            <input name="notes" id="notes" type="text" placeholder="Site notes">
        </div>

        <div class="pure-control-group">
            <label for="link">Add a Wikipedia Link:</label>
            <input name="link" id="link" type="text" placeholder="Site link">
        </div>

        <div class="pure-control-group" style="display: none;">
            <label for="addSpp"></label>
            <input name="addSpp" id="addSpp" value ="true" type="text" placeholder="addSpp">
        </div>

            <button type="submit" class="pure-button pure-button-primary">Submit</button>
        </div>
    </fieldset>
</form>
 </body>
 </html>

