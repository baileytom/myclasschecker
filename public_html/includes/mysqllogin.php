<?php
    $servername = 'localhost';
    $username = 'classcheckerapp';
    $password = 'eNMKt7vHQRl5z2bI';
    $dbname = 'my_data';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
?>