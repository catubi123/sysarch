<?php
include('db.php');

if(isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    
    // Delete the user
    $query = "DELETE FROM user WHERE id = '$id' AND role = 'user'";
    $result = mysqli_query($con, $query);
    
    if($result) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($con);
    }
} else {
    echo "error: No ID provided";
}
?>
