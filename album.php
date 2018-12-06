<html>
<head>
<title> Album </title>
</head>
<body>
<a href = 'index.php'> Home </a><br><br>
<a href = "sign-out.php"> Sign out</a><br><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && !isset($_GET['q'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewAlbum($_SESSION['user_id']);
}
elseif (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_GET['q'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewAlbum($_GET['q']);
}
else {
	echo "You are not allowed to view this page";
}
?>
<script>
function deleteAlbum(str) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "image.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).style.display = "none";
		}
	};
	var a = "a=" + str;
	xhttp.send(a);
}
</script>
</body>
</html>