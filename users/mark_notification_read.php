<?php
session_start();
require_once('db.php');

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if(isset($_POST['notification_id']) && isset($_SESSION['id'])) {
    $notification_id = intval($_POST['notification_id']);
    
    // Delete the notification instead of marking as read since there's no is_read column
    $query = "DELETE FROM notification WHERE notification_id = ? AND id_number = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $notification_id, $_SESSION['id']);
    
    if($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Notification removed'
        ];
    } else {
        $response['message'] = 'Failed to remove notification';
    }
} else {
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
?>
