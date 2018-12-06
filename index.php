<?php
require_once('db.php');
session_start();
if (!isset($_SESSION['username'])) {
	if (isset($_POST['submit'])) {
		$db = mysqli_connect(db_host, db_username, db_pass, database);
		$username = mysqli_real_escape_string($db, trim($_POST['username']));
		$password = mysqli_real_escape_string($db, trim($_POST['password']));
		if (!empty($username) && !empty($password)) {
			$query = "SELECT user_id, email_id, verfied FROM user_data WHERE email_id = '$username' AND password1 = sha('$password')";
			$result = mysqli_query($db, $query);
			if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_array($result);
				if ($row['verfied'] == "no") {
					$err = "Your account hasn't been verified";
				}
				else {
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['username'] = $row['email_id'];	
				if (isset($_POST['remember'])) {
					setcookie("user_id", $row['user_id'], time() + (30 * 60 * 60 * 24));
				    setcookie("username",  $row['email_id'], time() + (30 * 60 * 60 * 24));
				}
				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index1.php';
				header('Location: ' . $home_url);
				}
				}
			else {
				$err = "You must enter valid credentials";
			}
		}
		else {
			$err = "Fill both the fields";
		}
	}
	else {
		if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
			$_SESSION['user_id'] = $_COOKIE['user_id'];
			$_SESSION['username'] = $_COOKIE['username'];
			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index1.php';
		    header('Location: ' . $home_url);
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<link href = "home.css" rel = "stylesheet" type = "text/css">
<title> Welcome </title>
</head>
<body>
<div class = "sign-in">
<p> SIGN IN <p>
<?php
if (empty($_SESSION['user_id'])) {
	if (isset($_POST['submit'])) {
		echo "<span class = 'error'>$err</span>";
	}
?>
<form id = "sign-in" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
<label for = "username"> <p class = "ti">USER NAME</p></label>
<input type = "text" name = "username" placeholder = "Email id or Username" required>
<label for = "password"> <p class = "ti"> PASSWORD </p> </label>
<input type = "password" name = "password" placeholder = "Password" required> <br><br>
<input type = "checkbox" name = "remember"><span> Remember me</span><br> <br>
<input type = "submit" name = "submit" value = "Sign in"><br><br>
<span> No account? <a href = "sign-up.php"> Create one! </a></span>
</form>
</div>
<?php
}
else {
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index1.php';
    header('Location: ' . $home_url);
}
?>
</body>
</html>



















