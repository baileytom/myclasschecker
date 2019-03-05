<?php
$pgName = "Verify | myclasschecker";
include('includes/header.php');
?>

<h3>Enter verification code</h3>
<hr>
<form action='verifytoken.php' method='post'>
  <!-- email field -->
  <div class='form-group'>
    <label for='emailInput'>Email address</label>
    <input type='email' name='email' class='form-control' id='emailInput' aria-describedby='emailHelp' placeholder='Enter email' required>
  </div>
  <br>
  <!-- password field -->
  <div class='form-group'>
    <label for='passwordInput'>Verification code</label>
    <input type='text' name='token' class='form-control' id='passwordInput' placeholder='Verification code' required>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>
<br>
<small>
    Didn't receive the email? <a href='forgotpassword.php'>Send another code.</a>
</small>
<?php
include('includes/footer.php');
?>