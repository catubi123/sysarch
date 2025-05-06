<?php
require_once('db.php');

header('Content-Type: application/json');

$lab = $_GET['lab'] ?? '';

if ($lab) {
    // Query to get all PCs status for the lab
    $query = "SELECT pc_number as number, is_active, last_updated 
              FROM pc_status 
              WHERE lab_number = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pcs = [];
    while ($row = $result->fetch_assoc()) {
        $pcs[] = [
            'number' => $row['number'],
            'is_active' => (bool)$row['is_active'],
            'last_updated' => $row['last_updated']
        ];
    }
    
    echo json_encode(['pcs' => $pcs]);
} else {
    echo json_encode(['error' => 'Missing lab parameter']);
}
