<html>
<head> 

<title> Add photos </title>
</head>
<body>
<a href = 'index.php'> Home </a><br><br>
<a href = 'sign-out.php'> Sign out </a><br><br>
<?php
require_once('db.php');
require_once('app.php');
require_once('class2.php');
session_start();
if (isset($_SESSION['username']) && $_SESSION['user_id'] && !isset($_POST['submit'])) {
?>

<h1> ADD PHOTOS </h1>
<form id = "addphoto" enctype = "multipart/form-data" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
<label for = "album"><p>Album Name:</p></label>
<input type = "text" name = "album_name" maxlength = "30" required><br>
<label for = "picture"><p>Select photos:</p></label>
<input type = "file" id = "pictures" name = "pictures[]" multiple><br><br>
<label for = "visibility"> Visible to </label>
<select name = "visibility"> 
<option value = 0 > Everyone </option>
<option value = 1> Only to friends </option>
</select><br><br>
<input type = "submit" value = "Upload" name = "submit">
</form>

<?php
}
elseif($_SESSION['username'] && $_SESSION['user_id'] && isset($_POST['submit'])) {
	if (!empty($_POST['album_name']) && !empty($_FILES['pictures']['name'][0])) {
		$count = count($_FILES['pictures']['name']);
		$visibility = $_POST['visibility'];
		$name = $_POST['album_name'];
		$user = new User($_SESSION['user_id']);
		$s = $user->addPhotos($count, $name, $visibility);
		echo "Album uploaded Successfully<br><br>";
		$r = 0;
		for ($i = 0; $i < $count ; $i++) {
			if (!empty($s[$i])) {
				$r = $r + 1;
			}
		}  
		if ($r != 0) {
		echo "{$r} out of {$count} images not uploaded due to error<br><br>";
		}
	}
	else {
		echo "You have not selected any images or not entered an album name";
	}
}
?>
</body>
</html>