<?php
include_once 'login.php';
include_once 'functions.php';

session_start();

$h1 = "Blog&nbsp&nbsp";
$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
$links = array("index.php", "feed.php", "profile.php", "bloghome.php", "findothers.php", "logout.php");
$ids = array("home", "feed", "profile", "blog", "find", "login");
head($h1, $tabs, $links, $ids, "loggedin");

if (!isset($_SESSION['prenom'])) die ("please login to view this page");

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die ("Cannot connect to database server" . mysql_error());
mysql_select_db('users', $db_server) or die ("unable to open database");

echo <<<_END
<div class='body'>
<h2>Existing Blogs<a class='getlink' href='bloghome.php?sort=time'>
| Most Recent</a><a class='getlink' href='bloghome.php?sort=pop'>
Most Popular&nbsp</a></h2>
_END;

if (isset($_GET['sort'])){
	if ($_GET['sort'] == "time"){
		$result = mysql_query("SELECT * FROM allblogs ORDER BY 
		id DESC");
	}
	if ($_GET['sort'] =="pop"){
		$result = mysql_query("SELECT * FROM allblogs ORDER BY 
		followerscount DESC");
	}
}
else {
	$result = mysql_query("SELECT * FROM allblogs ORDER BY id DESC");
}
for ($i = 0; $i < mysql_num_rows($result); $i++){
	$row = mysql_fetch_row($result);
	$title = $row[0];
	$author = $row[2];
	$time = $row[4];
	$date = date("F j, Y, g:i a", $time);
	echo "<p class='profile'><a class='blogs' 
	href='blog.php?title=$title&author=$author'>$row[0]</a> 
	&nbsp($row[2] -<span class='timestamp'>$date</span>)
	</p>";
}
echo "</div>";
?>
