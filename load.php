<?php
ini_set('display_errors', 'off');
ini_set('log_errors', 'on');
require_once("app.php");
require_once("db.php");
if(isset($_POST['submit'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['surname'];
  $mobile = $_POST['mobile'];
  $email = $_POST['email'];
  $password1 = $_POST['password-1'];
  $password2 = $_POST['password-2'];
  $birthday = $_POST['birthday'];
  $gender = $_POST['gender'];
  $random = rand(45555, 99999);
  $er = 'false';
  $target = "";
  $db = mysqli_connect(db_host, db_username, db_pass, database) or die("error connecting");
  $new_picture = mysqli_real_escape_string($db, trim($_FILES['new_picture']['name']));
  $new_picture_type = $_FILES['new_picture']['type'];
  $new_picture_size = $_FILES['new_picture']['size'];
  list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
  $query1 = "SELECT user_id FROM user_data WHERE email_id = '$email'" ;
  $result1 = mysqli_query($db, $query1) or die("error querying1");
  if (!empty($new_picture)) {
	if ((($new_picture_type == 'image/jpg') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) && ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
		$base = "$random".basename($new_picture);
		$target = MM_UPLOADPATH ."$base";
		if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
			echo "upload success";
		}
		else {
			@unlink($_FILES['new_picture']['tmp_name']);
			$er = 'true';
			$ermsg = "Sorry, there was a problem uploading your picture.";
		}
	}
	else {
		@unlink($_FILES['new_picture']['tmp_name']);
		$er = 'true';
		$ermsg = "Your picture must be a GIF, JPEG, or PNG image file not greater than " . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . " pixels in size";
	}
  }
  if (strlen("$mobile")> 12) {
    $err1 = "Mobile number should be less than 12 digits";
  }
  elseif ($password1 != $password2) {
    $err2 = "Both the passwords must match";
  }
  elseif (mysqli_fetch_array($result1)) {
	  $err3 = "Email id already exists";
  }
  elseif ('true' == $er) {
	  $err4 = $ermsg;
  }
  else {
	  if (!empty($new_picture) && ('false' == $er)) {
		  require_once("email.php");
		  $query2 = "INSERT INTO user_data VALUES (0, '$firstname', '$lastname', $mobile, '$email', sha('$password1'), '$birthday', '$gender', now(), $random, 'no', '$target')";
	      $result2 = mysqli_query($db, $query2) or die("error querying2");
	  }
	  else {
		  require_once("email.php");
		  $query3 = "INSERT INTO user_data VALUES (0, '$firstname', '$lastname', $mobile, '$email', sha('$password1'), '$birthday', '$gender', now(), $random, 'no', NULL)";
	      $result3 = mysqli_query($db, $query3) or die("error querying3");
	  }
	  
  }
}
if (isset($err1) || isset($err2) || isset($err3) || isset($err4)) {
?>
<html>
<head>
<link href = "sign-up.css" rel = "stylesheet" type = "text/css">
<title> Sign up </title>
</head>
<body>
<div class = "sign-up">
<p> Create an account </p>
<form id = "sign-up" enctype="multipart/form-data" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
<input type = "text" name = "firstname" placeholder = "First name" value="<?php if (isset($firstname)) echo "$firstname"; ?>"required>
<input type = "text" name = "surname" placeholder = "Last name" value="<?php if (isset($lastname)) echo "$lastname"; ?>" required>
<p><br></p>
<input type = "number" name = "mobile" placeholder = "Mobile no." value="<?php if (isset($mobile)) echo "$mobile"; ?>" required><br> <br>
<span class = "error"> <?php if(isset($err1)) echo "<input type = 'hidden' id = 'err1' value='".$err1."'>"; ?> </span>
<input type = "email" name = "email" placeholder = "Email id" value = "<?php if (isset($email)) echo "$email"; ?>" required id = "emailav"><br><br>
<button type = "button" onclick = "checkemail()" > Check Email Availability </button> <span id = 'avai'> </span><br><br>
<span class = "error"><?php if (isset($err3)) echo "$err3"; ?> </span>
<input type = "password" name = "password-1" placeholder = "New password" required><br> <br>
<input type = "password" name = "password-2" placeholder = "Confirm password" required>
<span class = "error"><?php if (isset($err2)) echo "<input type = 'hidden' id = 'err2' value='".$err2."'>"; ?> </span>
<label for = "birthday"> <p class = "ti"> Birthday </p> </label>
<input type = "date" name = "birthday" value ="<?php if (isset($birthday)) echo "$birthday"; ?>" required>
<input type = "radio" name = "gender" id = "male"  value = "male" <?php if (isset($gender) && $gender=="male") echo "checked"; ?> required> Male
<input type = "radio" name = "gender" id = "female" value = "female" <?php if (isset($gender) && $gender=="female") echo "checked"; ?> required> Female<br><br>
<label for="new_picture"><p class = "ti">Profile Picture:</p></label>
<input type="file" id="new_picture" name="new_picture" >
<span style = "color:blue;"><?php if (isset($err4)) echo "$err4"; ?> </span>
<input type = "submit" value = "Next" name = "submit">
</div>

<?php
  }
?>
<script>
try {
    var x = document.getElementById("err2").value;
	alert(x);
}
finally {
	var y = document.getElementById("err1").value;
	alert(y);
}
function checkemail() {
	var s = document.getElementById("emailav").value;
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "checkemail.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function () {
	if (this.readyState == 4 && this.status == 200) {
			document.getElementById('avai').innerHTML =  this.responseText;
		}
	};
	var r = "s=" + s;
	xhttp.send(r);
}

 </script>
</body>
</html>
