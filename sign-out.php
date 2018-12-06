<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	$_SESSION = array();
	session_destroy();
	if (isset($_COOKIE['username']) && isset($_COOKIE['user_id'])) {
		setcookie('username', '', time() - 3600);
		setcookie('user_id', '', time() - 3600);
	}
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
	header('Location: ' . $home_url);
}
?>