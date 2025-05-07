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
            // First, ensure the record exists
            $stmt = $con->prepare("INSERT INTO lab_pc (lab, pc_number, is_active) 
                                 VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE is_active = VALUES(is_active)");
            $active = $is_active ? 1 : 0;
            $stmt->bind_param("sii", $lab, $pc_number, $active);
            
            if ($stmt->execute()) {
                // Log the status change
                error_log("PC Status Updated - Lab: $lab, PC: $pc_number, Active: $active");
                
                // Also update pc_status table to keep both tables in sync
                $stmt2 = $con->prepare("INSERT INTO pc_status (lab_number, pc_number, is_active) 
                                      VALUES (?, ?, ?) 
                                      ON DUPLICATE KEY UPDATE is_active = VALUES(is_active)");
                $stmt2->bind_param("sii", $lab, $pc_number, $active);
                $stmt2->execute();
                
                $con->commit();
                echo json_encode([
                    'success' => true,
                    'pc_number' => $pc_number,
                    'lab' => $lab,
                    'is_active' => $active
                ]);
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