<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	if (isset($_POST['a'])) {
		$status = 2;
		$user = new User($_SESSION['user_id']);
		$user->changeStatus($status, $_POST['a']);
	}
	elseif (isset($_POST['r'])) {
		$status = 0;
		$user = new User($_SESSION['user_id']);
		$user->changeStatus($status, $_POST['r']);
	}
	else {
		echo "database not set";
	}
}
?>