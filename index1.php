<html>
<head> 
<title> Your profile </title>
</head>
<style>
#myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content, #caption {    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-content {
        width: 100%;
    }
}
</style>
<body>
<a href = 'change-profile.php'> Change profile </a><br> <br>
<a href = 'view-profile.php'> View my profile </a><br> <br>
<a href = 'notifications.php'> Friend Requests </a> <br> <br>
<a href = 'view-post.php'> My posts </a> <br> <br>
<a href = 'album.php'> My Photos </a> <br> <br>
<a href = 'add-image.php'> Add photos </a> <br> <br>
<a href = 'sign-out.php'>Sign out </a> <br><br>
<?php
require_once("db.php");
require_once("class2.php");
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
	$user = new User($_SESSION['user_id']);
	$user->home();
}
else {
  echo "database not set";
}
?>
<div style = "border-style: groove;width:35%;padding:15px;">
<form name = "post" action = "addpost.php" enctype="multipart/form-data" method = "post">
<label for =  "content"> Add your thoughts here </label><br>
<textarea name = "content" maxlength = '1000' rows = '5' cols='60' placeholder = "Post something here" required style="width:80%;">
</textarea><br><br>
<label for = "visibility"> Visible to </label>
<select name = "visibility"> 
<option value = 0 > Everyone </option>
<option value = 1> Only to friends </option>
</select><br><br>
<label for = "post_picture"> Add a picture: </label><br><br>
<input type="file" id="post_picture" name="post_picture" ><br><br>
<input type = "submit" value = "Add post" name = "submit">
</form>
</div>
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script id = 'erase'>
	function showHint(str) {
		var xhttp;
		if (str.length == 0) {
			document.getElementById("search2").innerHTML = "No suggestions";
			return;
		}
		xhttp = new XMLHttpRequest();
		xhttp.open("POST", "gethint.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("search2").innerHTML = this.responseText;
			}
		};
		var q = "q=" + str;
		xhttp.send(q);
	}
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
    modal.style.display = "none";
}
document.getElementById('erase').innerHTML = '';
</script>
</body>
</html>