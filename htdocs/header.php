<?php
session_start();

if (isset($_SESSION['prenom'])){
	echo "<a href='index.php'>Home</a>
	      <a href='profile.php'>Profile</a>
	      <a href='membersearch.php'>Find Others</a>
	      <a href='logout.php'>Logout</a>";
}

else {
	echo "<a href='login_register.php'>Please login or register 
	to view this page</a>";
}
?>
