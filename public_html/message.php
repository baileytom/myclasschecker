<?php include("includes/secure.php") ?>

<?php
$pgName = 'Contact';
include('includes/sessionheader.php');
include('includes/mysqllogin.php');

$name = mysqli_real_escape_string($conn, $_POST['name']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);



$sql = sprintf("INSERT INTO Comments (user, name, comment) VALUES ('%s', '%s', '%s')", $_SESSION['email'], $name, $comment);

$conn->query($sql);

echo "Comment submitted.";

header('refresh:1;url=home.php');

include('includes/footer.php')

?>

