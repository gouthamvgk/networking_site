<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])&& isset($_POST['a']) && isset($_POST['r'])) {
	$user = new User($_SESSION['user_id']);
	$s = $user->changeLike($_POST['r'], $_POST['a']);
	echo "$s";
}
elseif (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_POST['u']) && isset($_POST['p'])  && isset($_POST['i'])) {
	$user = new User($_SESSION['user_id']);
	$v = $user->changeLikeimage($_POST['u'],$_POST['p'], $_POST['i']);
	echo "$v";
}
else {
	echo "You are not allowed to view this page";
}
?>