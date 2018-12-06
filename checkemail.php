<?php
require_once("db.php");
$db = mysqli_connect(db_host, db_username, db_pass, database);
if (isset($_POST['s'])) {
	$query = "SELECT * FROM user_data WHERE email_id = '{$_POST['s']}'";
	$result = mysqli_query($db, $query) or die('error');
	if (mysqli_num_rows($result) == 0) {
		echo "Email id available";
	}
	else {
		echo "Email id not available";
	}
}
?>