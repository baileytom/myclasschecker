
<?php session_start();
include('includes/header.php');

include('includes/mysqllogin.php');

$email = $_POST['email'];
$token = $_POST['token'];

echo $token;

    $sql = sprintf("SELECT resetcode, id FROM Users WHERE email = '%s'", mysqli_real_escape_string($conn, $email));;
    $result = mysqli_query($conn, $sql);
    
    $resetcode = 0;
    $id = 0;

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $resetcode = $row['resetcode'];
            $id = $row['id'];
        }
    }

if (password_verify($token, $resetcode)) {
    echo 'Verified.';
    $_SESSION['is_auth'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $id;
    $_SESSION['is_reset'] = true;
    
    $sql = sprintf("UPDATE Users SET resetcode='%s' WHERE email = '%s'", NULL,  mysqli_real_escape_string($conn, $email));
    $conn->query($sql);

    header( 'Location: changepassword.php' );
    exit;
} else {
    echo 'Verification failed.';
    header( 'refresh:1;url=resetcode.php' );
}

$conn->close();

?>

<?php include_once('includes/footer.php');?>