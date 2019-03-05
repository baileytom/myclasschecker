<?php
include("includes/secure.php");
$pgName = "Change password | myclasschecker";
include("includes/sessionheader.php");
?>
<h3>Update password</h3>
<hr>
<?php

if(isset($_SESSION['is_reset'])) { ?>

<form action='update.php' method='post'>
  <!-- password field -->
  <div class='form-group'>
    <label for='passwordInput'>New password</label>
    <input type='password' name='password' class='form-control' id='passwordInput' placeholder='Password' minlength='5' required>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>

<?php } else { ?>

<form action='update.php' method='post'>
  <!-- email field -->
  <div class='form-group'>
    <label for='currentPassword'>Old password</label>
    <input type='password' name='oldpassword' class='form-control' id='currentPassword' placeholder='Old password' minlength='5' required>
  </div>
  <!-- password field -->
  <div class='form-group'>
    <label for='newPassword'>New password</label>
    <input type='password' name='password' class='form-control' id='newPassword' placeholder='New password' minlength='5' required>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>
<?php
}

include("includes/footer.php");

?>