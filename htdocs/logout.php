<?php
include_once 'functions.php';

session_start();

$h1 = "FaceTable&nbsp&nbsp&nbsp";
$tabs = array("About", "Services", "Philosophy", "Blog", "Photos", 
"Connect", "Contact", "", "Login");
$links = array("index.php?tab=about", "index.php?tab=services", 
"index.php?tab=phil", "bloghome.php", "photos.php", "feed.php", 
"index.php?tab=contact", "index.php", "logout.php");
$ids = array("About", "Services", "Phil", "Blog", "Photos", "Connect", 
"Contact", "blank", "login");
head($h1, $tabs, $links, $ids, "home");

if (isset($_SESSION['prenom'])){
	destroySession();
	echo <<<_END
<div class='notheader'>
<h4>You have been logged out. Please <a href='index.php'>click here</a>
to refresh the screen</h4>
</div>
_END;
}
else {
	echo <<<_END
<div class='notheader'>
<h4>You are not logged in</h4>
</div>
_END;
}
