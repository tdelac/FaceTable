<?php
require_once 'login.php';
require_once 'functions.php';
include_once 'blogheader.php';

if (!isset($_SESSION['prenom'])) die ("please login to view this page");

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die ('could not connect to database server');
mysql_select_db('users', $db_server) or die ('unable to open database');

if (isset($_GET['title']) && isset($_GET['author'])){
	$_title = $_GET['title'];
	$title = str_replace('_', ' ', $_title);
	$author = $_GET['author'];
	$url = "blog.php?title=$_title&author=$author";
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title'
	AND author='$author'");
	if (!$result) die ("could not select from table");
	$row = mysql_fetch_row($result);
	$email = $row[5];
	$table = removePeriodsAndAt($email) . removeSpaces($title);

	if (isset($_POST['newpost'])){
		$newpost = sanatize('newpost');
		mysql_query("INSERT INTO $table(posts) VALUES('$newpost')");
	}
	if (isset($_POST['follow'])){
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
		mysql_query("UPDATE allblogs SET title=$newtitle 
		WHERE title=$title AND author=$author");
	}
	if (isset($_POST['edittitle'])){
		echo <<<_END
<div class='notheader'>
<form action='$url' method='post'>
Edit your title <input type='text' name='newtitle' value='$title' />
<input type='submit' value='UPDATE' />
</form>
</div>
_END;
	}
	elseif (isset($_POST['post'])){
		echo <<<_END
<div class='notheader'>
<form action='$url' method='post'>
New post <textarea name='newpost' cols='100' rows='20' wrap='hard'>
</textarea>
<input type='submit' value='POST' />
</form>
</div>
_END;
	}
	elseif (isset($_GET['editid'])){
		$id = $_GET['editid'];
		$result = mysql_query("SELECT * FROM $table WHERE id=$id");
		$row = mysql_fetch_row($result);
		$text = $row[0];
		echo <<<_END
<div class='notheader'>
<form action='$url' method='post'>
Edit your post <textarea name='updated' cols='100' rows='20' wrap='hard'>
$text
</textarea>
<input type='hidden' name='editid' value=$id />
<input type='submit' value='UPDATE' />
</form>
</div>
_END;
	}
	else showblog($title, $author, $url);
}
