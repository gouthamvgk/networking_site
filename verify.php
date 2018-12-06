<?php
require_once("db.php");
if (isset($_GET['email']) && isset($_GET['random'])) {
	$email = $_GET['email'];
	$random = $_GET['random'];
	$db = mysqli_connect(db_host, db_username, db_pass, database);
	$query = "SELECT random, verfied FROM user_data WHERE email_id = '$email'";
	$result = mysqli_query($db, $query)  or die("error querying");
	$row = mysqli_fetch_array($result);
	if ($random = $row['random']) {
		$query2 = "UPDATE user_data SET verfied = 'yes' WHERE email_id = '$email'";
		$result2 = mysqli_query($db, $query2) or die("error in verifying");
		echo "Your account has been verified  "."<a href='index.php'>Login here </a>";
	}
	else {
		die("Inapporapriate verification link");
	}
}
else {
	die("Something happened");
}
?>