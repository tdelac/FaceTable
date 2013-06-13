<?php
include_once 'header.php';
require_once 'login.php';
require_once 'functions.php';

if (!isset($_SESSION['prenom'])) die("Please login to view this page");
$email = $_SESSION['email'];
$prenom = $_SESSION['prenom'];
$surname = $_SESSION['surname'];

echo <<<_END
<html>
  <head>
    <title>Welcome, $prenom</title>
    <link rel="stylesheet" type="text/css" href="style/profilestyle.css" />
  </head>
_END;

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
else {
	$result = mysql_query("SELECT * FROM profiles WHERE email='$email'");
	if (mysql_num_rows($result)){
		$row = mysql_fetch_row($result);
		$text = $row[1];
	}
	else $text = "";
}
if (isset($_GET['removeblog'])){
	$title = $_GET['removeblog'];
	$table = $email . "blogs";
	mysql_query("DELETE FROM $table WHERE title='$title'");
	mysql_query("DELETE FROM blogs WHERE title='$title . $prenom . $surname'");
}
if (isset($_POST['img'])){
	$imagenum = $_POST['img'];

	if (isset($_FILES['image']['name'])){
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
			$max = 150;
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
if (isset($_GET['edit'])){
	echo <<<_END
<form action="profile.php" method="post" enctype="multipart/form-data"><pre>
Select a picture to replace 
(numbered from top to bottom)<select name="img" size="1">
			     <option value="1">1</option>
			     <option value="2">2</option>
			     <option value="3">3</option>
			     <option value="4">4</option>
			     <option value="5">5</option>
			     <option value="6">6</option>
			     <option value="7">7</option>
			     </select>
Image:	    		     <input type="file" name="image" size="14" maxlength="32"i />
			     <textarea name="text" cols="50" rows="7" wrap="hard">
$text
			     </textarea>
			     <input type="submit" value="SAVE CHANGES" />
</pre></form>
_END;
}
else {
echo "<a href='profile.php?edit=yes'>edit</a>";
}
showprofile($email);
?>
