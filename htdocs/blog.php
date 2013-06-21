<?php
require_once 'login.php';
require_once 'functions.php';
include_once 'blogheader.php';

if (!isset($_SESSION['prenom'])) die ("please login to view this page");

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die ('could not connect to database server');
mysql_select_db('users', $db_server) or die ('unable to open database');

if (isset($_GET['title']) && isset($_GET['author'])){
	$title = $_GET['title'];
	$author = $_GET['author'];
	$result = mysql_query("SELECT * FROM allblogs WHERE title=$title
	AND author=$author");
	if (!$result) die ("could not select from table");
	$row = mysql_fetch_row($result);
	$email = $row[5];
	$table = removePeriodsAndAt($email) . removeSpaces($title);

	if (isset($_POST['newpost'])){
		$newpost = sanatize('newpost');
		mysql_query("INSERT INTO $table(posts) VALUES($newpost)");
	}
	if (isset($_POST['follow'])){
	}
	if (isset($_POST['editid'])){
		$id = sanatize('editid');
		$updated = sanatize('updated');
		mysql_query("UPDATE $table SET posts=$updated WHERE id=$id");
	}
	if (isset($_POST['removeid'])){
		$id = sanatize('removeid');
		mysql_query("DELETE FROM $table WHERE id=$id");
	}
	if (isset($_POST['newtitle'])){
		$newtitle = sanatize('newtitle');
		$newtable = removePeriodsAndAt($email) . 
		removeSpaces($newtitle);
		$userblogs = removePeriodsAndAt($email) . "blogs";
		mysql_query("RENAME TABLE $table TO $newtable");
		mysql_query("UPDATE $userblogs SET title=$newtitle 
		WHERE title=$title");
		mysql_query("UPDATE allblogs SET title=$newtitle 
		WHERE title=$title and author=$author");
	}
	if isset post edit title display form
	elseif isset post post display form
	elseif isset get editid
	else function display blog
}
