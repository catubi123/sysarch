<?php
include('../users/db.php');

if(isset($_POST['id'])) {
    $feedback_id = (int)$_POST['id'];
    
    $query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $feedback_id);
    
    echo $stmt->execute() ? 'success' : 'error';
} else {
    echo 'invalid_request';
}
?>

