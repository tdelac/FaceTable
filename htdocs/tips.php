<head>
  <title> Welcome to FaceTable! </title>
  <link rel="stylesheet" type="text/css" href="style/stylesheettips.css" />
</head>
<body>
<h1><? echo "FaceTable &nbsp&nbsp&nbsp&nbsp";?> </h1>
<table>
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
	 <p><? echo "Photo&nbspAlbum";?></p>
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
<?php
session_start();

if (isset($_SESSION['prenom'])){
require_once 'login.php';
include 'functions.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("unable to connect to MySql: " . mysql_error());

mysql_select_db('tips', $db_server) 
	or die("unable to select database: " . mysql_error());

$query = "SELECT * FROM categories";
$result = mysql_query($query);

if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);

$options = optiongen($result, $rows);

for ($i = 0 ; $i < $rows ; $i++){
	$cat   = mysql_result($result,$i,'category');
	$width = rand(20, 600) . "px";
	$height = rand(100, 250) . "px";
	echo <<<_END
<div style="position: absolute; left: $width;
top: $height; width: 25; height: 10">
<a href="tippages/$cat.php">$cat</a></div>
_END;
}

if (isset($_POST['title']) &&
	isset($_POST['tip']) &&
	(isset($_POST['existingcategory']) ||
	isset($_POST['newcategory']))){

	$title    = sanatize('title');
	$tip      = sanatize('tip');
	$name     = isset($_POST['name']) ? sanatize('name') : 'anonymous';
	$category = 'initialized';

	if ($_POST['existingcategory'] != 'none'){
		$category = $_POST['existingcategory'];
	}

	else {
		$category = sanatize('newcategory');
		$query    = "CREATE TABLE $category (
				title VARCHAR(50) NOT NULL,
				tip VARCHAR(65000) NOT NULL,
				name VARCHAR(25) NOT NULL,
				PRIMARY KEY (title)
			     )";
		$result   = mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());

		$query    = "INSERT INTO categories VALUES" .
			     "('$category')";
	 	$result   = mysql_query($query);
		if (!$result) die ("Failed to edit table: " . mysql_error());

		$fh = fopen("tippages/$category" . ".php", 'w') or die ("Failed to create file");
		$text =  
"<head>
<title>Welcome to FaceTable!</title>
<link rel='stylesheet' type='text/css' href='style/styletips.css' />
</head>
<body>";
		fwrite($fh, "$text") or die("Could not write to file");
		fclose($fh);
	}

	$query    = "INSERT INTO $category VALUES" .
			     "('$title', '$tip', '$name')";
	if (!mysql_query($query, $db_server))
		echo "INSERT failed: $query<br />" .
		mysql_error() . "<br /><br />";

	$fh = fopen("tippages/$category" . ".php", 'r+') or die("Failed to open file");
	$text = "<h1>$title</h1>
	      <p>$tip</p>
	      <div class='name'> Submitted By - $name </div>";
	fseek($fh, 0, SEEK_END);
	fwrite($fh, "$text") or die ("Could not write to file");
	fclose($fh);
}

echo <<<_END
<form action="tips.php" method="post"><pre>
Your name (optional) <input type="text" name="name" />
   Select a category <select name="existingcategory" size="1">
		     $options
		     </select>
 Or create your own! <input type="text" name="newcategory" />
           Tip title <input type="text" name="title" />
                     <textarea name="tip" cols="50" rows="7" wrap="hard">
 Type your tip here (please keep it concise)!
           	     </textarea>
          	     <input type="submit" value="POST TIP" />
</pre></form>
_END;
}

else {
	echo "Please <a href=login_register.php>login</a> or 
	<a href=login_register.php>register</a> to view this page.";
}

function alphabetize($result, $rows){
	$options = array();
	for ($i = 0; $i < $rows; $i++){
		array_push($options, mysql_result($result,$i,'category'));
	}
	$options = sort($options);
	return $options;
}

function optiongen($result, $rows){
	$options = alphabetize($result, $rows);
	$string = "<option value='none'>Select One</option>";
	for ($i = 0; $i < $rows; $i++){
		$cat = $options[$i];
		$string .= "<option value=$cat>$cat</option>";
	}
	return $string;
}
?>
