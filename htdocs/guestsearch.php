<?php
require_once 'functions.php';
require_once 'login.php';

session_start();

if (!isset($_SESSION['prenom'])) die ("Please login to continue");

if (!isset($_GET['user'])){
	$h1 = "Oops!&nbsp&nbsp";
	$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
	$links = array("index.php", "feed.php", "profile.php", "bloghome.php", "findothers.php", "logout.php");
	$ids = array("home", "feed", "profile", "blog", "find", "login");
	head($h1, $tabs, $links, $ids, "loggedin");
	echo "<div class='body'>You haven't selected a user!</div>";
}
else {
	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) die ("Could not connect to mysql server");
	mysql_select_db('users', $db_server) or die ("unable to open database");

	$searchid = $_GET['user'];
	$result = mysql_query("SELECT * FROM registration WHERE id=
	'$searchid'");
	$row = mysql_fetch_row($result);
	$searchemail = $row[2];
	$url = "guestsearch.php?user=$searchid";
	$result = mysql_query("SELECT * FROM registration WHERE email=
	'$searchemail'");
	if (!$result) die (mysql_error());
	$row = mysql_fetch_row($result);
	$prenom = $row[0];
	$h1 = $prenom . "'s guests&nbsp&nbsp";
	$tabs = array("Home", "Profile", "Blog", "Find Others", "Logout");
	$links = array("index.php", "profile.php", "blog.php", "findothers.php", "logout.php");
	$ids = array("home", "profile", "blog", "find", "login");
	head($h1, $tabs, $links, $ids, "loggedin");

	$email = $_SESSION['email'];

	echo <<<_END
<div class='body'>
<form action='$url' method='post'>
<input class='normal2' type='text' name='search' maxlength='35' />
<input class='submit2' type='submit' value='Find' />
</form>
_END;
	$guesttable = removePeriodsAndAt($searchemail) . "guests";
	$result = mysql_query("SELECT * FROM $guesttable WHERE status=
	'true' ORDER BY RAND() LIMIT 50");
	echo "<div class='bodyelement2'>";
	for ($i = 0; $i < mysql_num_rows($result); $i++){
		$row = mysql_fetch_row($result);
		$id = $row[0];
		$result = mysql_query("SELECT * FROM registration WHERE 
		id='$id'");
		if (!$result) die(mysql_error());
		$row = mysql_fetch_row($result);
		$useremail = $row[2];
		echo <<<_END
<a href='profile.php?user=$id'><img class='guestpics' src='imgs/
$useremail/t.jpg'></a>
_END;
	}
	echo "</div>";
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
			$id = $row[5];

			$result1 = mysql_query("SELECT * FROM $guesttable WHERE 
			id='$id' AND status='true'");
			$row = mysql_fetch_row($result1);
			if (!empty($row)){
				if ($email != $useremail){
					$id = $row[0];
					$table = removePeriodsAndAt($email) . "guests";
					$result = mysql_query("SELECT * FROM 
					$table WHERE id='$id' AND status='true'");
					$row = mysql_fetch_row($result);
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
<h2>$prenom $surname</h2>
</div>
</div>
_END;
				}
			}
		}
	}
echo "</div>";
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
}
