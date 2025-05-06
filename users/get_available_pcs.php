<?php
require_once('db.php');

header('Content-Type: application/json');

$lab = $_GET['lab'] ?? '';

if ($lab) {
    // Get all PCs that are marked as not available
    $query = "SELECT pc_number FROM pc_status WHERE lab_number = ? AND is_active = 0";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $unavailable_pcs = [];
    while ($row = $result->fetch_assoc()) {
        $unavailable_pcs[] = $row['pc_number'];
    }
    
    echo json_encode([
        'lab' => $lab,
        'unavailable_pcs' => $unavailable_pcs
    ]);
} else {
    echo json_encode(['error' => 'Missing lab parameter']);
}
