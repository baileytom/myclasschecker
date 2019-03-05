<?php
$pgName = "Verify | myclasschecker";
include("includes/header.php");
?>

<h3>Send verification code</h3>
<hr>
<form action='http://myclasschecker.com/resetpassword.php' method='post'>
  <!-- email field -->
  <div class='form-group'>
    <label for='emailInput'>Email address</label>
    <input type='email' name='email' class='form-control' id='emailInput' aria-describedby='emailHelp' placeholder='Enter email' required>
  </div>
  <button type='submit' class='btn btn-primary'>Send reset email</button>
</form>
<br>
<small>Or <a href="/resetcode.php">enter verification code.</a></small>
<?php
include("includes/footer.php");
?>
