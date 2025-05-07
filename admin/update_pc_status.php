<?php
require_once('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pc_number = $_POST['pc_number'] ?? null;
    $lab = $_POST['lab'] ?? null;
    $is_active = $_POST['active'] ?? null;

    if ($pc_number && $lab !== null && $is_active !== null) {
        $con->begin_transaction();
        
        try {
            // Update PC status
            $stmt = $con->prepare("INSERT INTO lab_pc (lab, pc_number, is_active) 
                                 VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE is_active = ?");
            $active = $is_active ? 1 : 0;
            $stmt->bind_param("siii", $lab, $pc_number, $active, $active);
            
            if ($stmt->execute()) {
                $con->commit();
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Failed to update PC status");
            }
        } catch (Exception $e) {
            $con->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
