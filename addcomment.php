<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_POST['a']) && isset($_POST['r'])  && isset($_POST['s'])) {
	$user = new User($_SESSION['user_id']);
	$s = $user->addComment($_POST['r'], $_POST['a'], $_POST['s']);
	echo "$s";
}
elseif(isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_POST['u']) && isset($_POST['p'])  && isset($_POST['i']) && isset($_POST['c'])) {
	$user = new User($_SESSION['user_id']);
	$r = $user->addCommentimage($_POST['u'], $_POST['p'], $_POST['i'], $_POST['c']);
	echo "$r";
}
else {
	echo "database not set<br>";
}
?>