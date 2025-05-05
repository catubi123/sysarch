<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_POST['pc_number']) && isset($_POST['lab'])) {
    $pc = $_POST['pc_number'];
    $lab = $_POST['lab'];
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 0;

    // First, ensure PC exists in pc_numbers
    $stmt = $con->prepare("INSERT IGNORE INTO pc_numbers (lab_number, pc_number) VALUES (?, ?)");
    $stmt->bind_param("si", $lab, $pc);
    $stmt->execute();

    // Then update status
    $stmt = $con->prepare("INSERT INTO pc_status (lab_number, pc_number, is_active) 
                          VALUES (?, ?, ?) 
                          ON DUPLICATE KEY UPDATE is_active = ?");
    $stmt->bind_param("siii", $lab, $pc, $active, $active);
    
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
?>
