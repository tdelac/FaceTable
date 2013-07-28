<?php
require_once 'login.php';
require_once 'functions.php';

session_start();

$h1 = "BLOG&nbsp&nbsp";
$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
$links = array("index.php", "feed.php", "profile.php", "bloghome.php", "findothers.php", "logout.php");
$ids = array("home", "feed", "profile", "blog", "find", "login");
head($h1, $tabs, $links, $ids, "loggedin");

if (!isset($_SESSION['prenom'])) die ("please login to view this page");

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die ('could not connect to database server');
mysql_select_db('users', $db_server) or die ('unable to open database');

if (isset($_GET['title']) && isset($_GET['author'])){
	$_title = $_GET['title'];
	$title = str_replace('_', ' ', $_title);
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title'");
	if (!$result) die ("Nice try. Stop messing with my GET variables.");
	$_author = $_GET['author'];
	$author = str_replace('_', ' ', $_author);
	$url = "blog.php?title=$_title&author=$_author";
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title'
	AND author='$author'");
	if (!$result) die ("could not select from table");
	$row = mysql_fetch_row($result);
	$email = $row[5];
	$table = removePeriodsAndAt($email) . removeSpaces($title);

	if (isset($_POST['newpost'])){
		$newpost = sanatize('newpost');
		$time = time() - 18960;
		mysql_query("INSERT INTO $table(posts, time) 
		VALUES('$newpost', '$time')");
		$blogtable = removePeriodsAndAt($email) . "blogs";
		$result = mysql_query("SELECT * FROM $blogtable WHERE 
		title='$title'");
		$row = mysql_fetch_row($result);
		$followerarray = explode(",", $row[3]);
		foreach ($followerarray as $follower){
			$followingtable = removePeriodsAndAt($follower) . 
			"following";
			$result = mysql_query("UPDATE $followingtable SET 
			bool='false' WHERE title='$title' AND author='$author'");
		}
	}
	if (isset($_GET['bold'])){
		$id = $_GET['bold'];
		$result = mysql_query("SELECT * FROM registration WHERE 
		id='$id'");
		if (!$result) die(mysql_error());
		$row = mysql_fetch_row($result);
		$useremail = $row[2];
		$followingtable = removePeriodsAndAt($useremail) . "following";
		$result = mysql_query("UPDATE $followingtable SET bool='true' 
		WHERE title='$title' AND author='$author'");
		if (!$result) die("died" . mysql_error());
	}
	if (isset($_POST['follow'])){
		$useremail = $_SESSION['email'];
		$blogstable = removePeriodsAndAt($email) . "blogs";
		$followingtable = removePeriodsAndAt($useremail) . 
		"following";
		$result = mysql_query("SELECT * FROM $blogstable WHERE 
		title='$title'");
		$row = mysql_fetch_row($result);
		$followers = $row[3];
		$followers .= ",$useremail";
		$result = mysql_query("UPDATE $blogstable SET
		followers='$followers' WHERE title='$title'");
		if (!$result) die(mysql_error());
		$result = mysql_query("INSERT INTO $followingtable(
		author, title) VALUES('$author', '$title')");
		if (!$result) die(mysql_error());
		$result = mysql_query("SELECT * FROM $allblogs WHERE 
		title='$title' AND author='$author'");
		$row = mysql_fetch_row($result);
		$count = $row[3];
		$count++;
		$result = mysql_query("UPDATE $allblogs SET followerscount=
		'$count' WHERE title='$title' AND author='$author'");
	}
	if (isset($_POST['unfollow'])){
		$useremail = $_SESSION['email'];
		$blogstable = removePeriodsAndAt($email) . "blogs";
		$followingtable = removePeriodsAndAt($useremail) . 
		"following";
		$result = mysql_query("DELETE FROM $followingtable
		WHERE title='$title' AND author='$author'");
		if (!$result) die(mysql_error());
		$result = mysql_query("SELECT * FROM $blogstable
		WHERE title='$title'");
		$row = mysql_fetch_row($result);
		$followers = $row[3];
		$followarray = explode(",", $followers);
		$key = array_search($useremail, $followarray);
		array_splice($followarray, $key, 1);
		$followers = implode(",", $followarray);
		$result = mysql_query("UPDATE TABLE $blogstable
		SET followers='$followers' WHERE title='$title'");
	}
	if (isset($_POST['editid'])){
		$id = sanatize('editid');
		$updated = sanatize('updated');
		mysql_query("UPDATE $table SET posts='$updated' WHERE id='$id'");
	}
	if (isset($_GET['removeid'])){
		$id = $_GET['removeid'];
		mysql_query("DELETE FROM $table WHERE id=$id");
	}
	if (isset($_POST['newtitle'])){
		$newtitle = sanatize('newtitle');
		$newtable = removePeriodsAndAt($email) . 
		removeSpaces($newtitle);
		$userblogs = removePeriodsAndAt($email) . "blogs";
		mysql_query("RENAME TABLE $table TO $newtable");
		mysql_query("UPDATE $userblogs SET title='$newtitle' 
		WHERE title='$title'");
		mysql_query("UPDATE allblogs SET title='$newtitle' 
		WHERE title='$title' AND author='$author'");
		header("location: blog.php?title=$newtitle&author=$_author");
	}
	if (isset($_POST['edittitle'])){
		echo <<<_END
<div class='body'>
<form action='$url' method='post'>
<input class='normal' type='text' name='newtitle' value='$title' />
<input class='submit' type='submit' value='Update' />
</form>
</div>
_END;
	}
	elseif (isset($_POST['post'])){
		echo <<<_END
<div class='bodyblog'>
<form action='$url' method='post'>
<textarea name='newpost' cols='85' rows='10' wrap='hard'>
My new post
</textarea>
<input class='submit' type='submit' value='POST' />
</form>
</div>
_END;
	}
	elseif (isset($_GET['editid'])){
		$id = $_GET['editid'];
		$result = mysql_query("SELECT * FROM $table WHERE id='$id'");
		$row = mysql_fetch_row($result);
		$text = $row[0];
		echo <<<_END
<div class='bodyblog'>
<form action='$url' method='post'>
<textarea name='updated' cols='83' rows='10' wrap='hard'>
$text
</textarea>
<input type='hidden' name='editid' value=$id />
<input class='submit' type='submit' value='UPDATE' />
</form>
</div>
_END;
	}
	else showblog($title, $author, $url);
}
