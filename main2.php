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

#echo "in php";

# initialize mysqli, with error reporting
$mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);
if ($mysqli->connect_errno) {
    echo "MySQL Connection error";
}
#else {
#    echo "Connection successful!<br>";
#}

if (isset($_GET['allSites']) || isset($_POST['allSites'])) {
    getAllSites();
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

function getAllSites() {
    global $mysqli;

    $result = $mysqli->query("SELECT sites.id, sites.name, sites.county,
        sites.state, species.commonName FROM sites INNER JOIN site_species ON
        sites.id = site_species.siteID INNER JOIN species ON
        site_species.speciesID = species.id WHERE sites.private=0");

    $allRows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$allRows = mysqli_fetch_all($result, MYSQLI_NUM);

    //return array to ajax call
    echo json_encode($allRows);
}


?>
<!DOCTYPE html>
<html>
<header>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
     <script src="landingJS.js"></script>

</header>
 <body>
   <input type="button" value="Logout" name="logout" onclick="logoutUser()"/>
  <br>
   <input type="button" value="All Sites" name="logout" onclick="getAllSites()"/>
  <br>
   <input type="button" value="New Site" name="logout" onclick="showSiteForm()"/>
  <br>
  <table>
   <thead>
   <tr>
    <td>Site Name</td>
    <td>County</td>
    <td>State</td>
    <td>Species Present</td>
   </tr>
   </thead>
  <tbody id="allSites">
 </body>
</html>
