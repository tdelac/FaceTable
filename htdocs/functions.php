<?php
function sanatize($var){
	$string = $_POST[$var];
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	$string = mysql_real_escape_string($string);
	return htmlentities($string);
}
function showprofile($email, $url){
	echo "<div class='notheader'>";
	echo "<div class='imgs'>";
	if (file_exists("imgs/$email/1.jpg"))
		echo "<a href=$url?img=1><img class='sidebar' src='imgs/$email/1.jpg'></img></a>";
	if (isset($_GET['img'])){
		if ($_GET['img'] == 1)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/2.jpg"))
		echo "<img class='sidebar' src='imgs/$email/2.jpg'></img>";	
	if (isset($_GET['img'])){
		if ($_GET['img'] == 2)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/3.jpg"))
		echo "<img class='sidebar' src='imgs/$email/3.jpg'></img>";		
	if (isset($_GET['img'])){
		if ($_GET['img'] == 3)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/4.jpg"))
		echo "<img class='sidebar' src='imgs/$email/4.jpg'></img>";		
	if (isset($_GET['img'])){
		if ($_GET['img'] == 4)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/5.jpg"))
		echo "<img class='sidebar' src='imgs/$email/5.jpg'></img>";		
	if (isset($_GET['img'])){
		if ($_GET['img'] == 5)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/6.jpg"))
		echo "<img class='sidebar' src='imgs/$email/6.jpg'></img>";	
	if (isset($_GET['img'])){
		if ($_GET['img'] == 6)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}
	if (file_exists("imgs/$email/7.jpg"))
		echo "<img class='sidebar' src='imgs/$email/7.jpg'></img>";
	if (isset($_GET['img'])){
		if ($_GET['img'] == 7)
			echo <<<_END
<form action="$url" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit value="Upload" />
</form>
_END;
	}	
	echo "</div>";
	$result = mysql_query("SELECT * FROM profiles WHERE email='$email'");
	$row = mysql_fetch_row($result);
	$text = $row[1];
	$table = removePeriodsAndAt($email) . 'blogs';
	$result = mysql_query("SELECT * FROM $table ORDER BY id DESC"); 
	echo "<div class='body'>";
	echo "<h2><b>About me</b>";
	if ($email == $_SESSION['email'])
		echo "<a href='$url?edit=about'> (edit)</a></h2>";
	else echo "</h2>";
	echo "<p class='profile'>$text</p>";
	if (isset($_GET['edit'])){
		if ($_GET['edit'] == 'about'){
			echo <<<_END
<form action="profile.php" method="post"><pre>
<textarea name="text" cols="50" rows="7" wrap="hard">
$text
</textarea>
<input type="submit" value="SAVE CHANGES" />
</pre></form>
_END;
		}
	}
	echo "<h3><b>My blogs</b>";
	if (isset($_GET['blog'])){
		$numrows = mysql_num_rows($result);
		echo "<a href='$url'> (collapse)</a>";
	}
	else {
		$numrows = mysql_num_rows($result) > 2 ? 3 : mysql_num_rows($result);
		echo "<a href='$url?blog=viewall'> (view)</a>";
	}
	if ($email == $_SESSION['email']){
		echo "<a href='$url?edit=blogs'> (edit)</a>";
		echo "<a href='$url?new=blogs'> (New Blog)</a></h3>";
	}
	else echo "</h3>";
	if (isset($_GET['edit'])){
		if ($_GET['edit'] == 'blogs'){
			for ($i = mysql_num_rows($result); $i > 0; $i--){
				$row = mysql_fetch_row($result);
				echo "<p class='profile'>$row[2]
				<a href='$url?blogremove=$row[0]'> remove</a></p>";
				}
		}
	}
	if (isset($_GET['new'])){
		if ($_GET['new'] == 'blogs'){
			echo <<<_END
<form action='$url' method='post'>
Blog Title <input type='text' name='newtitle' maxlength='300' />
<input type='submit' value='CREATE' />
</form>
_END;
		}
	}
	for ($i = $numrows; $i > 0; $i--){
		$row = mysql_fetch_row($result);
		echo "<p class='profile'>$row[2]</p>";
	}
	if ($email == $_SESSION['email']){
		echo "<h4><b>Guest Requests</b>";
		if (isset($_GET['guestreq'])){
			echo "<a href='$url'> (Hide)</a></h4>";
			$myguests = removePeriodsAndAt($email) . "guests";
			$result = mysql_query("SELECT * FROM $myguests WHERE 
			status='false'");
			$numrows = mysql_num_rows($result);
			for ($i = 0; $i < $numrows; $i++){
				$row = mysql_fetch_row($result);
				$id = $row[0];
				$result = mysql_query("SELECT * FROM registration 
				WHERE id='$id'");
				$row = mysql_fetch_row($result);
				$prenom = $row[0];
				$surname = $row[1];
				echo "<p><a href='profile.php?user=$id'>$prenom 
				$surname</a> (<a href='$url?guestaccept=$id'>
				accept</a> | <a href='$url?guestdecline=$id'>
				decline</a>) ";
			}
			echo "</p>";
		}
		else {
			echo "<a href='$url?guestreq=view'> (View)</a></h4>";
		}
	}
	echo "<h5><b>Guests</b><a href='guestsearch.php?user=$email'> 
	(ViewAll)</a></h5>";
	$guesttable = removePeriodsAndAt($email) . "guests";
	$result = mysql_query("SELECT * FROM $guesttable WHERE status='true'
	ORDER BY RAND() LIMIT 7");
	for ($i = 0; $i < mysql_num_rows($result); $i++){
		if ($i % 3 == 0){
			echo "<p class='guestpics'>";
		}
		$row = mysql_fetch_row($result);
		$id = $row[0];
		$result1 = mysql_query("SELECT * FROM registration WHERE 
		id='$id'");
		$row = mysql_fetch_row($result1);
		$prenom = $row[0];
		$surname = $row[1];
		$email = $row[2];
		echo "<a href='profile.php?user=$id'><img class='guestpics' 
		src='imgs/$email/1.jpg'></a>";
		if ($i % 3 == 2){
			echo "</p>";
		}
	}
	echo "</p>";
	echo "</div>";
	echo "</div>";
}
function showblog($title, $author, $url){
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title'
	AND author='$author'");
	$row = mysql_fetch_row($result);
	$email = $row[5];
	$table = removePeriodsAndAt($email) . removeSpaces($title);
	$result = mysql_query("SELECT * FROM $table ORDER BY id DESC");
	echo <<<_END
<div class='notheader'>
<h2>$title</h2>
_END;
	$useremail = $_SESSION['email'];
		
	for ($i = 0; $i < mysql_num_rows($result); $i++){
		$row = mysql_fetch_row($result);
		$blog = $row[0];
		$id = $row[1];
		$editurl = $url . "&editid=$id";
		$removeurl = $url . "&removeid=$id";
		echo "<p class='blog'>$blog";
		if ($email == $useremail){
			echo "<span class='parens'> (<a href='$editurl'>edit
			</a>|<a href='$removeurl'>remove</a>)</span></p>";
		}
	}
	if ($email == $useremail){
			echo <<<_END
<div class='blogaction'>
<form action='$url' method='post'>
<input type='hidden' name='edittitle' value='blank' />
<input type='submit' value='EDIT' />
</form>
<form action='$url' method='post'>
<input type='hidden' name='post' value='blank' />
<input type='submit' value='NEW POST' />
</form>
</div>
_END;
	}
	else {
		echo "</p>";
		$followingtable = removePeriodsAndAt($useremail) . "following";
		$result = mysql_query("SELECT * FROM $followingtable
		WHERE title='$title' AND author='$author'");
		$row = mysql_fetch_row($result);
		if (empty($row)){
			$variable = "follow";
			$variable1 = "FOLLOW";
		}
		else {
			$variable = "unfollow";
			$variable1 = "UN-FOLLOW";
		}
		echo <<<_END
<div class='blogaction'>
<form action='$url' method='post>
<input type='hidden' name='$variable' value=$useremail />
<input type='sumbit' value='$variable1' />
</form>
</div>
_END;
	}
	echo "</div>";
}
function removePeriodsAndAt($string){
	$string = preg_replace('/\./','',$string);
	$string = preg_replace('/@/','',$string);
	return $string;
}
function removeSpaces($string){
	$string = str_replace(' ', '', $string);
	return $string;
}
?>
