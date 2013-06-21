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
if (isset($_GET['blogremove'])){
	$table = removePeriodsAndAt($email) . "blogs";
	$id = $_GET['blogremove'];
	$result = mysql_query("SELECT * FROM $table WHERE id=$id");
	$row = mysql_fetch_row($result);
	$title = $row[2];
	$blogtable = removePeriodsAndAt($email) . removeSpaces($title);
	mysql_query("DELETE FROM $table WHERE id=$id");
	mysql_query("DELETE FROM allblogs WHERE title=$title AND 
	email=$email");
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
showprofile($email, $url);
?>
