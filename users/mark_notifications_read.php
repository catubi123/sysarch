<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user_id = $_SESSION['id'];
$conn = openConnection();

$query = "UPDATE notification SET is_read = 1 WHERE id_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>
