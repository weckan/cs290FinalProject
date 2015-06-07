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
    <title>Park Cleanup Home</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
     <script src="landingJS.js"></script>

</header>
 <body>
  <br>
<div class="pure-menu pure-menu-horizontal">
    <a href="#" class="pure-menu-heading pure-menu-link"></a>
    <ul class="pure-menu-list">
        <li class="pure-menu-item">
        <button class="pure-button pure-button-primary" onclick="getMySites()">
        Your Cleanup Sites
        </button></li>
        <li class="pure-menu-item"><button class="pure-button pure-button-primary">
        Invasive Species Information
        </button></li>
        <li class="pure-menu-item"><button class="pure-button pure-button-primary">
        Volunteer Phonebook
        </button></li>
        <li class="pure-menu-item">
        <a class="pure-button pure-button-primary" href="http://web.engr.oregonstate.edu/~weckwera/290/wk10/addSite.php">
        Add Site</a>
        Add Site
        </button></li>
        <li class="pure-menu-item">
        <button class="pure-button pure-button-primary" onclick="logoutUser()">
        Logout
        </button></li>
    </ul>
</div>
<br>
<br>
  <table class="pure-table pure-table-bordered">
   <thead id="dataHeader">
   </thead>
  <tbody id="allSites">
 </body>
 </html>
