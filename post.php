<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['a'])) {
	$user = new User($_SESSION['user_id']);
	$user->deletepost($_POST['a']);
}
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['r'])) {
	$user = new User($_SESSION['user_id']);
	$user->removepicture($_POST['r']);
}
else {
	echo 'You are not allowed to view this page';
}
?>