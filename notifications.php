<html>
<head> <title> Notifications </title>
</head>
<body>
<a href='index.php'> Go to Profile</a><br><br>
<a href='sign-out.php'> Sign out</a><br><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['username'] ) {
	$user = new User($_SESSION['user_id']);
	$user->notifications();
?>
<script>
function aa1(str) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "request.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).innerHTML = "Added as friend";
		}
	};
	var a = "a=" + str;
	xhttp.send(a);
}
function dd1(str) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "request.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).innerHTML = "Friend request deleted";
		}
	};
	var r = "r=" + str;
	xhttp.send(r);
}
</script>
<?php 
}
?>
</body>
</html>