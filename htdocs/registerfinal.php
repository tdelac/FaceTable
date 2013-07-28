<?php
include_once 'functions.php';
$h1 = "FaceTable&nbsp&nbsp&nbsp";
$tabs = array("About", "Services", "Philosophy", "Blog", "Photos", 
"Connect", "Contact", "Home");
$links = array("index.php?tab=about", "index.php?tab=services", 
"index.php?tab=phil", "bloghome.php", "photos.php", "profile.php", 
"index.php?tab=contact", "index.php");
$ids = array("About", "Services", "Phil", "Blog", "Photos", "Connect", 
"Contact", "login");
head($h1, $tabs, $links, $ids, "home");

$email = $_GET['email'];

echo <<<_END
<div class='notheader'>
<p class='brochure1'><span class='brochuretext'>
Welcome to the beta test of <b>FaceTable.us</b>! Thank you 
for participating. 
</br>
</br>
This site is our attempt to realize the idea sharing and social 
networking that is central to our company philosophy. As a <b>FaceTable
</b> user you will be given a personal profile page, on which you may 
upload up to seven profile pictures (new images may be uploaded by clicking 
on the existing ones), write a personal bio, keep track of blogs, and 
maintain your own network of "guests" - our version of facebook's 
"friends". Once you have built your profile page, feel free to visit our 
main feed, updated live from our Twitter account. Here we will be posting 
relevant tips, articles, and thoughts related to food, politics, and 
philosophy. You can keep yourself up to date with what other <b>
FaceTable</b> users are thinking about by reading and/or following their 
blogs, or you can contribute to the discourse by starting blogs of your own.
</br>
</br>
Please keep in mind that this is a beta test. As such, this release 
contains the bare minimum of functions we have deemed necessary for the 
site to function properly. As we come out of the testing phase, 
no extra work will be required on your part - new updates will reach your 
account automatically. In the mean time, please send us feedback so that 
we can make future <b>FaceTable</b> experiences better!
</br>
</br>
As an added precaution in the propagation of this site while it is still 
in its testing phase, we ask that you provide the email address of the 
<b>FaceTable</b> user who referred you to us.</span>
<div class='register'>
<form action='login_register.php' method='post'>
<p class='form'><label>Referrer's email</label><input class=
'normal' type='text' name='refemail' maxlength='50' /></p>
<input type='hidden' name='useremail' value='$email' />
<p class='submit'><input class='submit' type='submit' value='Finish' /></p>
</form>
</div>
_END;
?>
