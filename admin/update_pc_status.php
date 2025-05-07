<?php
require_once('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pc_number = $_POST['pc_number'] ?? null;
    $lab = $_POST['lab'] ?? null;
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;
    
    try {
        // Update or insert PC status
        $query = "INSERT INTO lab_pc (lab, pc_number, is_active, last_updated) 
                 VALUES (?, ?, ?, NOW())
                 ON DUPLICATE KEY UPDATE 
                 is_active = VALUES(is_active),
                 last_updated = VALUES(last_updated)";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("sii", $lab, $pc_number, $active);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'PC status updated successfully'
            ]);
        } else {
            throw new Exception("Error updating PC status");
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}