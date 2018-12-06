<html>
<head> 
<title>Friend's List </title>
</head>
<body>
<h2> Your friend list </h2>
<a href = 'index.php'> Home </a><br><br>
<a href = 'sign-out.php'> Sign out </a><br><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewfriends();
}
?>
</body>
</html>