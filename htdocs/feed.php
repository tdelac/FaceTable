<?php
include_once 'login.php';
include_once 'functions.php';

session_start();

$h1 = "Live Feed&nbsp&nbsp";
$tabs = array("Home", "Feed", "Profile", "Blog", "Find Others", "Logout");
$links = array("index.php", "feed.php", "profile.php", "bloghome.php", 
"findothers.php", "logout.php");
$ids = array("home", "feed", "profile", "blog", "find", "login");

head($h1, $tabs, $links, $ids, "loggedin");

if (!isset($_SESSION['prenom'])) die ("Please login to view this page");

echo <<<_END
<a class='twitter-timeline' href='https://twitter.com/FaceTable' data-widget-id='360455412300017664'>Tweets by @FaceTable</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
_END;

