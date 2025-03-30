<?php
include('db.php');

if(isset($_POST['id'])) {
    $feedback_id = mysqli_real_escape_string($con, $_POST['id']);
    
    $query = "UPDATE feedback SET status = 'read' WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $feedback_id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}
?>
