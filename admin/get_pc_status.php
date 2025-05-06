<?php
require_once('db.php');

header('Content-Type: application/json');

$lab = $_GET['lab'] ?? '';
$pc = $_GET['pc'] ?? '';

if ($lab && $pc) {
    $query = "SELECT is_active FROM pc_status WHERE lab_number = ? AND pc_number = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $lab, $pc);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['active' => (bool)$row['is_active']]);
    } else {
        echo json_encode(['active' => true]); // Default to available if no record
    }
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
