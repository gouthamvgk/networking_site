<html>
<head> <title> Add post </title>
</head>
<body>
<a href = 'index.php'> Go to home </a><br> <br>
<a href = 'view-post.php'> View my posts</a> <br> <br>
<a href = 'sign-out.php'> Sign out </a>
<?php
require_once('db.php');
require_once('class2.php');
require_once('app.php');
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_POST['submit'])) {
	$user = new User($_SESSION['user_id']);
	$s = $user->addpost();
	if (empty($s)) {
		echo "<p> Your post was upload successfully </p> <br> <br>";
	}
	elseif (!empty($s)) {
		echo "$s <br> <br>" ;
		echo "Your post is not uploaded <br> <br>";
	}
	
}
else {
	echo "You are not allowed to view this page";
}
?>
</body>
</html>