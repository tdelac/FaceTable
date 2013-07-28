<?php
require_once 'functions.php';
require_once 'login.php';

session_start();

$h1 = "Search&nbsp&nbsp";
$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
$links = array("index.php", "feed.php", "profile.php", "bloghome.php", "findothers.php", "logout.php");
$ids = array("home", "feed", "profile", "blog", "find", "login");
head($h1, $tabs, $links, $ids, "loggedin");

if (!isset($_SESSION['prenom'])) die ("Please login to continue");

$email = $_SESSION['email'];

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die ("Could not connect to mysql server");
mysql_select_db('users', $db_server) or die ("unable to open database");

echo <<<_END
<div class='body'>
<form action='findothers.php' method='post'>
<input class='normal2' type='text' name='search' maxlength='35' />
<input class='submit2' type='submit' value='Find' />
</form>
_END;

if (isset($_POST['search'])){
	$searchstring = sanatize('search');
	$searcharray = explode(' ', $searchstring);
	$first = $searcharray[0];
	$second = $searcharray[1];
	$result = mysql_query("SELECT * FROM registration WHERE 
	prenom LIKE '%$first%' AND surname LIKE '%$second%'");

	for ($i = 0; $i < mysql_num_rows($result); $i++){
		$row = mysql_fetch_row($result);
		$prenom = $row[0];
		$surname = $row[1];
		$useremail = $row[2];

		if ($email != $useremail){
			$id = $row[5];
			$table = removePeriodsAndAt($email) . "guests";
			$result1 = mysql_query("SELECT * FROM $table WHERE 
			id='$id' AND status='true'");
			$row = mysql_fetch_row($result1);
			if (!empty($row)) {
				$value1 = "Un-Guest";
				$value2 = "unguest";
			}
			else {
				$value1 = "Send Guest Request";
				$value2 = "sendguestreq";
			}
			echo <<<_END
<div class='bodyelement2'>
<div class='findimg'><a href='profile.php?user=$id'>
<img src='imgs/$useremail/t.jpg'></a></div> 
<div class='gueststatus'>
<h2>$prenom $surname</h2>
<form action='findothers.php' method='post'>
<input type='hidden' name='$value2' value='$id' />
<input class='submit' type='submit' value='$value1' />
</form>
</div>
</div>
_END;
		}
		else {
			echo <<<_END
<div class='bodyelement2'>
<div class='findimg'>
<a href='profile.php'><img src='imgs/$email/t.jpg'></a>
</div>
<div class='gueststatus'>
<h2>You could just click 'profile'...</h2>
</div>
</div>
_END;
		}
	}
}
if (isset($_POST['sendguestreq']) || isset($_POST['unguest'])){
	$id = isset($_POST['sendguestreq']) ? sanatize('sendguestreq') 
	: sanatize('unguest');
	$result = mysql_query("SELECT * FROM registration WHERE id='$id'");
	$row = mysql_fetch_row($result);
	$useremail = $row[2];
	$guesttable = removePeriodsAndAt($useremail) . "guests";
	$myguests = removePeriodsAndAt($email) . "guests";
	$result = mysql_query("SELECT * FROM registration WHERE email=
	'$email'");
	$row = mysql_fetch_row($result);
	$id1 = $row[5];
	if (isset($_POST['sendguestreq'])){
		$result = mysql_query("INSERT INTO $guesttable(id, status) 
		VALUES('$id1', 'false')");
		if (!$result) die(mysql_error());
	}
	else {
		$result = mysql_query("DELETE FROM $guesttable WHERE 
		id='$id1'");
		if (!$result) die(mysql_error());
		$result = mysql_query("DELETE FROM $myguests WHERE 
		id='$id'");
		if (!$result) die(mysql_error());
	}
}
echo "</div>";
