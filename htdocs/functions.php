<?php
function sanatize($var){
	$string = $_POST[$var];
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	$string = mysql_real_escape_string($string);
	return htmlentities($string);
}
function showprofile($email){
	echo "<div class='imgs'>";
	if (file_exists("imgs/$email/1.jpg"))
		echo "<img class='sidebar' src='imgs/$email/1.jpg'>";
	if (file_exists("imgs/$email/2.jpg"))
		echo "<img class='sidebar' src='imgs/$email/2.jpg'>";	
	if (file_exists("imgs/$email/3.jpg"))
		echo "<img class='sidebar' src='imgs/$email/3.jpg'>";	
	if (file_exists("imgs/$email/4.jpg"))
		echo "<img class='sidebar' src='imgs/$email/4.jpg'>";	
	if (file_exists("imgs/$email/5.jpg"))
		echo "<img class='sidebar' src='imgs/$email/5.jpg'>";	
	if (file_exists("imgs/$email/6.jpg"))
		echo "<img class='sidebar' src='imgs/$email/6.jpg'>";	
	if (file_exists("imgs/$email/7.jpg"))
		echo "<img class='sidebar' src='imgs/$email/7.jpg'>";
	echo "</div>";
}
?>
