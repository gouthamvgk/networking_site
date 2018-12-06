<html>
<head>
<title> My posts </title>
</head>
<body>
<a href = 'index.php'> Go to Home </a><br> <br>
<a href = 'sign-out.php'> Sign out </a><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && !isset($_GET['q'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewpost($_SESSION['user_id']);
}
elseif (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_GET['q'])) {
	$user = new User($_SESSION['user_id']);
	$user->viewpost($_GET['q']);
}
?>
<script id = "erase">
function deletePost(str) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "post.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).style.display = "none" ;
		}
	};
	var a = "a=" + str;
	xhttp.send(a);
}
function like(post_no, user) {
	var s = "count" + post_no;
	var x = "like" + post_no;
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
	var a = "a=" + post_no + "&r=" + user;
	xhttp.send(a);
}
function comment(post_no, user) {
	var s = "comment" + post_no;
	var x = "co" + post_no;
	var q = document.getElementById(x).value;
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
	    var a = "a=" + post_no + "&r=" + user + "&s=" + q;
	    xhttp.send(a);
	}
}
document.getElementById("erase").innerHTML = "";
</script>
</body>
</html>