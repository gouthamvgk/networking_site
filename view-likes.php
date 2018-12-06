<html>
<head>
<title> Likes </title>
</head>
<body> 
<a href = 'index.php'> Go to Home </a><br> <br>
<a href = 'sign-out.php'> Sign out </a><br>
<h2> The likes for this post are </h2>
<?php
require_once("class2.php");
require_once("db.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_GET['q']) && isset($_GET['a'])){
	$user = new User($_SESSION['user_id']);
	$user->viewLikes($_GET['q'], $_GET['a']);
}
elseif (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_GET['u']) && isset($_GET['p']) && isset($_GET['i'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewLikesimage($_GET['u'], $_GET['p'], $_GET['i']);
}
else {
	echo "You are not allowed to view this page";
}
?>
</body>
</html>