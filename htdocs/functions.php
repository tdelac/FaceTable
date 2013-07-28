<?php
function sanatize($var){
	$string = $_POST[$var];
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	$string = mysql_real_escape_string($string);
	return htmlentities($string);
}
function destroySession(){
	$_SESSION=array();

	if (session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-2592000, '/');

	session_destroy();
}
function loginform($class){
	echo <<<_END
<div class='login'>
<form class='loginreg' action='login_register.php' method='post'>
<p class='form'><label>Email</label><input class='$class' type='text' name='logEmail' maxlength='35' /></p>
<p class='form'><label>Password</label><input class='$class' type='password' name='logPass' maxlength='15' /></p>
<p class='submit'><input class='submit' type='submit' value='Login' /></p>
</form>
<a href='login_register.php?register=yes'>Not Registered?</a></div>
_END;
}
function registerform($class){
	echo <<<_END
<div class='register'>
<form action='login_register.php' method='post'>
<p class='form'><label>First Name</label><input class='normal' type='text' name='regPrenom' maxlength='20' /></p>
<p class='form'><label>Last name</label><input class='normal' type='text' name='regSurname' maxlength='30' /></p>
<p class='form'><label>Email</label><input class='$class' type='text' name='regEmail' maxlength='35' /></p>
<p class='form'><label>Password</label><input class='normal' type='password' name='regPass' maxlength='15' /></p>
<p class='form'><label>Confirm password</label><input class='normal' type='password' name='passConfirm' maxlength='15' /></p>
<p class='form'><label>Zipcode (optional)</label><input class='normal' type='text' name='zip' maxlength='9' /></p>
<p class='submit'><input class='submit' type='submit' value='Register' /></p>
</form>
<a href='login_register.php?register=no'>Back to Login</a></div>
_END;

}
function head($h1, $tabs, $links, $ids, $class){
	$first = "";
	if (isset($_SESSION['prenom'])){
		$first = ", " . $_SESSION['prenom'];
	}

	echo <<<_END
<html>
<head>
<title>Welcome$first</title>
<link rel="stylesheet" type="text/css" href="style/stylesheet.css" />
</head>
<body class='$class'>
<h1>$h1</h1>
<div class='header'>
_END;
	$i = 0;
	foreach ($tabs as $tab){
		$id = $ids[$i];
		$link = $links[$i];
		echo <<<_END
<span id="$id">
<a class='header' href='$link'>$tab</a>
</span>
_END;
		$i++;
	}

	echo <<<_END
</div>
</body>
</html>
_END;
}
function showprofile($pre, $sur, $email, $url){
	$h1 = "$pre $sur";
	$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
	$links = array("index.php", "feed.php", "profile.php", "bloghome.php",
	"findothers.php", "logout.php");
	$ids = array("home", "feed", "profile", "blog", "find", "login");
	head($h1, $tabs, $links, $ids, "loggedin");
	echo "<div class='imgs'>";
	if (file_exists("imgs/$email/1.jpg")){
		$imageurl = "imgs/$email/1.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar' 
			src='$imageurl'></a>";
			if ($_GET['img'] == 1){
				$newurl = $url . "?img=1";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=1><img class='sidebar' 
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/2.jpg")){
		$imageurl = "imgs/$email/2.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar'
			src='$imageurl'></a>";
			if ($_GET['img'] == 2){
				$newurl = $url . "?img=2";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=2><img class='sidebar'
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/3.jpg")){
		$imageurl = "imgs/$email/3.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar'
			src='$imageurl'></a>";
			if ($_GET['img'] == 3){
				$newurl = $url . "?img=3";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=3><img class='sidebar'
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/4.jpg")){
		$imageurl = "imgs/$email/4.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar' 
			src='$imageurl'></a>";
			if ($_GET['img'] == 4){
				$newurl = $url . "?img=4";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=4><img class='sidebar'
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/5.jpg")){
		$imageurl = "imgs/$email/5.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar' 
			src='$imageurl'></a>";
			if ($_GET['img'] == 5){
				$newurl = $url . "?img=5";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=5><img class='sidebar' 
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/6.jpg")){
		$imageurl = "imgs/$email/6.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar'
			src='$imageurl'></a>";
			if ($_GET['img'] == 6){
				$newurl = $url . "?img=6";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=6><img class='sidebar' 
			src='$imageurl'></a>";
	}
	if (file_exists("imgs/$email/7.jpg")){
		$imageurl = "imgs/$email/7.jpg?t=" . time();
		if (isset($_GET['img'])){
			echo "<a href=$url><img class='sidebar' 
			src='$imageurl'></a>";
			if ($_GET['img'] == 7){
				$newurl = $url . "?img=7";
				echo <<<_END
<form action="$newurl" method="post" enctype="multipart/form-data">
<input type="file" name="image" size="14" maxlength="32" />
<input type="submit" value="Upload" />
</form>
_END;
			}
		}
		else echo "<a href=$url?img=7><img class='sidebar' 
			src='$imageurl'></a>";
	}	
	echo "</div>";
	$result = mysql_query("SELECT * FROM profiles WHERE email='$email'");
	$row = mysql_fetch_row($result);
	$text = $row[1] == "" ? "Write about yourself!" : $row[1];
	$textedit = $row[1] == "" ? "" : $row[1];
	$finaltext = nl2br($textedit);
	$table = removePeriodsAndAt($email) . 'blogs';
	$result = mysql_query("SELECT * FROM $table ORDER BY id DESC"); 
	echo "<div class='body'>";
	echo "<div class='bodyelement'>";
	echo "<h2><b>About me</b>";
	if ($email == $_SESSION['email'])
		echo "<a class='getlink' href='$url?edit=about'> Edit</a></h2>";
	else echo "</h2>";
	echo "<p class='profile'>$finaltext</p>";
	if (isset($_GET['edit'])){
		if ($_GET['edit'] == 'about'){
			echo <<<_END
<form action="profile.php" method="post"><pre>
<textarea name="text" cols="60" rows="7" wrap="hard">
$text
</textarea>
<input class='submit' type="submit" value="SAVE CHANGES" />
</pre></form>
_END;
		}
	}
	echo "</div>";
	echo "<div class='bodyelement'>";
	echo "<h2><b>My blogs</b>";
	if ($email == $_SESSION['email']){
		if (isset($_GET['new'])){
			echo "<a class='getlink' href='$url'>&nbsp| New </a>";
		}
		else echo "<a class='getlink' href='$url?new=blogs'>&nbsp| New </a>";
		if (isset($_GET['edit'])){
			echo "<a class='getlink' href='$url'>&nbsp| Edit </a>";
		}
		else echo "<a class='getlink' href='$url?edit=blogs'>&nbsp| Edit </a>";
	}
	if (isset($_GET['blog'])){
		$numrows = mysql_num_rows($result);
		echo "<a class='getlink' href='$url'>Collapse</a></h2>";
	}
	else {
		$numrows = mysql_num_rows($result) > 2 ? 3 : mysql_num_rows($result);
		echo "<a class='getlink' href='$url?blog=viewall'>Viewall</a></h2>";
	}
	$result1 = mysql_query("SELECT * FROM registration WHERE email=
	'$email'");
	$row = mysql_fetch_row($result1);
	$prenom = $row[0];
	$surname = $row[1];
	$userid = $row[5];
	$blogauthor = $prenom . "_" . $surname;
	if (isset($_GET['edit'])){
		if ($_GET['edit'] == 'blogs'){
			for ($i = mysql_num_rows($result); $i > 0; $i--){
				$row = mysql_fetch_row($result);
				$time = $row[1];
				$date = date("F j, Y, g:i a", $time);
				echo "<p class='profile'><a class='blogs' href='
				blog.php?title=$row[2]&author=$blogauthor
				'> $row[2]</a><span class='timestamp'>&nbsp($date)</span>
				<a class='getlink' href='$url?blogremove=
				$row[0]'> Remove</a></p>";
				}
		}
	}
	else {
	if (isset($_GET['new'])){
		if ($_GET['new'] == 'blogs'){
			echo <<<_END
<form action='$url' method='post'>
<p><input class='normal' type='text' name='newtitle' value='New blog title'
maxlength='300' /></p>
<p><input class='submit' type='submit' value='CREATE' /></p>
</form>
_END;
		}
	}
	for ($i = $numrows; $i > 0; $i--){
		$row = mysql_fetch_row($result);
		$time = $row[1];
		$date = date("F j, Y, g:i a", $time);
		echo "<p class='profile'><a class='blogs' href=
		'blog.php?title=$row[2]&author=$blogauthor'>$row[2]</a>
		<span class='timestamp'>($date)</span></p>";
	}
	}
	echo "</div>";
	echo "<div class='bodyelement'>";
	echo "<h2><b>Blogs I'm following</b>";
	$followingtable = removePeriodsAndAt($email) . "following";
	$result = mysql_query("SELECT * FROM $followingtable ORDER BY 
	id DESC");
	$numrows = mysql_num_rows($result);
	if (isset($_GET['followview'])){
		echo "<a class='getlink' href='$url'>Collapse</a></h2>";
	}
	else {
		echo "<a class='getlink' href='$url?followview=yes'>Viewall
		</a></h2>";
		$numrows = $numrows > 2 ? 3 : $numrows;
	}
	for ($i = 0; $i < $numrows; $i++){
		$row = mysql_fetch_row($result);
		$author = str_replace(' ', '_', $row[0]);
		$title = str_replace(' ', '_', $row[1]);
		$time = $row[4];
		$date = date("F j, Y, g:i a", $time);
		if ($row[3] == 'true'){
			echo "<p class='profile'><a class='blogs' href='
			blog.php?title=$title&author=$author'>$row[1] </a>
			<span class='timestamp'>($date)</span></p>";
		}
		else {
			echo "<p class='profile'><a class='blogs' href='
			blog.php?title=$title&author=$author&bold=$userid'>
			<b>$row[1] </b></a><span class='timestamp'>($date)
			</span></p>";
		}
	}
	echo "</div>";
	if ($email == $_SESSION['email']){
		echo "<div class='bodyelement'>";
		echo "<h2><b>Guest requests</b>";
		if (isset($_GET['guestreq'])){
			echo "<a class='getlink' href='$url'>Hide</a></h2>";
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
				$guestemail = $row[2];
				echo "<div class='bodyelement3'><a href='profile.php?user=$id'>
				<img src='imgs/$guestemail/t.jpg'></a>
				<h3>$prenom $surname<a class='getlink' 
				href='$url?guestdecline=$id'>| decline</a>
				<a class='getlink' href='$url?guestaccept=
				$id'>accept&nbsp</a></h3></div>";
			}
		}
		else {
			echo "<a class='getlink' href='$url?guestreq=view'>View</a></h2>";
		}
		echo "</div>";
	}
	echo "<h2><b>Guests</b><a class='getlink' href='guestsearch.php?user=$userid'> 
	Search</a></h2>";
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
		src='imgs/$email/t.jpg'></a>";
		if ($i % 3 == 2){
			echo "</p>";
		}
	}
	echo "</p>";
	echo "</div>";
}
function showblog($title, $author, $url){
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title'
	AND author='$author'");
	$row = mysql_fetch_row($result);
	$email = $row[5];
    	$useremail = $_SESSION['email'];		
	$table = removePeriodsAndAt($email) . removeSpaces($title);
	$result = mysql_query("SELECT * FROM $table ORDER BY id DESC");
	echo <<<_END
<div class='bodyblog'>
<h4>$title</h4>
_END;
	for ($i = 0; $i < mysql_num_rows($result); $i++){
		$row = mysql_fetch_row($result);
		$blog = $row[0];
		$finalblog = nl2br($blog);
		$time = $row[2];
		$date = date("F j, Y, g:i a", $time);
		$id = $row[1];
		$editurl = $url . "&editid=$id";
		$removeurl = $url . "&removeid=$id";
		echo "<div class='bodyelement2'><p class='profile'>
		$finalblog</br></br><span class='timestamp'>
		&ensp;&ensp;- $date</span>";
		if ($email == $useremail){
			echo "<a class='getlink' href='$editurl'>&nbsp| edit
			</a><a class='getlink' href='$removeurl'>remove</a></p>";
		}
		echo "</div>";
	}
	echo "</div>";
	if ($email == $useremail){
			echo <<<_END
<div class='blogactions'>
<form action='$url' method='post'>
<input type='hidden' name='edittitle' value='blank' />
<input class='submit' type='submit' value='EDIT' />
</form>
<form action='$url' method='post'>
<input type='hidden' name='post' value='blank' />
<input class='submit' type='submit' value='NEW POST' />
</form>
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
<div class='blogactions'>
<form action='$url' method='post'>
<input type='hidden' name='$variable' value='$useremail' />
<input class='submit' type='submit' value='$variable1' />
</form>
_END;
	}
	$result = mysql_query("SELECT * FROM allblogs WHERE title='$title' 
	AND author='$author'");
	$row = mysql_fetch_row($result);
	$count = $row[3];
	$word = $count == 1 ? "is" : "are";
	$table = removePeriodsAndAt($email) . "blogs";
	$result = mysql_query("SELECT * FROM $table WHERE title='$title'");
	$row = mysql_fetch_row($result);
	$string = substr($row[3], 1);
	$followersarray = explode(",", $string);
	echo <<<_END
<h2>$count $word following</h2>
_END;
	foreach ($followersarray as $followers){
		$result = mysql_query("SELECT * FROM registration WHERE 
		email='$followers'");
		$row = mysql_fetch_row($result);
		$id = $row[5];
		echo <<<_END
<a href='profile.php?user=$id'><img src='imgs/$followers/t.jpg'></a>
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
