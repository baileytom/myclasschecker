<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "classcheckerapp", "eNMKt7vHQRl5z2bI", "classchecker");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$term = mysqli_real_escape_string($link, $_REQUEST['term']);

echo "<ul class='list-group'>";
 
if(isset($term)){
    // Attempt select query execution
    $sql = "SELECT * FROM Courses WHERE name LIKE '" . $term . "%'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo '<li class="list-group-item">';
                #echo "<p>" . $row['crn'] . " " . $row['semester'] . " " . $row['year'] . " " . $row['name'] . " " . $row['day'] . " " .$row['time'] . "</p>";
                $_result = "%s</br>%s</br>%s, %s</br> %s, %s</br>";
                echo "".sprintf($_result, $row['name'], $row['professor'], $row['day'], $row['time'], $row['crn'], $row['semester']." ".$row['year'])."";
                echo '</li>';
            }
            // Close result set
            mysqli_free_result($result);
        } else{
            echo "<p>No matches found</p>";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
 
// close connection
mysqli_close($link);
?>