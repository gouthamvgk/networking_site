<html> 
<head>
<title> Your profile </title>
</head>
<body> 
<?php
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && !isset($_GET['q'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewownprofile();
}
elseif (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_GET['q']) && $_GET['q'] != $_SESSION['user_id']) {
	$user = new User($_SESSION['user_id']);
	$user->viewotherprofile($_GET['q']);
}
else {
  echo "database not set";
}
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
</body>
</html>