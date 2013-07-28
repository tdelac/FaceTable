<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once 'login.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSION['prenom'])){
	echo <<<_END
You must <a href='login_regis
ter.php'>login or register</a> to view this page
_END;
}
else {
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
	$userpre = $row[0];
	$usersur = $row[1];
	if ($email != $useremail){
		$table = removePeriodsAndAt($email) . "guests";
		$result = mysql_query("SELECT * FROM $table WHERE id='$id' 
		AND status='true'");
		$row = mysql_fetch_row($result);
		if (!empty($row)) showprofile($userpre, $usersur, 
			$useremail, $url);
		else {
			$h1 = "Oops!";
			$tabs = array("Home", "Profile", "Blog", "Find Others");
			$links = array("index.php", "profile.php", 
			"bloghome.php", "findothers.php");
			$ids = array("home", "profile", "blog", "find");
			head($h1, $tabs, $links, $ids, "loggedin");
			echo <<<_END
<div class='body'>
<div class='findimg'>
<img src=imgs/$useremail/t.jpg></div>
<div class='gueststatus'>
<h2>$userpre $usersur</h2>
<form action='findothers.php' method='post'>
<input type='hidden' name='sendguestreq' value='$id'>
<input class='submit' type='submit' value='Send Guest Request'>
</form>
</div>
</div>
_END;
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
	$time = time() - 18960;
	mysql_query("CREATE TABLE $newtable (posts VARCHAR(65000) 
	NOT NULL, id INT UNSIGNED NOT NULL AUTO_INCREMENT, time 
	INT UNSIGNED NOT NULL, PRIMARY KEY(id))");
	mysql_query("INSERT INTO $userblogs(time, title)
	VALUES('$time', '$newblog')");
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
			$max = 175;
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

			if ($imagenum == 1){
				$thumb = "imgs/$email/t.jpg";
				copy($path, $thumb);
				list($w, $h) = getimagesize($thumb);
				$src = imagecreatefromjpeg($thumb);
				$max = 100;
				$tmp = imagecreatetruecolor($max, $max);
				if ($w < $h){
					imagecopyresampled($tmp, $src, 
					0, 0, 0, $h - $w, $max, $max,
					$w, $h);
					imagejpeg($tmp, $thumb);
				}
				elseif ($h < $w){
					imagecopyresampled($tmp, $src,
					0, 0, 0, 0, $max, $max,
					$w, $h);
					imagejpeg($tmp, $thumb);
				}
				else {
					imagecopyresampled($tmp, $src,
					0, 0, 0, 0, $max, $max, $w, $h);
					imagejpeg($tmp, $thumb);
				}
				imagedestroy($tmp);
				imagedestroy($src);
			}
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
showprofile($prenom, $surname, $email, $url);
}
}
?>
