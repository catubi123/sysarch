<?php
require_once('../admin/db.php');
header('Content-Type: application/json');

if(isset($_POST['pc_number']) && isset($_POST['lab']) && isset($_POST['is_active'])) {
    $pc = $_POST['pc_number'];
    $lab = $_POST['lab'];
    $active = (bool)$_POST['is_active'];

    // Update pc_status table
    $stmt = $con->prepare("INSERT INTO pc_status (lab_number, pc_number, is_active) 
                          VALUES (?, ?, ?) 
                          ON DUPLICATE KEY UPDATE is_active = ?");
    $activeInt = $active ? 1 : 0;
    $stmt->bind_param("siii", $lab, $pc, $activeInt, $activeInt);
    
    $success = $stmt->execute();
    
    if ($success) {
        // Clear any cached PC status
        if (isset($_SESSION['pc_status'])) {
            unset($_SESSION['pc_status']);
        }
    }
    
    echo json_encode([
        'success' => $success,
        'pc' => $pc,
        'lab' => $lab,
        'is_active' => $active
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
