<?php
$pgName = 'Sign up';
include('includes/header.php');
?>

  <?php
    include('includes/mysqllogin.php');
    $email = $_POST['email'];
    $hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $duplicate = FALSE;
    $sql = "SELECT email FROM Users";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        if($email == $row['email']) {
            $duplicate = TRUE;
        }
    }
    if($duplicate) { echo "Email already used."; header('refresh:1;url=signup.php'); }
    else {
    $sql = sprintf("INSERT INTO Users (email, hash) VALUES ('%s', '%s')", mysqli_real_escape_string($conn,$email), mysqli_real_escape_string($conn,$hash_pass) );

    if ($conn->query($sql) === TRUE) {
        echo '<p>Account created.</p>';
        $sql = sprintf("INSERT INTO EmailQueue (recipient) VALUES ('%s')", mysqli_real_escape_string($conn,$email));
        $conn->query($sql);
    } else {
        echo 'Error: '.$sql.'<br>'.$conn->error;
    }
    $conn->close();
    header( 'refresh:1;url=login.php' ); }
  ?>

<?php include_once('includes/footer.php');?>

