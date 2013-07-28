<?php
require_once 'login.php';
include 'functions.php';
$h1 = "FaceTable&nbsp&nbsp&nbsp";
$tabs = array("About", "Services", "Philosophy", "Blog", "Photos", 
"Connect", "Contact", "Home");
$links = array("index.php?tab=about", "index.php?tab=services", 
"index.php?tab=phil", "bloghome.php", "photos.php", "profile.php", 
"index.php?tab=contact", "index.php");
$ids = array("About", "Services", "Phil", "Blog", "Photos", "Connect", 
"Contact", "login");
head($h1, $tabs, $links, $ids, "home");
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("unable to connect to MySql: " . mysql_error());

mysql_select_db('users', $db_server)
	or die("unable to select database: " . mysql_error());

$salt1 = "ih3%";
$salt2 = "7mt!";

if (isset($_POST['logEmail']) && 
	isset($_POST['logPass'])){

	$logEmail = sanatize('logEmail');
	$logPass = sanatize('logPass');

	$query = "SELECT * FROM registration WHERE email='$logEmail'";
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	elseif (mysql_num_rows($result) == 1){
		$row = mysql_fetch_row($result);
		if ($row[6] == 'false') die("need confirm address!");
		$token = md5("$salt1$logPass$salt2");

		if ($token == $row[3]){
			session_start();
			ini_set('session.gc_maxlifetime', 60 * 60);
			$_SESSION['prenom'] = $row[0];
			$_SESSION['surname'] = $row[1];
			$_SESSION['email'] = $logEmail;
			header("Location: index.php");
		}
		else {
			echo "<div class='notheader'>";
			echo "<p class='incorrect'>Incorrect email/ pass
			word combination</p>";
			loginform("normal");
			echo "</div>";
		}
	}
	else {
		echo "<div class='notheader'>";
		echo "<p class='incorrect'>Incorrect email/ password 
		combination</p>";
		loginform("normal");
		echo "</div>";
	}
}
elseif (isset($_POST['regPrenom']) &&
	isset($_POST['regSurname']) &&
	isset($_POST['regEmail']) &&
	isset($_POST['regPass']) &&
	isset($_POST['passConfirm'])){

	$prenom = sanatize('regPrenom');
	$surname = sanatize('regSurname');
	$email = sanatize('regEmail');
	$result = mysql_query("SELECT * FROM registration WHERE 
	email='$email'");
	if (mysql_num_rows($result) != 0){
		echo "<div class='notheader'>";
		echo "<p class='incorrect'>There already exists an account 
		associated with this email</p>";
		registerform("normal");
		echo "</div>";
	}
	elseif ($_POST['regPass'] != $_POST['passConfirm']) {
		echo "<div class='notheader'>";
		echo "<p class='incorrect'>Confirm password does not match!
		</p>";
		registerform("normal");
		echo "</div>";
	}
	else {
		$pass = md5($salt1 . sanatize('regPass') . $salt2);
		$zip = isset($_POST['zip']) ? sanatize('zip') : "";
		$result = mysql_query("SELECT * FROM registration WHERE 
		email='$email'");
		$query = "INSERT INTO registration (prenom, surname, 
		email, password, zipcode, bool) VALUES ('$prenom', '$surname', 
		'$email', '$pass', '$zip', 'false')";
		$result = mysql_query($query);
		if (!$result)
			die("INSERT FAILED: $query<br />" .
			mysql_error());
		$sqlname = removePeriodsAndAt($email) . 'blogs';
		$guesttable = removePeriodsAndAt($email) . 'guests';
		$followtable = removePeriodsAndAt($email) . 'following';
		$query = "CREATE TABLE $sqlname (id MEDIUMINT NOT NULL AUTO_INCREMENT, time INT NOT NULL, 
		title VARCHAR(300) NOT NULL, followers VARCHAR(65000), PRIMARY KEY(id));";
		if (!mysql_query($query, $db_server))
			die("INSERT FAILED: $query<br />" . 
			mysql_error());
		mysql_query("CREATE TABLE $guesttable (id INT UNSIGNED NOT NULL,
		status VARCHAR(5) NOT NULL)");
		$result = mysql_query("CREATE TABLE $followtable (author VARCHAR(50) NOT NULL,
		title VARCHAR(300) NOT NULL, id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id))");
		if (!$result) die(mysql_error());

		mkdir("imgs/$email", 0755);
		copy("imgs/tmp/1.jpg", "imgs/$email/1.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/2.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/3.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/4.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/5.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/6.jpg");
		copy("imgs/tmp/1.jpg", "imgs/$email/7.jpg");
		copy("imgs/tmp/t.jpg", "imgs/$email/t.jpg");

		header("Location: registerfinal.php?email=$email");
	}
}
elseif (isset($_POST['refemail'])){
	$refemail = sanatize('refemail');
	$useremail = sanatize('useremail');
	$result = mysql_query("SELECT * FROM registration WHERE email=
	'$refemail'");
	if (mysql_num_rows($result) != 0){
		$result = mysql_query("UPDATE registration SET bool=
		'true' WHERE email='$useremail'");
		if (!$result) die(mysql_error());
		loginform("normal");
	}
	else {
		$blogtable = removePeriodsAndAt($useremail) . "blogs";
		$followtable = removePeriodsAndAt($useremail) . "following";
		$guesttable = removePeriodsAndAt($useremail) . "guests";
		mysql_query("DROP TABLE $blogtable");
		mysql_query("DROP TABLE $followtable");
		mysql_query("DROP TABLE $guesttable");
		mysql_query("DELETE FROM registration WHERE email='$useremail'");
		mysql_query("DELETE FROM profiles WHERE email='$useremail'");
		unlink("imgs/$useremail/1.jpg");
		unlink("imgs/$useremail/2.jpg");
		unlink("imgs/$useremail/3.jpg");
		unlink("imgs/$useremail/4.jpg");
		unlink("imgs/$useremail/5.jpg");	
		unlink("imgs/$useremail/6.jpg");
		unlink("imgs/$useremail/7.jpg");
		unlink("imgs/$useremail/t.jpg");
		rmdir("imgs/$useremail");
		echo "<div class='notheader'>";
		echo "<p class='incorrect'>The email you provided does 
		not exist on our system.</p>";
		registerform("normal");
		echo "</div>";
	}
}
elseif (!isset($_GET['register']) || $_GET['register'] == "no"){
	echo "<div class='notheader'>";
	loginform("normal");
	echo "</div>";
}
elseif (isset($_GET['register']) && $_GET['register'] == "yes"){
	echo "<div class='notheader'>";
	registerform("normal");
	echo "</div>";
}
?>

