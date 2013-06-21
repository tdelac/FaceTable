<?php
session_start();

if (isset($_SESSION['prenom'])){
	$first = $_SESSION['prenom'];
	$second = $_SESSION['surname'];
	echo <<<_END
<html>
<head>
<title>Welcome, $first</title>
<link rel="stylesheet" type="text/css" href="style/stylesheet.css" />
</head>
<body>
<h1>Blogs&nbsp&nbsp&nbsp&nbsp</h1>
<table>
<tr>
<td id="home">
<p>HOME</p>
</td>
<td id="profile">
<p>PROFILE</p>
</td>
<td id="find">
<p>FIND OTHERS</p>
</td>
</tr>
</table>
</body>
</html>
_END;
}

else {
	echo "<a href='login_register.php'>Please login or register 
	to view this page</a>";
}
?>
