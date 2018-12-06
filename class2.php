<?php
require_once("app.php");
require_once("db.php");
class User {
	public $db;
	public $email;
	public $firstname;
	public $lastname;
	public $user_id;
	public $mobile;
	public $birthday;
	private $password;
	public $gender;
	public $pro_pic;
	public function __construct($user_id1) {             
		$this->user_id = $user_id1;
		$this->db = mysqli_connect(db_host, db_username, db_pass, database);
		$query = "SELECT * FROM user_data WHERE user_id = '$user_id1'";
		$result = mysqli_query($this->db, $query) or die("error querying");
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$this->email = $row['email_id'];
			$this->firstname = $row['first_name'];
			$this->lastname = $row['last_name'];
			$this->user_id = $row['user_id'];
			$this->birthday = $row['birthday'];
			$this->password = $row['password1'];
			$this->gender = $row['gender'];
			$this->pro_pic = $row['pro_pic'];
			$this->mobile = $row['mobile_no'];
		}
	}
	public function home() {
		echo <<<END
		<img src = '{$this->pro_pic}' alt = 'profile picture' style = 'width:104px;height:142px;' id="myImg"><br><br>
		<h2> Welcome {$this->firstname} </h2>
		<div><label for = 'input'> Search for users </label><input type = 'text' name = 'search' id = 'search1' onkeydown = 'showHint(this.value)'> </input>
		<p id = 'search2'> </p></div>
END;
	}
	private function findUser($q) {
		$query = "SELECT * FROM user_data WHERE user_id = $q";
		$result = mysqli_query($this->db, $query) or die("error querying2");
		$row = mysqli_fetch_array($result);
		return $row;
	}
	public function notifications() {
		$query = "SELECT * FROM relationship WHERE user_one_id = $this->user_id OR user_two_id = $this->user_id";
		$result = mysqli_query($this->db, $query) or die("error querying");
		while ($row = mysqli_fetch_array($result)) {
			if ($row['action_user_id'] != $this->user_id && $row['status'] == 1) {
				$user = $this->findUser($row['action_user_id']);
			    echo <<<END
				<img src = '{$user['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>
			    <a href = 'view-profile.php?q={$user['user_id']}'>{$user['first_name']}</a> <span id = {$user['user_id']}> sent you a friend request 
				<button type = 'button' value = {$user['user_id']} id = 'add' name ='a' onclick = "aa1(this.value)"> Add as Friend </button>
				<button type = 'button' value = {$user['user_id']} id = 'delete' name ='r' onclick = "dd1(this.value)"> Delete Friend Request </button>
				</span><br>
END;
			}
		}
	}
	public function changeStatus($s, $q) {
		if ($this->user_id > $q) {
			$user_one = $this->user_id;
			$user_two = $q;
		}
		else {
			$user_one = $q;
			$user_two = $this->user_id;
	    }
		$query = "UPDATE relationship SET status = $s, action_user_id = $this->user_id, action_time = now() WHERE user_one_id = $user_one AND user_two_id = $user_two";
		$result = mysqli_query($this->db, $query) or die("error changing status");
	}
	private function findStatus($q) {
		if ($this->user_id > $q) {
			$user_one = $this->user_id;
			$user_two = $q;
		}
		else {
			$user_one = $q;
			$user_two = $this->user_id;
	    }
		$query = "SELECT * FROM relationship WHERE user_one_id = $user_one AND user_two_id = $user_two";
		$result = mysqli_query($this->db, $query) or die("error finding status");
		$row = mysqli_fetch_array($result);
		return $row;
	}
	private function findalbum($q) {
		$query = "SELECT DISTINCT album_no FROM images WHERE user_id = $q";
		$result = mysqli_query($this->db, $query) or die('error finding album');
		$r = array();
		while ($row = mysqli_fetch_array($result)) {
			array_push($r, $row['album_no']);
		}
		return $r;
	}
	private function findal($u, $p) {
		$query = "SELECT * FROM images WHERE user_id = $u AND album_no = $p";
		$result = mysqli_query($this->db, $query) or die('erro in al');
		$row = mysqli_fetch_array($result);
		return $row;
	}
	public function changeProfile() {
		$email = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
	    $firstname = $_POST['firstname'];
        $lastname = $_POST['surname'];
        $mobile = $_POST['mobile'];
        $password1 = $_POST['password-1'];
        $password2 = $_POST['password-2'];
        $birthday = $_POST['birthday'];
		if (!empty($firstname) && !empty($lastname) && strlen("$mobile") < 12 && !empty($password1) && $password1 == $password2) {
		        $query = "UPDATE user_data SET first_name = '$firstname', last_name = '$lastname', mobile_no = $mobile, password1 = sha('$password1'), birthday = '$birthday' WHERE user_id = '$user_id' AND email_id = '$email'";
		        if (mysqli_query($this->db, $query)) {
			          echo "Profile updated<br> <br>";
			          echo "<a href='index.php'> Go to Profile</a>";
		        }
		        else {
			        echo "Error updating<br><br>";
					echo "<a href='index.php'> Go to home </a><br><br>";
					echo "<a href='change-profile.php'> Try again </a><br><br>";
		        }
	    }
	    else {   
		   echo "You have entered invalid credentials<br> <br>";
		   echo "<a href='index.php'> Go to Profile</a><br> <br>";
		   echo "<a href='change-profile.php'> Try again </a><br><br>";
	   }
	}
	public function changeProfilePic() {
		$random = rand(45555, 99999);
		$target = "";
		$new_picture = mysqli_real_escape_string($this->db, trim($_FILES['new_picture']['name']));
		$new_picture_type = $_FILES['new_picture']['type'];
		$new_picture_size = $_FILES['new_picture']['size'];
		list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
		if (!empty($new_picture)) {
	          if ((($new_picture_type == 'image/jpg') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) && ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
		          $base = "$random".basename($new_picture);
		          $target = MM_UPLOADPATH ."$base";
		          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
			          echo "Profile picture changed successfully<br>";
					  echo "<a href='index.php'> Go to Home </a><br>";
					  $query = "UPDATE user_data SET pro_pic = '$target' WHERE user_id = $this->user_id";
					  $result = mysqli_query($this->db, $query) or die ("error updating profile pic");
					  unlink("{$this->pro_pic}");
		          }
		          else {
			           @unlink($_FILES['new_picture']['tmp_name']);
			           echo "Sorry, there was a problem Uploading your picture.<br><br>";
					   echo "<a href='index.php'> Go to Home </a><br><br>";
					   echo "<a href='changepropic.php'> Try again </a><br>";
		          }
	          }
	          else {
		           @unlink($_FILES['new_picture']['tmp_name']);
		           echo "Your picture must be a GIF, JPEG, or PNG image file not greater than " . (MM_MAXFILESIZE / 1024) ." KB and " . MM_MAXIMGWIDTH . "x" . MM_MAXIMGHEIGHT . " pixels in size<br><br>";
                   echo "<a href='index.php'> Go to Home </a><br><br>";    
				   echo "<a href='changepropic.php'> Try again </a><br>";
	          }
        }
		else {
			echo "You've not selected any Picture<br><br>";
			echo "<a href='index.php'> Go to Home </a><br><br>"; 
			echo "<a href='changepropic.php'> Try again </a><br>";
		}
	}
	public function viewownprofile() {
			echo <<<END
			<a href = 'index.php'> Home </a><br><br>
            <a href = 'friendslist.php'> Friends List </a><br> <br>
            <a href = 'view-post.php'> My posts</a><br><br>
			<a href = 'album.php'> My photos </a> <br><br>
            <a href = 'sign-out.php'> Sign out </a><br><br>
            <h2> Welcome {$this->firstname} </h2>
            <p> First name: {$this->firstname} </p> <br><br>
            <p> Last name: {$this->lastname} </p> <br><br>
            <p> Mobile.no: {$this->mobile} </p> <br><br>
            <p> Email-id: {$this->email} </p> <br><br>
            <p> Birthday: {$this->birthday} </p> <br><br>
            <p> Gender : {$this->gender} </p> <br><br>
END;
			if(!empty($this->pro_pic)) {
				echo "<img src = '". $this->pro_pic. "' alt = 'profile picture' style = 'width:104px;height:142px;'> ";
			}
	   
	}
	public function viewotherprofile($q) {
		if ($this->user_id > $q) {
			$user_one = $this->user_id;
			$user_two = $q;
		}
		else {
			$user_one = $q;
			$user_two = $this->user_id;
	    }
		$query2 = "SELECT * FROM relationship WHERE user_one_id = $user_one AND user_two_id = $user_two";
		$result1 = mysqli_query($this->db, $query2) or die("error querrying2");
		if (mysqli_num_rows($result1) == 0) {
		    $status = 0;
			$query3 = "INSERT INTO relationship VALUES ($user_one, $user_two, $status, $this->user_id, now())";
			$result3 = mysqli_query($this->db, $query3) or die("error querying3");
	    }
		else {
			$row = mysqli_fetch_array($result1);
			$status = $row['status'];
			if ($status == 1 && $row['action_user_id'] == $this->user_id) {
				$you = "sent on {$row['action_time']}";
			}
			elseif($status == 1 && $row['action_user_id'] != $this->user_id) {
				$you = "received on {$row['action_time']}";
			}
	    }
		$row1 = $this->findUser($q);
		switch ($status) {
			case 0:
			     echo <<<END
				<a href = 'index.php'> Home </a><br><br>
                <a href = 'sign-out.php'> Sign out </a><br><br>
				<a href = 'view-post.php?q={$q}'> View posts </a><br><br>
				<a href = 'album.php?q={$q}'> View photos </a> <br><br>
				<form id = 'a' action = 'friendreq.php' method = 'post'>
				<input type = 'hidden' name = 'user' value = '{$q}' >
				<input type = 'submit' value = 'Add as friend' name = 'submit'>
				</form>
				<form id = 'a' action = 'friendreq.php' method = 'post'>
				<input type = 'hidden' name = 'user' value = '{$q}' >
				<input type = 'submit' value = 'Block User' name = 'submit1'>
				</form>
		        <p> First name: {$row1['first_name']} </p> <br> <br>
				<p> Last name: {$row1['last_name']} </p> <br> <br>
				<p> Birthday: {$row1['birthday']} </p> <br> <br>
				<p> Gender: {$row1['gender']} </p> <br> <br>
END;
                break;
			case 1:
			    if ($row['action_user_id'] != $this->user_id) {
					echo <<<END
					<a href = 'index.php'> Home </a><br><br>
                    <a href = 'sign-out.php'> Sign out </a><br><br>
					<a href = 'view-post.php?q={$q}'> View posts </a><br><br>
					<a href = 'album.php?q={$q}'> View photos </a> <br><br>
				    <p> Friend request received on {$row['action_time']}</p>
					<form id = 'a' action = 'friendreq.php' method = 'post'>
				    <input type = 'hidden' name = 'user' value = '{$q}' ><br>
				    <input type = 'submit' value = 'Accept Friend Request' name = 'submit3'>
				    </form>
					<form id = 'a' action = 'friendreq.php' method = 'post'>
				    <input type = 'hidden' name = 'user' value = '{$q}' ><br>
				    <input type = 'submit' value = 'Delete Friend Request' name = 'submit2'>
				    </form>
				    <form id = 'a' action = 'friendreq.php' method = 'post'>
				    <input type = 'hidden' name = 'user' value = '{$q}' ><br>
				    <input type = 'submit' value = 'Block User' name = 'submit1'>
				    </form>
		            <p> First name: {$row1['first_name']} </p> <br> <br>
				    <p> Last name: {$row1['last_name']} </p> <br> <br>
				    <p> Birthday: {$row1['birthday']} </p> <br> <br>
				    <p> Gender: {$row1['gender']} </p> <br> <br>
END;
				}
				elseif ($row['action_user_id'] == $this->user_id) {
					echo <<<END
					<a href = 'index.php'> Home </a><br><br>
                    <a href = 'sign-out.php'> Sign out </a><br><br>
					<a href = 'view-post.php?q={$q}'> View posts </a><br><br>
					<a href = 'album.php?q={$q}'> View photos </a> <br><br>
				    <p> Friend request sent on {$row['action_time']}</p>
					<form id = 'a' action = 'friendreq.php' method = 'post'>
				    <input type = 'hidden' name = 'user' value = '{$q}' >
				    <input type = 'submit' value = 'Delete Request' name = 'submit2'>
				    <form id = 'a' action = 'friendreq.php' method = 'post'>
				    <input type = 'hidden' name = 'user' value = '{$q}' >
				    <input type = 'submit' value = 'Block User' name = 'submit1'>
				    </form>
		            <p> First name: {$row1['first_name']} </p> <br> <br>
				    <p> Last name: {$row1['last_name']} </p> <br> <br>
				    <p> Birthday: {$row1['birthday']} </p> <br> <br>
				    <p> Gender: {$row1['gender']} </p> <br> <br>
END;
				}
                break;
			case 2:
			    echo <<<END
				<a href = 'index.php'> Home </a><br><br>
                <a href = 'sign-out.php'> Sign out </a><br><br>
				<a href = 'view-post.php?q={$q}'> View posts </a><br><br>
				<a href = 'album.php?q={$q}'> View photos </a> <br><br>
				<p> Friends from {$row['action_time']} </p>
				<form id = 'a' action = 'friendreq.php' method = 'post'>
				<input type = 'hidden' name = 'user' value = '{$q}' >
				<input type = 'submit' value = 'Unfriend' name = 'submit2'>
		        <p> First name: {$row1['first_name']} </p> <br> <br>
				<p> Last name: {$row1['last_name']} </p> <br> <br>
				<p> Mobile.no: {$row1['mobile_no']} </p> <br><br>
				<p> Email-id: {$row1['email_id']} </p> <br><br>
				<p> Birthday: {$row1['birthday']} </p> <br> <br>
				<p> Gender: {$row1['gender']} </p> <br> <br>
END;
                if (!empty($row1['pro_pic'])) {
					echo "<img src = '". $row1['pro_pic']. "' alt = 'profile picture' style = 'width:104px;height:142px;'> ";
				}
				break;
			case 3:
			     if ($row['action_user_id'] != $this->user_id) {
					 echo <<<END
				     <p> This user has blocked you. </p>
END;
				 }
				 else {
					 echo <<<END
				     <a href = 'sign-out.php'> Log out </a><br><br>
				     <a href = 'index.php'> Home </a>
				     <p> You have blocked this User. </p>
					 <form id = 'a' action = 'friendreq.php' method = 'post'>
				     <input type = 'hidden' name = 'user' value = '{$q}' >
				     <input type = 'submit' value = 'Unblock User' name = 'submit2'>
				     </form>
END;
				 }
                break;
		}
	}
	public function friendreq($q, $s) {
		$this->changeStatus($s, $q);
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/view-profile.php?q='.$q;
		header('Location: ' . $url);
	}
	public function viewfriends() {
		$query = "SELECT * FROM relationship WHERE (user_one_id = $this->user_id OR user_two_id = $this->user_id) AND status = 2";
		$result = mysqli_query($this->db, $query) or die ("error finding friends");
		while ($row = mysqli_fetch_array($result)) {
			if ($row['user_one_id'] == $this->user_id) {
				$user = $row['user_two_id'];
			}
			else {
				$user = $row['user_one_id'];
			}
			$info = $this->findUser($user);
			echo <<<END
			<img src = '{$info['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>
			<a href = 'view-profile.php?q={$info['user_id']}'>{$info['first_name']}</a> <br><br>
END;
		}
	}
	public function addpost() {
		$content = $_POST['content'];
		$visibility = $_POST['visibility'];
		$post_picture = mysqli_real_escape_string($this->db, trim($_FILES['post_picture']['name']));
		$target = "";
		$er = "";
		if (!empty($post_picture)) {
			$random = rand(45555, 99999);
		    $post_picture_type = $_FILES['post_picture']['type'];
		    $post_picture_size = $_FILES['post_picture']['size'];
		    list($post_picture_width, $post_picture_height) = getimagesize($_FILES['post_picture']['tmp_name']);
			if ((($post_picture_type == 'image/jpg') || ($post_picture_type == 'image/jpeg') || ($post_picture_type == 'image/png')) && ($post_picture_size > 0) && ($post_picture_size <= MM_MAXFILESIZE) && ($post_picture_width <= MM_MAXIMGWIDTH) && ($post_picture_height <= MM_MAXIMGHEIGHT)) {
				$base = "$random".basename($post_picture);
		        $target = MM_UPLOADPATHPOST ."$base";
				if (move_uploaded_file($_FILES['post_picture']['tmp_name'], $target)) {
				}
				else {
					@unlink($_FILES['post_picture']['tmp_name']);
					$er = "There is an error in uploading post picture";
				}
			}
			else {
				@unlink($_FILES['post_picture']['tmp_name']);
				$er = "Your picture must be a GIF, JPEG, or PNG image file not greater than " . (MM_MAXFILESIZE / 1024) ." KB and " . MM_MAXIMGWIDTH . "x" . MM_MAXIMGHEIGHT . " pixels in size<br><br>";
			}
		}
		if (empty($er)) {
		$query = "SELECT MAX(post_no) FROM post WHERE user_id = $this->user_id";
		$result = mysqli_query($this->db, $query) or die ('error accessing the post <br> <a href = "index.php"> Go to home </a>'); 
		if ($row = mysqli_fetch_array($result)) {
			$post_no = $row['MAX(post_no)'] + 1;
		}
		else {
			$post_no = 1;
		}
		$like = array();
		$count = count($like);
		$r = serialize($like);
		$query3 = "INSERT INTO post_likes VALUES ($this->user_id, $post_no, $count , '$r')";
		$query4 = "INSERT INTO post_comments VALUES ($this->user_id, $post_no, $count, '$r')";
		$query2 = "INSERT INTO post VALUES($this->user_id, $post_no, $visibility, '$content', '$target', now())";
		$result1 = mysqli_query($this->db, $query2) or die('error uploading the post <br> <a href = "index.php"> Go to home </a>');
		$result3 = mysqli_query($this->db, $query3) or die('error uploading the likes2 <br> <a href = "index.php"> Go to home </a>');
		$result4 = mysqli_query($this->db, $query4) or die('error uploading the likes 3<br> <a href = "index.php"> Go to home </a>');
		}
		return $er;
	}
	public function viewpost($q) {
		if ($q == $this->user_id) {
			$user = $this->user_id;
			$query = "SELECT * FROM post WHERE user_id = $this->user_id ORDER BY post_no DESC";
			$result = mysqli_query($this->db, $query) or die("error viewing");
			while ($row = mysqli_fetch_array($result)) {
				$query3 = "SELECT * FROM post_likes WHERE user_id = $user AND post_no = {$row['post_no']}";
				$query4 = "SELECT * FROM post_comments WHERE user_id = $user AND post_no = {$row['post_no']}";
				$result3 = mysqli_query($this->db, $query3) or die("error viewing3");
				$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				$row3 = mysqli_fetch_array($result3);
				$row4 = mysqli_fetch_array($result4);
				$d = unserialize($row3['likes']);
				if (in_array($this->user_id, $d)) {
					$r = "Unlike";
				}
				else {
					$r = "Like";
				}
				echo "<div id = {$row['post_no']} style='width:400px;'> Post no.: {$row['post_no']}<br>";
				if (!empty($row['image'])) {
					echo <<<END
					<br>
					<img src = "{$row['image']}" width = '150' height = '150' id = {$row['post_no']}><br><br>
END;
				}
				echo <<<END
				<p id = {$row['post_no']}>{$row['content']}
                </p>
			    <span id = "count{$row['post_no']}"> {$row3['like_count']}</span><a href='view-likes.php?q={$user}&a={$row['post_no']}' id = {$row['post_no']}> people liked this </a><br><br>
				<button type = "button" id = "like{$row['post_no']}" value = {$row['post_no']} onclick = "like(this.value, {$user})">{$r}</button><br><br>
				<span id = "comment{$row['post_no']}">{$row4['count']}</span><a href = 'view-comments.php?q={$user}&a={$row['post_no']}'> comments </a><br><br>
				<input type = "text" id = "co{$row['post_no']}" placeholder = "Comment here..."/> 
				<button type = "button" value = "{$row['post_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
				<button type = "button" name = "delete" value = {$row['post_no']} onclick = "deletePost(this.value)"> Delete Post </button>
				<form id = "editpost" method = "post" action = "editpost.php" style = "display:inline;">
				<input type = "hidden" name = "post_no" value = {$row['post_no']}>
				<input type = "submit" name = "submit" value = "Edit Post">
				</form>
				<br> <br>
				<hr></div>
END;
			}
		}
		else {
            $row = $this->findStatus($q);
			$s = $row['status'];
			$user = $q;
			$query2 = "SELECT * FROM POST WHERE user_id = $q";
			$result2 = mysqli_query($this->db, $query2) or die("error querying 12");
			switch($s) {
				case 0:
				case 1:
				     while ($row2 = mysqli_fetch_array($result2)) {
						 if ($row2['visibility'] == 0) {
							    $query3 = "SELECT * FROM post_likes WHERE user_id = $user AND post_no = {$row2['post_no']}";
								$query4 = "SELECT * FROM post_comments WHERE user_id = $user AND post_no = {$row2['post_no']}";
				                $result3 = mysqli_query($this->db, $query3) or die("error viewing3");
								$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				                $row3 = mysqli_fetch_array($result3);
								$row4 = mysqli_fetch_array($result4);
								$d = unserialize($row3['likes']);
				                if (in_array($this->user_id, $d)) {
					                    $r = "Unlike";
				                }
				                else {
					                    $r = "Like";
				                }
							     if (!empty($row2['image'])) {
					                    echo <<<END
										<br>
					                    <img src = "{$row2['image']}" width = '150' height = '150' id = {$row2['post_no']}>
END;
				                }
								echo <<<END
				                <p id = {$row2['post_no']} style="width:400px;"> Post no.: {$row2['post_no']}<br>{$row2['content']} </p><br> 
								<span id = "count{$row2['post_no']}"> {$row3['like_count']}</span><span id = {$row2['post_no']}> people liked this </span><br><br>
				                <button type = "button" id = "like{$row2['post_no']}" value = {$row2['post_no']} onclick = "like(this.value, {$user})">{$r}</button><br><br>
								<span id = "comment{$row2['post_no']}">{$row4['count']}</span><p> comments </a><br><br>
								<input type = "text" id = "co{$row2['post_no']}" placeholder = "Comment here..."/>
								<button type = "button" value = "{$row2['post_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
								<hr>
END;
						 }
					}
					break;
				case 2:
				    while ($row2 = mysqli_fetch_array($result2)) {
						$query3 = "SELECT * FROM post_likes WHERE user_id = $user AND post_no = {$row2['post_no']}";
						$query4 = "SELECT * FROM post_comments WHERE user_id = $user AND post_no = {$row2['post_no']}";
				        $result3 = mysqli_query($this->db, $query3) or die("error viewing3");
						$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				        $row3 = mysqli_fetch_array($result3);
						$row4 = mysqli_fetch_array($result4);
					    $d = unserialize($row3['likes']);
				        if (in_array($this->user_id, $d)) {
					        $r = "Unlike";
				        }
				        else {
					         $r = "Like";
				        }
						if (!empty($row2['image'])) {
					                echo <<<END
					                <img src = "{$row2['image']}" width = '150' height = '150' id = {$row2['post_no']}>
END;
				        }
						echo <<<END
				        <p id = {$row2['post_no']} style="width:400px;"> Post no.: {$row2['post_no']}<br>{$row2['content']} </p>
						<span id = "count{$row2['post_no']}"> {$row3['like_count']}</span><a href='view-likes.php?q={$user}&a={$row2['post_no']}' id = {$row2['post_no']}> people liked this </a><br><br>
				        <button type = "button" id = "like{$row2['post_no']}" value = {$row2['post_no']} onclick = "like(this.value, {$user})">{$r}</button><br>
						<span id = "comment{$row2['post_no']}">{$row4['count']}</span><a href = 'view-comments.php?q={$user}&a={$row2['post_no']}'> comments </a><br><br>
						<input type = "text" id = "co{$row2['post_no']}" placeholder = "Comment here..."/>
						<button type = "button" value = "{$row2['post_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
						<hr>
END;
					}
					break;
				case 3:
				    echo "<br>You cannot view this profile";
			}
		}
	}
	public function deletepost($q) {
		$query2 = "SELECT * FROM post WHERE user_id = $this->user_id AND post_no = $q";
		$query3 = "DELETE FROM post_likes WHERE user_id = $this->user_id AND post_no = $q";
		$result2 = mysqli_query($this->db, $query2) or die("error querying 11");
		$row = mysqli_fetch_array($result2);
		unlink("{$row['image']}");
		$query = "DELETE FROM post WHERE user_id = $this->user_id AND post_no = $q";
		$query4 = "DELETE FROM post_comments WHERE user_id = $this->user_id AND post_no = $q";
		$result = mysqli_query($this->db, $query) or die('error in querying');
		$result3 = mysqli_query($this->db, $query3) or die('error in querying3');
		$result4 = mysqli_query($this->db, $query4) or die('error in querying3');
	}
	public function editpost($q) {
		$post_picture = mysqli_real_escape_string($this->db, trim($_FILES['post_picture']['name']));
		if (empty($post_picture)) {
	        $query = "UPDATE post SET content = '{$_POST['content']}', visibility = {$_POST['visibility']}, time = now() WHERE user_id = $this->user_id AND post_no = $q";
		    $result = mysqli_query($this->db, $query) or die('error in accessing');
		    echo "The post has been updated successfully";
		}
		else {
			$target = "";
		    $er = "";
			$random = rand(45555, 99999);
		    $post_picture_type = $_FILES['post_picture']['type'];
		    $post_picture_size = $_FILES['post_picture']['size'];
		    list($post_picture_width, $post_picture_height) = getimagesize($_FILES['post_picture']['tmp_name']);
			if ((($post_picture_type == 'image/jpg') || ($post_picture_type == 'image/jpeg') || ($post_picture_type == 'image/png')) && ($post_picture_size > 0) && ($post_picture_size <= MM_MAXFILESIZE) && ($post_picture_width <= MM_MAXIMGWIDTH) && ($post_picture_height <= MM_MAXIMGHEIGHT)) {
				$base = "$random".basename($post_picture);
		        $target = MM_UPLOADPATHPOST ."$base";
				if (move_uploaded_file($_FILES['post_picture']['tmp_name'], $target)) {
				}
				else {
					@unlink($_FILES['post_picture']['tmp_name']);
					$er = "There is an error in uploading post picture";
				}
			}
			else {
				@unlink($_FILES['post_picture']['tmp_name']);
				$er = "Your picture must be a GIF, JPEG, or PNG image file not greater than " . (MM_MAXFILESIZE / 1024) ." KB and " . MM_MAXIMGWIDTH . "x" . MM_MAXIMGHEIGHT . " pixels in size<br><br>";
			}
			if (empty($er)) {
				$query = "UPDATE post SET content = '{$_POST['content']}',image = '$target', visibility = {$_POST['visibility']},  time = now() WHERE user_id = $this->user_id AND post_no = $q";
		        $result = mysqli_query($this->db, $query) or die('error in accessing');
		        echo "The post has been updated successfully";
			}
			else {
				echo "$er<br>";
				echo "The post wasn't changed";
			}
		}
	}
	public function removepicture($q) {
		$query1 = "SELECT image FROM post WHERE user_id = $this->user_id AND post_no = $q";
		$result1 = mysqli_query($this->db, $query1) or die('hi');
		$row = mysqli_fetch_array($result1);
		$i = $row['image'];
	    unlink("{$i}");
		$query = "UPDATE post SET image = '' WHERE user_id = $this->user_id AND post_no = $q";
		$result = mysqli_query($this->db, $query) or die("eror in accessing");
	}
	public function changeLike($user, $post_no) {
		$query = "SELECT * FROM post_likes WHERE user_id = $user AND post_no = $post_no";
		$result = mysqli_query($this->db, $query) or die('error in post');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['likes']);
		if (in_array($this->user_id, $s)) {
			$key = array_search($this->user_id, $s);
			unset($s[$key]);
			$n = count($s);
		}
		else {
			array_push($s, $this->user_id);
			$n = count($s);
		}
		$a = serialize($s);
		$query2 = "UPDATE post_likes SET like_count = $n, likes = '$a' WHERE user_id = $user AND post_no = $post_no";
		$result2 = mysqli_query($this->db, $query2) or die('error changing likes');
		return $n;
	}
	public function changeLikeimage($u, $a, $i) {
		$query = "SELECT * FROM image_likes WHERE user_id = $u AND album_no = $a AND image_no = $i";
		$result = mysqli_query($this->db, $query) or die('error in image');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['likes']);
		if (in_array($this->user_id, $s)) {
			$key = array_search($this->user_id, $s);
			unset($s[$key]);
			$n = count($s);
		}
		else {
			array_push($s, $this->user_id);
			$n = count($s);
		}
		$l = serialize($s);
		$query2 = "UPDATE image_likes SET like_count = $n, likes = '$l' WHERE user_id = $u AND album_no = $a AND image_no = $i";
		$result2 = mysqli_query($this->db, $query2) or die('error changing likes');
		return $n;
	}
	public function viewLikes($q, $a) {
		$query = "SELECT * FROM post_likes WHERE user_id = $q AND post_no = $a";
		$result = mysqli_query($this->db, $query) or die('error in query');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['likes']);
		if ($q == $this->user_id) {
			foreach ($s as $id) {
				$e = $this->findUser($id);
				if ($e['user_id'] == $this->user_id) {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "Liked by you <br><br>";
				}
				else {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><br><br>";
				}
			}
		}
		else {
			$status = $this->findStatus($q);
			if ($status['status'] == 2) {
				foreach ($s as $id) {
				    $e = $this->findUser($id);
				    if ($e['user_id'] == $this->user_id) {
					    echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					    echo "Liked by you <br><br>";
				    }
				    else {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><br><br>";
				   }
			    }  
			}
			else {
				echo "You are not friends with this user<br>";
			}
		}
	}
	public function viewComments($q, $a) {
		$query = "SELECT * FROM post_comments WHERE user_id = $q AND post_no = $a";
		$result = mysqli_query($this->db, $query) or die('error in query');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['comments']);
		if ($q == $this->user_id) {
			foreach ($s as $id => $k ) {
				$e = $this->findUser($id);
				if ($e['user_id'] == $this->user_id) {
					    foreach ($k as $j) {
							echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					        echo "You commented '{$j}' <br><br>";
						}
				}
				else {
					foreach ($k as $j) {
							echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					        echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']} </a><span>commented '{$j}'</span><br><br>";
						}
				}
			}
		}
		else {
			$status = $this->findStatus($q);
			if ($status['status'] == 2) {
				foreach ($s as $id => $k ) {
				     $e = $this->findUser($id);
				     if ($e['user_id'] == $this->user_id) {
					        foreach ($k as $j) {
							    echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					            echo "You commented '{$j}' <br><br>";
						    }
				    }
				    else {
					        foreach ($k as $j) {
							     echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					             echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><span> commented '{$j}'</span><br><br>";
						    }
				    }
			    }
			}
			else {
				echo "You are not friends with this user<br>";
			}
		}
	}
	public function viewCommentsimage($u, $p, $i) {
		$query = "SELECT * FROM image_comments WHERE user_id = $u AND album_no = $p AND image_no = $i";
		$result = mysqli_query($this->db, $query) or die('error in query');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['comments']);
		if ($u == $this->user_id) {
			foreach ($s as $id => $k ) {
				$e = $this->findUser($id);
				if ($e['user_id'] == $this->user_id) {
					    foreach ($k as $j) {
							echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					        echo "You commented '{$j}' <br><br>";
						}
				}
				else {
					foreach ($k as $j) {
							echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					        echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']} </a><span>commented '{$j}'</span><br><br>";
						}
				}
			}
		}
		else {
			$status = $this->findStatus($u);
			if ($status['status'] == 2) {
				foreach ($s as $id => $k ) {
				     $e = $this->findUser($id);
				     if ($e['user_id'] == $this->user_id) {
					        foreach ($k as $j) {
							    echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					            echo "You commented '{$j}' <br><br>";
						    }
				    }
				    else {
					        foreach ($k as $j) {
							     echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					             echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><span> commented '{$j}'</span><br><br>";
						    }
				    }
			    }
			}
			else {
				echo "You are not friends with this user<br>";
			}
		}
	}
	public function viewLikesimage($u, $p, $i) {
		$query = "SELECT * FROM image_likes WHERE user_id = $u AND album_no = $p AND image_no = $i";
		$result = mysqli_query($this->db, $query) or die('error in query');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['likes']);
		if ($u == $this->user_id) {
			foreach ($s as $id) {
				$e = $this->findUser($id);
				if ($e['user_id'] == $this->user_id) {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "Liked by you <br><br>";
				}
				else {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><br><br>";
				}
			}
		}
		else {
			$status = $this->findStatus($u);
			if ($status['status'] == 2) {
				foreach ($s as $id) {
				    $e = $this->findUser($id);
				    if ($e['user_id'] == $this->user_id) {
					    echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					    echo "Liked by you <br><br>";
				    }
				    else {
					echo "<img src = '{$e['pro_pic']}' alt = 'profile image' style ='width:50px;height:50px;'>";
					echo "<a href = 'view-profile.php?q={$e['user_id']}'>{$e['first_name']}</a><br><br>";
				   }
			    }  
			}
			else {
				echo "You are not friends with this user<br>";
			}
		}
	}
	public function addPhotos($c, $n, $v) {
		$er = array();
		$query = "SELECT MAX(album_no) FROM images WHERE user_id = $this->user_id";
		$result = mysqli_query($this->db, $query) or die ('error accessing the album <br> <a href = "index.php"> Go to home </a>'); 
		if ($row = mysqli_fetch_array($result)) {
			       $album_no = $row['MAX(album_no)'] + 1;
		}
		else {
			      $album_no = 1;
		}
		for ($i = 0; $i < $c ; $i++) {
			$picture = mysqli_real_escape_string($this->db, trim($_FILES['pictures']['name'][$i]));
			$target = "";
		    $er[$i] = "";
			if (!empty($picture)) {
			     $random = rand(45555, 99999);
		         $picture_type = $_FILES['pictures']['type'][$i];
		         $picture_size = $_FILES['pictures']['size'][$i];
		         list($picture_width, $picture_height) = getimagesize($_FILES['pictures']['tmp_name'][$i]);
			     if ((($picture_type == 'image/jpg') || ($picture_type == 'image/jpeg') || ($picture_type == 'image/png')) && ($picture_size > 0) && ($picture_size <= MM_MAXFILESIZE) && ($picture_width <= MM_MAXIMGWIDTH) && ($picture_height <= MM_MAXIMGHEIGHT)) {
				     $base = "$random".basename($picture);
		             $target = MM_UPLOADPATHPHOTOS ."$base";
				     if (move_uploaded_file($_FILES['pictures']['tmp_name'][$i], $target)) {
				     }
				     else {
					     @unlink($_FILES['pictures']['tmp_name'][$i]);
					     $er[$i] = "There is an error in uploading post picture";
				     }
			    }    
			    else {
				@unlink($_FILES['pictures']['tmp_name'][$i]);
				$er[$i] = "Your picture must be a GIF, JPEG, or PNG image file not greater than " . (MM_MAXFILESIZE / 1024) ." KB and " . MM_MAXIMGWIDTH . "x" . MM_MAXIMGHEIGHT . " pixels in size<br><br>";
			    }
				if (empty($er[$i])) {
		              $like = array();
		              $count = count($like);
		              $r = serialize($like);
					  $w = $i + 1;
		              $query3 = "INSERT INTO image_likes VALUES ($this->user_id, $album_no, $w , $count , '$r')";
					  $query4 = "INSERT INTO image_comments VALUES ($this->user_id, $album_no, $w , $count , '$r')";
		              $query2 = "INSERT INTO images VALUES ($this->user_id, $album_no, $w, '$n',  $v, '$target', now())";
		              $result1 = mysqli_query($this->db, $query2) or die('error uploading the post <br> <a href = "index.php"> Go to home </a>');
		              $result3 = mysqli_query($this->db, $query3) or die('error uploading the likes 1<br> <a href = "index.php"> Go to home </a>');
					  $result4 = mysqli_query($this->db, $query4) or die('error uploading the comments <br> <a href = "index.php"> Go to home </a>');
		        }
		    }
		}
		return $er;
	}
	public function viewAlbum($q) {
		if ($q == $this->user_id) {
			$user = $this->user_id;
			$a = $this->findalbum($user);
			$c = count($a);
			for ($i = 0; $i < $c; $i++) {
			         $query = "SELECT * FROM images WHERE user_id = $user AND album_no = {$a[$i]}";
			         $result = mysqli_query($this->db, $query) or die('Error in gallery');
					 $row = mysqli_fetch_array($result);
				     echo <<<END
					 <div id = {$row['album_no']}>
					 <p> Album No: {$row['album_no']} <br> Album Name: {$row['album_name']} </p>
					 <a href='gallery.php?q={$user}&r={$row['album_no']}'> Click here to View this album </a><br><br>
					 <button type = 'button' value = {$row['album_no']} onclick = "deleteAlbum(this.value)"> Delete Album </button><hr></div>
END;
					 
			}
		}
		else {
			$r = $this->findStatus($q);
			$s = $r['status'];
			$user = $q;
			$a = $this->findalbum($user);
			$c = count($a);
			switch ($s) {
				case 0:
				case 1:
				    for ($i = 0; $i < $c; $i++) {
			            $query = "SELECT * FROM images WHERE user_id = $user AND album_no = {$a[$i]}";
			            $result = mysqli_query($this->db, $query) or die('Error in album');
					    $row = mysqli_fetch_array($result);
						if ($row['visibility'] == 0) {
							echo <<<END
					        <p> Album No: {$row['album_no']} <br> Album Name: {$row['album_name']} </p>
					        <a href='gallery.php?q={$user}&r={$row['album_no']}'> Click here to View this album </a><hr>
END;
						}
					}
					break;
				case 2:
				    for ($i = 0; $i < $c; $i++) {
			            $query = "SELECT * FROM images WHERE user_id = $user AND album_no = {$a[$i]}";
			            $result = mysqli_query($this->db, $query) or die('Error in gallery');
					    $row = mysqli_fetch_array($result);
				        echo <<<END
					    <p> Album No: {$row['album_no']} <br> Album Name: {$row['album_name']} </p>
					    <a href='gallery.php?q={$user}&r={$row['album_no']}'> Click here to View this album </a><hr>
END;
			        }
				break;
				case 3:
				     echo "You are not allowed to view this profile<br>";
			}
		}
	}
	public function viewGallery($q, $r) {
		if ($q == $this->user_id) {
			$user = $q;
			$query = "SELECT * FROM images WHERE user_id = $q AND album_no = $r";
			$result = mysqli_query($this->db, $query) or die('error accessing gallery');
			$w = $this->findal($q, $r);
			echo "<h1>ALBUM NAME: {$w['album_name']} </h1>";
			echo "<input type = 'hidden' id = 'album_no' value = {$w['album_no']}>";
			while ($row = mysqli_fetch_array($result)) {
			    $query3 = "SELECT * FROM image_likes WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
				$query4 = "SELECT * FROM image_comments WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
				$result3 = mysqli_query($this->db, $query3) or die("error viewing3");
				$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				$row3 = mysqli_fetch_array($result3);
				$row4 = mysqli_fetch_array($result4);
				$d = unserialize($row3['likes']);
				if (in_array($this->user_id, $d)) {
					$g = "Unlike";
				}
				else {
					$g = "Like";
				}
				echo <<<END
				<div id = '{$row['image_no']}'>
				<img src = '{$row['location']}' alt = 'album image' style ='width:230px;height:220px;padding: 10px;'><br><br>
				<button type = 'button' value = {$row['image_no']} onclick = 'deletePicture(this.value)'>Delete Picture </button><br><br>
				<span id = "count{$row['image_no']}"> {$row3['like_count']}</span><a href='view-likes.php?u={$user}&i={$row['image_no']}&p={$row['album_no']}' id = {$row['image_no']}> people liked this </a><br><br>
			    <button type = 'button' id = "like{$row['image_no']}" value = {$row['image_no']} onclick = 'like(this.value, {$user})'>{$g}</button><br><br>
				<span id = "comment{$row['image_no']}">{$row4['count']}</span><a href = 'view-comments.php?u={$user}&p={$row['album_no']}&i={$row['image_no']}'> comments </a><br><br>
				<input type = "text" id = "co{$row['image_no']}" placeholder = "Comment here..."/> 
				<button type = "button" value = "{$row['image_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
				</div>
END;
			}
		}
		else {
			$w = $this->findal($q,$r);
			$v = $w['visibility'];
			$e = $this->findStatus($q);
			$s = $e['status'];
			$user = $q;
			$query = "SELECT * FROM images WHERE user_id = $q AND album_no = $r";
			$result = mysqli_query($this->db, $query) or die('error accessing gallery');
			switch ($s) {
				case 0:
				case 1:
				    if ($v == 0 ) {
				          echo "<h1>ALBUM NAME: {$w['album_name']} </h1>";
						  echo "<input type = 'hidden' id = 'album_no' value = {$w['album_no']}>";
				          while ($row = mysqli_fetch_array($result)) {
							    $query3 = "SELECT * FROM image_likes WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
								$query4 = "SELECT * FROM image_comments WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
							   	$result3 = mysqli_query($this->db, $query3) or die("error viewing3");
								$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				                $row3 = mysqli_fetch_array($result3);
								$row4 = mysqli_fetch_array($result4);
				                $d = unserialize($row3['likes']);
				                if (in_array($this->user_id, $d)) {
					                      $g = "Unlike";
				                }
				                else {
					                      $g = "Like";
				                }
				               echo <<<END
				               <div>
				               <img src = '{$row['location']}' alt = 'album image' style ='width:230px;height:220px;padding: 10px;'><br><br>
							   <span id = "count{$row['image_no']}"> {$row3['like_count']}</span><span id = {$row['image_no']}> people liked this </span><br><br>
							   <button type = 'button' id = "like{$row['image_no']}" value = {$row['image_no']} onclick = 'like(this.value, {$user})'>{$g}</button><br><br>
							   <span id = "comment{$row['image_no']}">{$row4['count']}</span><p> comments </p><br><br>
				               <input type = "text" id = "co{$row['image_no']}" placeholder = "Comment here..."/> 
				               <button type = "button" value = "{$row['image_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
				               </div>
END;
					      }
		            }
					break;
				case 2:
				    echo "<h1>ALBUM NAME: {$w['album_name']} </h1>";
					echo "<input type = 'hidden' id = 'album_no' value = {$w['album_no']}>";
				    while ($row = mysqli_fetch_array($result)) {
				                $query3 = "SELECT * FROM image_likes WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
								$query4 = "SELECT * FROM image_comments WHERE user_id = $user AND album_no = $r AND image_no = {$row['image_no']}";
							   	$result3 = mysqli_query($this->db, $query3) or die("error viewing3");
								$result4 = mysqli_query($this->db, $query4) or die("error viewing4");
				                $row3 = mysqli_fetch_array($result3);
								$row4 = mysqli_fetch_array($result4);
				                $d = unserialize($row3['likes']);
				                if (in_array($this->user_id, $d)) {
					                      $g = "Unlike";
				                }
				                else {
					                      $g = "Like";
				                }
				                echo <<<END
				               <div>
				               <img src = '{$row['location']}' alt = 'album image' style ='width:230px;height:220px;padding: 10px;'><br><br>
							   <span id = "count{$row['image_no']}"> {$row3['like_count']}</span><a href='view-likes.php?u={$user}&i={$row['image_no']}&p={$row['album_no']}' id = {$row['image_no']}> people liked this </a><br><br>
							   <button type = 'button' id = "like{$row['image_no']}" value = {$row['image_no']} onclick = 'like(this.value, {$user})'>{$g}</button><br><br>
							   <span id = "comment{$row['image_no']}">{$row4['count']}</span><a href = 'view-comments.php?u={$user}&p={$row['album_no']}&i={$row['image_no']}'> comments </a><br><br>
				               <input type = "text" id = "co{$row['image_no']}" placeholder = "Comment here..."/> 
				               <button type = "button" value = "{$row['image_no']}" onclick = "comment(this.value, {$user})">Comment</button><br><br>
				               </div>
END;
			        }
					break;
				case 3 :
				     echo "<br>You cannot view this page<br>";
	        }
	    }
	}
	public function deleteAlbum($a) {
		$query = "SELECT location FROM images WHERE user_id = $this->user_id AND album_no = $a";
		$result = mysqli_query($this->db, $query) or die('error deleteing');
		while ($row = mysqli_fetch_array($result)) {
			unlink("{$row['location']}");
		}
		$query2 = "DELETE FROM images WHERE user_id = $this->user_id AND album_no = $a";
		$query3 = "DELETE FROM image_likes WHERE user_id = $this->user_id AND album_no = $a";
		$result2 = mysqli_query($this->db, $query2) or die("error1");
		$result3 = mysqli_query($this->db, $query3) or die("error2");
	}
	public function deletePicture($a,$i) {
		$query = "SELECT location FROM images WHERE user_id = $this->user_id AND album_no = $a AND image_no = $i" ;
		$result = mysqli_query($this->db, $query) or die('error deleteing');
		$row = mysqli_fetch_array($result);
		unlink("{$row['location']}");
		$query2 = "DELETE FROM images WHERE user_id = $this->user_id AND album_no = $a AND image_no = $i";
		$query3 = "DELETE FROM image_likes WHERE user_id = $this->user_id AND album_no = $a AND image_no = $i";
		$result2 = mysqli_query($this->db, $query2) or die("error1");
		$result3 = mysqli_query($this->db, $query3) or die("error2");
	}
	public function addComment($u, $p, $r) {
		$query  = "SELECT * FROM post_comments WHERE user_id = $u AND post_no = $p";
		$result = mysqli_query($this->db, $query) or die ('error in comments');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['comments']);
		$s["{$this->user_id}"][] = $r;
		$w = serialize($s);
		$n = count($s);
		$c = count($s, COUNT_RECURSIVE) - $n;
		$query2 = "UPDATE post_comments SET count = $c , comments = '$w' WHERE user_id = $u AND post_no = $p";
		$result2 = mysqli_query($this->db, $query2) or die ('error in quu');
		return $c;
	}
	public function addCommentimage($u, $p, $i, $c) {
		$query  = "SELECT * FROM image_comments WHERE user_id = $u AND album_no = $p AND image_no = $i";
		$result = mysqli_query($this->db, $query) or die ('error in comments');
		$row = mysqli_fetch_array($result);
		$s = unserialize($row['comments']);
		$s["{$this->user_id}"][] = $c;
		$w = serialize($s);
		$n = count($s, COUNT_RECURSIVE) - count($s);
		$query2 = "UPDATE image_comments SET count = $n , comments = '$w' WHERE user_id = $u AND album_no = $p AND image_no = $i";
		$result2 = mysqli_query($this->db, $query2) or die ('error in quu');
		return $n;
	}
}
?>