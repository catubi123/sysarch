<?php
session_start();
require_once('../users/db.php');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$notification_id = $_POST['notification_id'] ?? null;

if ($notification_id) {
    $query = "UPDATE notification SET is_read = TRUE WHERE notification_id = ? AND id_number = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $notification_id, $_SESSION['id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid notification ID']);
}
