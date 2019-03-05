<?php
include("includes/secure.php");
$pgName="Contact | myclasschecker";
include("includes/sessionheader.php");
?>

<p>Use this form to report bugs, request support/features, or for any feedback you want to leave.</p> 
<hr>

<form action='message.php' method='post'>
  <div class='form-group'>
    <label for='name'>Subject</label>
    <input type='name' name='name' class='form-control' id='name' placeholder='Subject'>
  </div>
  <br>
  <div class='form-group'>
    <label for='comment'>Message</label>
    <textarea class='form-control' name='comment' rows='5' id='comment' required></textarea>
  </div>
  <button type='submit' class='btn btn-primary'>Submit</button>
</form>


<?php
include("includes/footer.php")
?>