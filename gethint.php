<?php
require_once("db.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	if (isset($_POST['q'])) {
	$q = $_POST['q'];
    $db = mysqli_connect(db_host, db_username, db_pass, database) or die("error connecting");
    $query = 'SELECT first_name, last_name, user_id FROM user_data WHERE first_name LIKE "'.$q.'%" ';
    $result = mysqli_query($db, $query) or die("error querying");
    $hint = "";
    while($row = mysqli_fetch_array($result)) {
          if ($row['user_id'] != $_SESSION['user_id']) {
			  $hint .= "<p><a href = 'view-profile.php?q=" .$row['user_id']. "'>{$row['first_name']}, {$row['last_name']}</a><p>";
		  }
    }
    echo $hint;
   }
}
?>