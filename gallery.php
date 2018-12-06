<html>
<head>
<title> Photos </title>
</head>
<body>
<a href = 'index.php'> Go to home </a><br> <br>
<a href = "sign-out.php"> Sign out</a><br><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_GET['q']) && isset($_GET['r'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewGallery($_GET['q'], $_GET['r']);
}
else {
	echo "You are not allowed to view this page<br>";
}
?>
<script>
function deletePicture(str) {
	var xhttp;
	var s = document.getElementById('album_no').value;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "image.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).style.display = "none";
		}
	};
	var a = "s=" + s + "&r=" + str;
	xhttp.send(a);
}
function like(image_no, user) {
	var s = "count" + image_no;
	var t = document.getElementById('album_no').value;
	var x = "like" + image_no;
	var r = document.getElementById(x).innerHTML;
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "likes.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(s).innerHTML = this.responseText;
			if (r == "Like") {
				document.getElementById(x).innerHTML = "Unlike";
			}else {
				document.getElementById(x).innerHTML = "Like";
			}
			
		}
	};
	var a = "u=" + user + "&p=" + t + "&i=" + image_no;
	xhttp.send(a);
}
function comment(image_no, user) {
	var s = "comment" + image_no;
	var x = "co" + image_no;
	var q = document.getElementById(x).value;
	var f = document.getElementById('album_no').value;
	if (q.length != 0) {
		var xhttp;
	    xhttp = new XMLHttpRequest();
	    xhttp.open("POST", "addcomment.php", true);
	    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	    xhttp.onreadystatechange = function () {
		    if (this.readyState == 4 && this.status == 200) {
			    document.getElementById(s).innerHTML = this.responseText;
				document.getElementById(x).value = "";
		    }
	    };
	    var a = "u=" + user + "&p=" + f + "&i=" + image_no + "&c=" + q;
	    xhttp.send(a);
	}
}
</script>
</body>
</html>