<?php
require_once('db.php');
require_once('class2.php');
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['a'])) {
	$user = new User($_SESSION['user_id']);
	$user->deleteAlbum($_POST['a']);
}
elseif (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['s']) && isset($_POST['r'])) {
	$user = new User($_SESSION['user_id']);
	$user->deletePicture($_POST['s'], $_POST['r']);
}
else {
	echo "You are not allowed to view this page";
}
?>