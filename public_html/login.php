<?php
$pgName = 'Log in | myclasschecker';
include('includes/header.php');
?>

<h3>Log in</h3>
<hr>
<form action='verify.php' method='post'>
  <!-- email field -->
  <div class='form-group'>
    <label for='emailInput'>Email address</label>
    <input type='email' name='email' class='form-control' id='emailInput' aria-describedby='emailHelp' placeholder='Enter email' required>
  </div>
  <br>
  <!-- password field -->
  <div class='form-group'>
    <label for='passwordInput'>Password</label>
    <input type='password' name='password' class='form-control' id='passwordInput' placeholder='Password' minlength='5' required>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>
<br>
<small>
Don't have an account? <a href='signup.php'>Sign up.</a>
</small>
<br>
<br>
<small>
Forgot your password? <a href='forgotpassword.php'>Reset password.</a>
</small>

<?php include_once('includes/footer.php') ?>
