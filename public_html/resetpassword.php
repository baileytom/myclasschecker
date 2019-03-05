<?php

include('includes/header.php');

include('includes/mysqllogin.php');

# Stuff i copied

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}


$email = $_POST['email'];

$sql = sprintf("SELECT id FROM Users WHERE email = '%s'", mysqli_real_escape_string($conn, $_POST['email']));

$result = mysqli_query($conn, $sql);

if($result->num_rows == 0) {
    # The email isn't in the database. But we cant tell them so do nothing.
    echo "Email sent.";
} else {
    # Generate reset token
    $reset_token = getToken(8);
    # Store hashed reset token in database
    $reset_hash = password_hash($reset_token, PASSWORD_DEFAULT);
    $sql = sprintf("UPDATE Users SET resetcode='%s' WHERE email = '%s'", $reset_hash,  mysqli_real_escape_string($conn, $email));
    if (mysqli_query($conn, $sql)) {
        echo 'Email sent.';
    } else {
        #echo 'Didnt work';
    }


    # First delete any password reset codes in the queue with the same email
    $sql = sprintf("DELETE FROM EmailQueue WHERE recipient = '%s' AND passwordreset = 1", mysqli_real_escape_string($conn, $email));

    $conn->query($sql);

    # Insert reset email into email queue
    $sql = sprintf("INSERT INTO EmailQueue (recipient, passwordreset, token) VALUES ('%s', '%s', '%s')", mysqli_real_escape_string($conn,$email), TRUE, $reset_token);
    $conn->query($sql);
    }

$conn->close();
header('refresh:1;url=resetcode.php');
?>

<?php
include('includes/footer.php')
?>
    