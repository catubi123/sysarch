<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_POST['lab']) && isset($_POST['pc_number']) && isset($_POST['is_active'])) {
    $lab = $_POST['lab'];
    $pc = $_POST['pc_number'];
    $active = $_POST['is_active'] ? 1 : 0;

    $stmt = $con->prepare("INSERT INTO pc_status (lab_number, pc_number, is_active) 
                          VALUES (?, ?, ?) 
                          ON DUPLICATE KEY UPDATE is_active = ?");
    $stmt->bind_param("ssii", $lab, $pc, $active, $active);
    
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
