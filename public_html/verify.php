<?php session_start(); ?>

<?php
$pgName = 'myclasschecker';
include('includes/header.php');
?>


<?php

    include('includes/mysqllogin.php');

    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = sprintf("SELECT hash, id FROM Users WHERE email = '%s'", mysqli_real_escape_string($conn, $email));;
    $result = mysqli_query($conn, $sql);
    
    $hash = 0;
    $id = 0;

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $hash = $row['hash'];
            $id = $row['id'];
        }
    }

    if (password_verify($password, $hash)) {
        echo 'Logged in.';
        $_SESSION['is_auth'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $id;
        
        header( 'Location: home.php' );
        exit;
    } else {
        echo 'Login failed.';
        header( 'refresh:1;url=login.php' );
    }

    $conn-close();

?>

<?php include_once('includes/footer.php');?>
