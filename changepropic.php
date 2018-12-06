<html>
<head>
<link href = "sign-up.css" rel = "stylesheet" type = "text/css">
<title> Change profile picture </title>
</head>
<body>
<?php
ini_set('display_errors', 'off');
ini_set('log_errors', 'on');
require_once("db.php");
require_once("app.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && !isset($_POST['submit'])) {
?>
<div class = "sign-up">
<p> Change profile Picture </p>
<form id = "changepropic" enctype="multipart/form-data" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
<label for="new_picture"><p class = "ti">New Profile Picture:</p></label>
<input type="file" id="new_picture" name="new_picture" >
<input type = "submit" value = "Next" name = "submit">
</form>
<a href = "index.php">Go to Home </a>
</div>
<?php 
}
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['submit'])) {
	$user = new User($_SESSION['user_id']);
	$user = $user->changeProfilePic();
}
?>
</body>
</html>