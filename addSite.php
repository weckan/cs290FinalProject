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
    <title>Add a Site</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
     <script src="landingJS.js"></script>

</header>
 <body>
  <br>
<form class="pure-form pure-form-stacked" action='lab.php' method='POST'>
    <fieldset>
        <div class="pure-control-group">
            <label for="name">Site Name</label>
            <input name="name" id="name" type="text" placeholder="Site Name">
        </div>

        <div class="pure-control-group">
            <label for="county">County</label>
            <input name="county" id="county" type="text" placeholder="County">
        </div>

        <div class="pure-control-group">
            <label for="state">State</label>
            <select name="state"id='state' placeholder="State">
            <option value=""></option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
            </select>
        </div>

        <div class="pure-control-group">
            <label for="notes">Site Notes</label>
            <input name="notes" id="notes" type="text" placeholder="Site notes">
        </div>

        <div class="pure-control-group">
            <label for="species">Species Present</label>
        <?php getSpecies(); ?>
        </div>

        <div class="pure-control-group" style="display: none;">
            <label for="lastEdit"></label>
            <input name="lastEdit" id="lastEdit" value ="<?php echo date('Y-m-d');?>"
            type="text" placeholder="">
        </div>

        <div class="pure-control-group" style="display: none;">
            <label for="addSite"></label>
            <input name="addSite" id="addSite" value ="true" type="text" placeholder="addSite">
        </div>

            <button type="submit" class="pure-button pure-button-primary">Submit</button>
        </div>
    </fieldset>
</form>
 </body>
 </html>

<?php

function getSpecies() {
    global $mysqli;

    $result = $mysqli->query("SELECT species.commonName, species.id
        FROM species");

    for ($row_no = 0; $row_no < $result->num_rows; $row_no++) {
        $result->data_seek($row_no);
        $row = $result->fetch_assoc();
        echo "<input type='checkbox' name='species[]' value=$row[id]>$row[commonName]<br>";
    }
    //return array to ajax call
    //echo json_encode($allRows);
}

?>

