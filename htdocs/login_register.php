<html>
  <head>
    <title>Welcome to FaceTable!</title>
    <link rel="stylesheet" type="text/css" href="style/stylesheet.css" />
  </head>
  <body>
  <h1><? echo "FaceTable &nbsp&nbsp&nbsp&nbsp";?></h1>
  <table style="top: 43px;">
    <tr>
      <td id="About">
	<p><? echo "About";?></p>
      </td>
      <td id="ServicesO">
        <p><? echo "Services";?></p>
      </td>
      <td id="Menus">
        <p><? echo 'Menus&nbsp&&nbspTips';?></p>
      </td>
      <td id="Blog">
	<p><? echo "Blog";?></p>
      </td>
      <td id="PhotoA">
	<a href=photos.php><p><? echo "Photo&nbspAlbum";?></p></a>
      </td>
      <td id="Connect">
	<p><? echo "Connect";?></p>
      </td>
      <td id="ContactU">
	<p><? echo "Contact";?></p>
      </td>
    </tr>
  </table>
  </body>
</html>
<?php
require_once 'login.php';
include 'functions.php';
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
		$token = md5("$salt1$logPass$salt2");

		if ($token == $row[3]){
			session_start();
			ini_set('session.gc_maxlifetime', 60 * 60);
			$_SESSION['prenom'] = $row[0];
			$_SESSION['surname'] = $row[1];
			$_SESSION['email'] = $logEmail;
			echo "Welcome $row[0]! You are now logged in.";
			die ("<a href=index.php>Click here to continue</a>");
		}
		else die("Invalid username/password combination");
	}
	else die("Invalid username/password combination");
}
if (isset($_POST['regPrenom']) &&
	isset($_POST['regSurname']) &&
	isset($_POST['regEmail']) &&
	isset($_POST['regPass']) &&
	isset($_POST['passConfirm'])){

	$prenom = sanatize('regPrenom');
	$surname = sanatize('regSurname');
	$email = sanatize('regEmail');
	$_POST['regPass'] == $_POST['passConfirm'] ? 
		$pass = md5($salt1 . sanatize('regPass') . $salt2) : 
		die ("Confirm password does not match");
	$zip = isset($_POST['zip']) ? sanatize('zip') : "";

	$query = "INSERT INTO registration VALUES" .
			"('$prenom','$surname','$email','$pass','$zip')";
	if (!mysql_query($query, $db_server))
		echo "INSERT FAILED: $query<br />" .
		mysql_error();
	$sqlname = removePeriodsAndAt($email) . 'blogs';
	$query = "CREATE TABLE $sqlname (id MEDIUMINT NOT NULL AUTO_INCREMENT, time INT NOT NULL, 
		title VARCHAR(300) NOT NULL, PRIMARY KEY(id));";
	if (!mysql_query($query, $db_server))
		echo "INSERT FAILED: $query<br />" . 
		mysql_error();

	mkdir("imgs/$email", 0755);
}

echo <<<_END
<div id="loginform">
<form action="login_register.php" method="post"><pre>
E-Mail   <input type="text" name="logEmail" maxlength="35" />
Password <input type="password" name="logPass" maxlength="15" />
	 <input type="submit" value="Login" />
</pre></form></div>
<div id="registerform">
<form action="login_register.php" method="post"><pre>
First Name	   <input type="text" name="regPrenom" maxlength="20" />
Last Name 	   <input type="text" name="regSurname" maxlength="30" />
E-Mail    	   <input type="text" name="regEmail" maxlength="35" />
Password           <input type="password" name="regPass" maxlength="15" />
Confirm Password   <input type="password" name="passConfirm" maxlength="15" />
Zipcode (optional) <input type="text" name="zip" maxlength="9" />
                   <input type="submit" value="Register" />
</pre></form></div>
_END;
?>

