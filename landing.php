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
<div class="header"><h1>Invasive Species Removal Sucks</h1>
<h2>It takes a lot of manpower to clean up your community natural areas. Find out what needs to be done near you!<h2>
</div>
<div>
<p>Most small communities have area parks and natural areas that are highly regarded. Biological invasives are often a threat to the natural heritage of these areas, and concerned citizens can maximize their efforts by tracking their local sites.</p>
</div>
<div class="pure-menu pure-menu-horizontal">
    <a href="#" class="pure-menu-heading pure-menu-link"></a>
    <ul class="pure-menu-list">
        <li class="pure-menu-item">
        <button class="pure-button pure-button-primary" onclick="getAllSites()">
        All Sites
        </button></li>
        <li class="pure-menu-item">
        <button class="pure-button pure-button-primary" onclick="getMySites()">
        Your Cleanup Sites
        </button></li>
        <li class="pure-menu-item">
        <button class="pure-button pure-button-primary" onclick="getInvInfo()">
        Invasive Species Information
        </button></li>
        <li class="pure-menu-item">
        <a class="pure-button pure-button-primary" href="http://web.engr.oregonstate.edu/~weckwera/290/wk10/addSpecies.php">
        Add Species</a>
        </button></li>
        <li class="pure-menu-item">
        <a class="pure-button pure-button-primary" href="http://web.engr.oregonstate.edu/~weckwera/290/wk10/addSite.php">
        Add Site</a>
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
