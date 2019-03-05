<?php include("includes/secure.php") ?>

<?php
$pgName = 'Home';
include('includes/sessionheader.php');
include('objects.php');
include('includes/mysqllogin.php');

$year = $_POST['year'];
$term = $_POST['term'];
$crn = $_POST['crn'];

$term_string = "Summer";

$url_year = $year;

if ($term == "10") {
    $term_string = "Fall";
    $url_year = $year + 1;
} else if ($term == "20") {
    $term_string = "Winter";
}

$term = $url_year.$term;

$term_string = $term_string." ".$year;


function isValidCourse($crn, $term) {
    $crn_length = strlen((string)$crn);
    if ($crn_length != 5) {
        return FALSE;
    }
    $html = file_get_contents('https://mybanner.gvsu.edu/PROD/bwckschd.p_disp_detail_sched?term_in='.$term.'&crn_in='.$crn);
    if (!strpos($html, "No detailed class information found")) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function getName($crn, $term) {
    $html = file_get_contents('https://mybanner.gvsu.edu/PROD/bwckschd.p_disp_detail_sched?term_in='.$term.'&crn_in='.$crn);
    $start_index=strpos($html, '"row" >');
    $end_index=strpos($html, '<br /><br />');
    $name = substr($html, $start_index+7, ($end_index-$start_index-12));
    return $name;
}

if (isValidCourse($crn, $term) AND isset($_POST['remove'])) {
    $sql = sprintf("SELECT id FROM Classes WHERE crn = '%s' AND term = '%s'", mysqli_real_escape_string($conn, $crn), mysqli_real_escape_string($conn, $term));
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $class_id = $row['id'];
    }
    $sql = sprintf("DELETE FROM Subscriptions WHERE user_id = '%s' AND class_id = '%s'", mysqli_real_escape_string($conn, $_SESSION['user_id']), mysqli_real_escape_string($conn, $class_id));
    $conn->query($sql);
    echo "Course removed.";
    } elseif (isset($_POST['remove'])) {
    echo "Course not found.";
}



if (isValidCourse($crn, $term) AND isset($_POST['add'])) {
    $duplicate = FALSE;
# First check if we already have the class 
    $sql = "SELECT crn, term FROM Classes";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        if ($crn == $row['crn'] AND $term == $row['term']) {
            $duplicate = TRUE;
        }
    }
# real quick lets get the name of the course
    # i hope nobody ever reads this code

    $name = getName($crn, $term);
# If we don't have the course add it to our databse
    if (!$duplicate) {
        $sql = sprintf("INSERT INTO Classes (name, term, crn, term_string) VALUES ('$name', '%s', '%s', '$term_string')", mysqli_real_escape_string($conn, $term), mysqli_real_escape_string($conn, $crn));
        $conn->query($sql);
    }
# Now add a subscription between the user and the course
     
# First check if the user is already subscribed to the course
    $sql = sprintf("SELECT id FROM Classes WHERE crn = '%s' AND term = '%s'", mysqli_real_escape_string($conn, $crn), mysqli_real_escape_string($conn, $term));
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $class_id = $row['id'];
    }
    $sql = sprintf("SELECT user_id, class_id FROM Subscriptions WHERE user_id = '%s' AND class_id = '%s'", mysqli_real_escape_string($conn, $_SESSION['user_id']), mysqli_real_escape_string($conn, $class_id));
    $result = mysqli_query($conn, $sql);
    $duplicate = FALSE;
    while($row = mysqli_fetch_assoc($result)) {
        if ($_SESSION['user_id'] == $row['user_id'] AND $class_id == $row['class_id']) {
            $duplicate = TRUE;
        }
    }


# if they arent then add a subscription for them
    if(!$duplicate) {
         $sql = sprintf("INSERT INTO Subscriptions (user_id, class_id) VALUES ('%s', '%s')", mysqli_real_escape_string($conn, $_SESSION['user_id']), mysqli_real_escape_string($conn, $class_id));
        $conn->query($sql);
        echo "Course added.";
     }

} elseif (isset($_POST['add'])) {
    echo "Course not found.";
}
unset($_POST['add']);
unset($_POST['remove']);
header('Location:home.php');
$conn->close();
?>

<?php include_once('includes/footer.php')?>