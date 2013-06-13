<html>
  <head>
    <title>Welcome to FaceTable!</title>
    <link rel="stylesheet" type="text/css" href="style/stylesheet.css" />
  </head>
  <body>
  <h1><? echo "FaceTable &nbsp&nbsp&nbsp&nbsp";?></h1>
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
      <td id="blank">
	<p><? echo "";?></p>
      </td>
    </tr>
  </table>
  </body>
</html>

<?php
session_start();

if (isset($_SESSION['prenom'])){
	$name = $_SESSION['prenom'];
	echo "<div id='welcome'>Welcome $name!</div>";
}

else {
	echo "<div id='login'>
	<a href=login_register.php><p>Login</p></a>
        </div>";
}
?>
