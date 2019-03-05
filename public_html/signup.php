<?php
$pgName = 'Sign up | myclasschecker';
include('includes/header.php');
?>
 
<h3>Sign up</h3>
<hr>
<form action='http://myclasschecker.com/welcome.php' method='post'>
  <!-- email field -->
  <div class='form-group'>
    <label for='emailInput'>Email address</label>
    <input type='email' name='email' class='form-control' id='emailInput' aria-describedby='emailHelp' placeholder='Enter email' required>
    <small id='emailHelp' class='form-text text-muted'></small><br>
  </div>
  <!-- password field -->
  <div class='form-group'>
    <label for='passwordInput'>Password</label>
    <input type='password' name='password' class='form-control' id='passwordInput' placeholder='Password' minlength='5' required>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>
<br>
<small>
  Already have an account? <a href='login.php'>Login.</a>
</small>

<?php include_once('includes/footer.php') ?>    
