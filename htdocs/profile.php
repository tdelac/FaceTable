<?php
include_once 'header.php';
require_once 'login.php';
require_once 'functions.php';

$email = $_SESSION['email'];
$prenom = $_SESSION['prenom'];
$surname = $_SESSION['surname'];
$url = 'profile.php';

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("unable to connect to mysql");
mysql_select_db('users', $db_server) or die("unable to open database");

if (isset($_GET['user'])){
	$id = $_GET['user'];
	$url = "profile.php?user=$id";
	$result = mysql_query("SELECT * FROM registration WHERE id='$id'");
	$row = mysql_fetch_row($result);
	$useremail = $row[2];
	if ($email != $useremail){
		$table = removePeriodsAndAt($email) . "guests";
		$result = mysql_query("SELECT * FROM $table WHERE id='$id' 
		AND status='true'");
		$row = mysql_fetch_row($result);
		if (!empty($row)) showprofile($useremail, $url);
		else {
			echo "You must be mutual guests to see
			this profile";
		}
	}
}
else { 
if (isset($_POST['text'])){
	$text = sanatize('text');
	$query = "SELECT * FROM profiles WHERE email='$email'";
	$result = mysql_query($query);

	if (mysql_num_rows($result)){
		mysql_query("UPDATE profiles SET text='$text' WHERE email='$email'");
	}
	else {
		mysql_query("INSERT INTO profiles VALUES('$email','$text')");
	}
}
if (isset($_POST['newtitle'])){
	$newblog = sanatize('newtitle');
	$newtable = removePeriodsAndAt($email) . removeSpaces($newblog);
	$userblogs = removePeriodsAndAt($email) . "blogs";
	$time = time();
	mysql_query("CREATE TABLE $newtable (posts VARCHAR(65000) 
	NOT NULL, id INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY
	(id))");
	mysql_query("INSERT INTO $userblogs(time, title, followers)
	VALUES('$time', '$newblog', '0')");
	mysql_query("INSERT INTO allblogs(title, author, followerscount,
	time, email) VALUES('$newblog', '$prenom $surname', '0', '$time',
	'$email')");
}
if (isset($_GET['blogremove'])){
	$table = removePeriodsAndAt($email) . "blogs";
	$id = $_GET['blogremove'];
	$result = mysql_query("SELECT * FROM $table WHERE id=$id");
	$row = mysql_fetch_row($result);
	$title = $row[2];
	$blogtable = removePeriodsAndAt($email) . removeSpaces($title);
	mysql_query("DELETE FROM $table WHERE id=$id");
	mysql_query("DELETE FROM allblogs WHERE title='$title' AND 
	email='$email'");
	mysql_query("DROP TABLE $blogtable");
}
if (isset($_GET['img'])){
	$imagenum = $_GET['img'];

	if (isset($_FILES['image']['name'])){
		if (file_exists("imgs/$email/$imagenum.jpg")){
			unlink("imgs/$email/$imagenum.jpg");
		}
		$path = "imgs/$email/$imagenum.jpg";
		move_uploaded_file($_FILES['image']['tmp_name'], $path);
		$typeok = TRUE;

		switch($_FILES['image']['type']){
			case "image/gif":   $src = imagecreatefromgif($path);
					    break;
			case "image/jpeg": 
			case "image/pjpeg": $src = imagecreatefromjpeg($path);
					    break;
			case "image/png":   $src = imagecreatefrompng($path);
					    break;
			default:            $typeok = FALSE;
					    break;
		}
		if ($typeok){
			list($w, $h) = getimagesize($path);
			$max = 200;
			$nw = $w;
			$nh = $h;

			$nh = $max * $h / $w;
			$nw = $max;

			$tmp = imagecreatetruecolor($nw, $nh);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, 
				$nw, $nh, $w, $h);
			imageconvolution($tmp, array(
					       array(-1, -1, -1),
					       array(-1, 16, -1),
					       array(-1, -1, -1)
					       ), 8, 0);
			imagejpeg($tmp, $path);
			imagedestroy($tmp);
			imagedestroy($src);
		}
	}
}	
if (isset($_GET['guestaccept'])){
	$id = $_GET['guestaccept'];
	$result = mysql_query("SELECT * FROM registration WHERE
	id='$id'");
	$row = mysql_fetch_row($result);
	$guestemail = $row[2];
	$theirguests = removePeriodsAndAt($guestemail) . "guests";
	$myguests = removePeriodsAndAt($email) . "guests";
	$result = mysql_query("UPDATE $myguests SET status='true' 
	WHERE id='$id'");
	if (!$result) die(mysql_error());
	$result = mysql_query("SELECT * FROM registration WHERE 
	email='$email'");
	$row = mysql_fetch_row($result);
	$id = $row[5];
	$result = mysql_query("INSERT INTO $theirguests (id, status)
	VALUES ('$id', 'true')");
	if (!$result) die(mysql_error());
}
if (isset($_GET['guestdecline'])){
	$id = $_GET['guestdecline'];
	$myguests = removePeriodsAndAt($email) . "guests";
	$result = mysql_query("DELETE FROM $myguests WHERE
	id='$id'");
}
showprofile($email, $url);
}
?>
