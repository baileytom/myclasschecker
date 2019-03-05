<?php
include("includes/secure.php");
$pgName = "Change password";
include("includes/sessionheader.php");
include("includes/mysqllogin.php");

$email = $_SESSION['email'];

if (isset($_SESSION['is_reset'])) {
    unset($_SESSION['is_reset']);
    
} else {
    $sql = sprintf("SELECT hash, id FROM Users WHERE email = '%s'", mysqli_real_escape_string($conn, $email));
    $result = mysqli_query($conn, $sql);
    
    $hash = 0;
    $id = 0;

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $hash = $row['hash'];
            $id = $row['id'];
        }
    }

    if (!password_verify($_POST['oldpassword'], $hash)) {
        echo "Incorrect password.";
        header('refresh:1;url=changepassword.php');
    }
}

$hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = sprintf("UPDATE Users SET hash='%s' WHERE email = '%s'", mysqli_real_escape_string($conn, $hash_pass), mysqli_real_escape_string($conn, $email));
$conn->query($sql);

echo "Password changed.";
header('refresh:1;url=home.php');

$conn->close();

include_once('includes/footer.php');

?>