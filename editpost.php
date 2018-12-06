<html>
<head>
<title> Edit post </title>
</head>
<body>
<a href = "index.php"> Go to Home </a><br><br>
<a href = "view-post.php"> View my posts</a><br><br>
<a href = "sign-out.php"> Sign out</a><br><br>
<?php
require_once("db.php");
require_once("app.php");
require_once("class2.php");
session_start();
$db = mysqli_connect(db_host, db_username, db_pass, database);
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_POST['submit'])) {
        $query = "SELECT * FROM post WHERE user_id = {$_SESSION['user_id']} AND post_no = {$_POST['post_no']}";
		$result = mysqli_query($db, $query) or die('error querrying');
		$row = mysqli_fetch_array($result);
		if (!empty($row['image'])) {
			echo "<div id = {$row['post_no']} ><img src = '{$row['image']}' width = '150' height = '150' id = {$row['image']}><br><br>";
			echo "<button type = 'button' value = {$row['post_no']} onclick = 'removeimage(this.value)' id = {$row['post_no']}> Remove Picture </button><br><br></div>";
		}
		echo <<<END
		<form id = {$row['post_no']} method = "post" action = "{$_SERVER['PHP_SELF']}" enctype="multipart/form-data">
		<input type = "hidden" name = "post_no" value = {$row['post_no']}>
		<label for = "image">Add or Change image: </label><br><br>
		<input type="file" id= "post_picture" name="post_picture" ><br><br>
		<textarea name = 'content' maxlength = '1000' rows = '5' cols='60' placeholder = "Post something here" required>{$row['content']} </textarea><br><br>
		<label for = "visibility"> Visible to </label>
		<select name = "visibility"> 
END;
?>
        <option value = 0 <?php if ($row['visibility'] == 0) echo "selected"?> > Everyone </option>
        <option value = 1 <?php if ($row['visibility'] == 1) echo "selected"?> > Only to friends </option>
        </select><br><br>
		<input type = "submit" name = "a" value = "Save changes">
        </form>		
<?php
}
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_POST['a'])) {
	$user = new User($_SESSION['user_id']);
	$user->editpost($_POST['post_no']);
}

?>
<script>
function removeimage(str) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "post.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(str).style.display = "none" ;
			document.getElementById("post_picture").disabled = false;
		}
	};
	var r = "r=" + str;
	xhttp.send(r);
}
</script>
</body>
</html>