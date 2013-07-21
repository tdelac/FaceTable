<?php
session_start();
require_once 'functions.php';
if (isset($_SESSION['prenom'])){
	$name = $_SESSION['prenom'];
	$login = "Welcome, $name!";
	$loginlink = "logout.php";
	$loginid = "welcome";
}
else {
	$login = "Login";
	$loginlink = "login_register.php";
	$loginid = "login";
}
$h1 = "FaceTable&nbsp&nbsp&nbsp";
$tabs = array("About", "Services", "Philosophy", "Blog", "Photos", 
"Connect", "Contact", "", $login);
$links = array("index.php?tab=about", "index.php?tab=services",
"index.php?tab=phil", "bloghome.php", "photos.php", "profile.php", 
"index.php?tab=contact", "index.php", $loginlink);
$ids = array("About", "Services", "Phil", "Blog", "Photos", "Connect", 
"Contact", "blank", $loginid);
head($h1, $tabs, $links, $ids);

if (isset($_GET['tab'])){
	if ($_GET['tab'] == 'about'){
		echo <<<_END
<div class='notheader'>
<p class='brochure1'><b>FaceTable</b><span class='brochuretext'>
is a skills acquisition service, imparting culinary know-how
</span>
</br>
</br>
<b>FaceTable</b><span class='brochuretext'>
is an organizational facilitator, offering family meal and dinner-
party preparation support
</span>
</br>
</br>
<b>FaceTable</b><span class='brochuretext'>
is a self-generating movement, helping to promote socializing and
the sharing of ideas at dinner tables across the Washington DC area
</span>
</br>
</br>
<b>FaceTable</b><span class='brochuretext'>
services include
</span>
</br>
<ul>
<li>&emsp;&emsp;&emsp;&emsp;Creative, simplified menus design</li>
<li>&emsp;&emsp;&emsp;&emsp;Logistics guidance</li>
<li>&emsp;&emsp;&emsp;&emsp;Shopping and kitchen set-up</li>
<li>&emsp;&emsp;&emsp;&emsp;Instruction and help with dinner courses</li>
<li>&emsp;&emsp;&emsp;&emsp;Idea sharing... social networking and blogging</li>
</ul>
<b>FaceTable</b><span class='brochuretext'>
stands for the subtle force of food to bridge divides and promote 
livelier lives. What you cook, and the memories you create at the
dinner table, become part of who you are. We hope to empower people 
with the skills and support services necessary to make the sharing of
meals and ideas a less stressful and more common, fulfilling experience.
</span>
</p>
</div>
_END;
	}
	elseif ($_GET['tab'] == 'services'){
		echo <<<_END
<div class='notheader'>
<p class='brochure1'>
<b>FaceTable</b><span class='brochuretext'>
services include
</span>
</br>
<ul>
<li>&emsp;&emsp;&emsp;&emsp;Creative, simplified menus design</li>
<li>&emsp;&emsp;&emsp;&emsp;Logistics guidance</li>
<li>&emsp;&emsp;&emsp;&emsp;Shopping and kitchen set-up</li>
<li>&emsp;&emsp;&emsp;&emsp;Instruction and help with dinner courses</li>
<li>&emsp;&emsp;&emsp;&emsp;Idea sharing... social networking and blogging</li>
</ul>
<b>FaceTable</b><span class='brochuretext'>
invites you to order a la carte the level of assistance provided:
</span>
</br>
</br>
<b>Creative, simplified menu design:</b><span class='brochuretext'>
We can work with you on an individual basis to help you create a 
menu that reflects your skill level, time availability and food
culture preferences
</span>
</br>
</br>
<b>Logistics guidance:</b><span class='brochuretext'>
We can propose a time plan for you that can accommodate your work 
and household schedule
</span>
</br>
</br>
<b>Shopping and kitchen set-up:</b><span class='brochuretext'>
We can shop for and deliver the menu ingredients, as well as provide 
assistance in preparing ingredients and cookware so that you are
ready to begin
</span>
</br>
</br>
<b>Instruction and help with dinner courses:</b><span class='brochuretext'>
We can offer cooking instructions and tips, as well as supervisory 
help with course preparation
</span>
</br>
</br>
<b>Idea sharing... Social networking and blogging:</b><span class='brochuretext'>
We offer blog space for members, who wish to share bold ideas about food
, politics, world events and more, as well as tasteful commentary about
 dinner parties attended. You may elect to become a member of a growing
 community of dinner party hosts and attendees by joining our network
</span>
</br>
</br>
<b>FaceTable</b><span class='brochuretext'>
pricing is set through means-adjusted quotes.
</br>
</br>
All are welcome to follow us on Twitter:
</span>
</p>
_END;
echo "<a href='https://twitter.com/FaceTable' class='twitter-follow-button' data-show-count='false' data-size='large'>Follow @FaceTable</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
</div>";

	}
	elseif ($_GET['tab'] == 'contact'){
		echo <<<_END
<div class='notheader'>
<p class='brochure1'>
Let us help you create your table!
</br>
</br>
<span class='brochuretext'>&emsp;&emsp;Inquiries:&emsp;</span>
<a href='mailto:facetable@verizon.net'><b>FaceTable</b>
<span class='brochuretext'>@verizon.net</a>
</span></p></div>";
_END;
	}
	elseif ($_GET['tab'] == 'phil'){
		echo <<<_END
<div class='notheader'>
<p class='brochure1'>
<b>Who we are</b></br></br><span class='brochuretext'>
A few years ago, our son came home from high school and recounted a 
world history lesson he had attended that day. The lesson starts with 
a puzzle: why has the political climate in the United States become so 
polarized? One theory of politics suggests that political agendas in 
two-party systems converge over time as both parties compete for votes 
at the center. Although many seemingly sophisticated theories could 
well be advanced to explain how the contrary result has been obtained, 
our son's teacher had a sensible idea: a few decades back, Washington 
had been another place, where congressmen had moved their families to 
reside in the nation's capitol, their workplace. Families then settled 
into Washington life and socialize, sharing meals and drink with colleagues 
and the families of colleagues: Republicans and Democrats mingled during 
meals. Today, by contrast, Washington has developed a community of 
commuter politicians, severing the crucial link between work and social 
life, so important to the sharing of ideas and perspectives.
</br>
</br>
We at <b>FaceTable</b> seek to facilitate meal and idea sharing, 
whether among family at home, or between friends, colleagues and 
acquaintances. As a family that most typically votes for the democratic 
party, we would like to pitch our commitment to the philosophy of 
<b>FaceTable</b> by citing a quote from Ronald Reagan's farewell speech 
in 1988: <i>All great change in America begins at the dinner table.</i>
Maybe Washington will take note.
</br>
</br>
<b>FaceTable</b> is a family effort, created by Heidi Hiebert. She 
holds a doctorate in international relations from American University, 
a Master's in international economics from the Graduate Institute 
of International Studies in Geneva, Switzerland and a Bachelor's in 
economics from the University of California at Los Angeles. She is 
supported by her husband, Olivier Delacour, an MIT-trained architect, 
son Thomas, a student of computer science and math at the University 
of Pennsylvania and daughter Tasha, a sophomore at Walt Whitman High 
School.
</span>
</p>
</div>
_END;
	}
}
?>
