<?php
session_start();
require_once('../users/db.php');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$query = "UPDATE notification SET is_read = TRUE WHERE id_number = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
