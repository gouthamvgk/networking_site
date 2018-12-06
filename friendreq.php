<?php
require_once("db.php");
require_once("app.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
	if (isset($_POST['submit'])) {
		$s = 1;
		$user = new User($_SESSION['user_id']);
	    $user->friendreq($_POST['user'], $s);
	}
	elseif (isset($_POST['submit1'])) {
		$s = 3;
		$user = new User($_SESSION['user_id']);
	    $user->friendreq($_POST['user'], $s);
	}
	elseif (isset($_POST['submit2'])) {
		$s = 0;
		$user = new User($_SESSION['user_id']);
	    $user->friendreq($_POST['user'], $s);
	}
	elseif (isset($_POST['submit3'])) {
		$s = 2;
		$user = new User($_SESSION['user_id']);
	    $user->friendreq($_POST['user'], $s);
	}
}
else {
	echo "You are not allowed to view this page";
	echo "<a href='index.php'> Home </a>";
}
?>