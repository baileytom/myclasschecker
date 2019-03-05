<?php
include("includes/secure.php");
$pgName="Other | myclasschecker";
include("includes/sessionheader.php");
?>

<h3>Other</h3>
<hr>
<small>You're logged in as <?php echo $_SESSION['email'] ?>!</small>
<br>
<small><a href="/changepassword.php">Change your password!<a></small>
<br>
<small><a href="/contact.php">Contact me!</a></small>


<?php
include("includes/footer.php")
?>