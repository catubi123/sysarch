<?php
include('db.php');

if (isset($_POST['single']) && isset($_POST['id'])) {
    // Reset single student
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $query = "UPDATE user SET remaining_session = 30 WHERE id = '$id' AND role = 'user'";
} else {
    // Reset all students
    $query = "UPDATE user SET remaining_session = 30 WHERE role = 'user'";
}

if (mysqli_query($con, $query)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($con);
}

mysqli_close($con);
?>
