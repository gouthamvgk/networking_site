<?php
require_once("app.php");
require_once("db.php");
require_once("class2.php");
session_start();
$db = mysqli_connect(db_host, db_username, db_pass, database) or die("error connecting");
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && !isset($_POST['submit'])) {
$email = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user_data WHERE user_id = $user_id AND email_id = '$email'";
$result = mysqli_query($db, $query) or die("error querying");	
if (mysqli_num_rows($result) == 1) {
	$row = mysqli_fetch_array($result);
}
?>
<html>
<head>
<link href = "sign-up.css" rel = "stylesheet" type = "text/css">
<title> Change profile </title>
</head>
<body>
<div class = "sign-up">
<p> Change profile </p>
<form id = "sign-up"  action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
<input type = "hidden" name = "MAX_FILE_SIZE" value = "<?php echo MM_MAXFILESIZE; ?>" />
<input type = "text" name = "firstname" value = "<?php echo $row['first_name']; ?>" required>
<input type = "text" name = "surname" value = "<?php echo $row['last_name']; ?>" required>
<p><br></p>
<input type = "number" name = "mobile" value = "<?php echo $row['mobile_no']; ?>" required><br> <br>
<input type = "email" name = "email" value = "<?php echo $row['email_id']; ?>" disabled><br><br>
<input type = "password" name = "password-1" placeholder = "New password" required><br> <br>
<input type = "password" name = "password-2" placeholder = "Confirm new password" required>
<label for = "birthday"> <p class = "ti"> Birthday </p> </label>
<input type = "date" name = "birthday" value = "<?php echo $row['birthday']; ?>" required>
<input type = "radio" name = "gender" id = "male"  value = "male" <?php if($row['gender'] == "male") echo "checked" ?> disabled> Male
<input type = "radio" name = "gender" id = "female" value = "female" <?php if($row['gender'] == "female") echo "checked" ?> disabled> Female <br><br>
<input type = "submit" value = "Update profile" name = "submit"><br><br>
<a href='index.php'> Go to Home </a><br> <br>
<a href='changepropic.php'> Change Profile picture </a> 

</div>
<?php 
}
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['submit'])) {
	$user = new User($_SESSION['user_id']);
	$user->changeProfile();
}
?>
</body>
</html>
