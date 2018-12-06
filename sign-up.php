<?php
require_once("app.php");
?>
<html>
<head>
<link href = "sign-up.css" rel = "stylesheet" type = "text/css">
<title> Sign up </title>
</head>
<body>
<div class = "sign-up">
<p> Create an account </p>
<form id = "sign-up" enctype="multipart/form-data" action = "load.php" method = "post">
<input type = "hidden" name = "MAX_FILE_SIZE" value = "<?php echo MM_MAXFILESIZE; ?>" />
<input type = "text" name = "firstname" placeholder = "First name" required>
<input type = "text" name = "surname" placeholder = "Last name" required>
<p><br></p>
<input type = "number" name = "mobile" placeholder = "Mobile no." required><br> <br>
<input type = "email" name = "email" placeholder = "Email id" id = 'emailav' required><br><br>
<button type = "button" onclick = "checkemail()" > Check Email Availability </button> <span id = 'avai'> </span><br><br>
<input type = "password" name = "password-1" placeholder = "New password" required><br> <br>
<input type = "password" name = "password-2" placeholder = "Confirm password" required>
<label for = "birthday"> <p class = "ti"> Birthday </p> </label>
<input type = "date" name = "birthday" required>
<input type = "radio" name = "gender" id = "male"  value = "male" required> Male
<input type = "radio" name = "gender" id = "female" value = "female" required> Female <br><br>
<label for="new_picture"><p class = "ti">Profile Picture:</p></label>
<input type="file" id="new_picture" name="new_picture" >
<input type = "submit" value = "Next" name = "submit">

</div>
<script>
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
