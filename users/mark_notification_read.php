<?php
session_start();
include('db.php');

if(isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']);
    
    $query = "UPDATE notification SET is_read = 1 
              WHERE notification_id = ? AND id_number = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $notification_id, $_SESSION['id']);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}
?>
