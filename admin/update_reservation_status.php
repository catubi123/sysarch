<?php
session_start();
include('db.php');
$conn = openConnection();

header('Content-Type: application/json');
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn->begin_transaction();
        
        $id = $_POST['id'];
        $status = $_POST['status'];
        $pc_number = $_POST['pc_number'] ?? null;
        $lab = $_POST['lab'] ?? null;
        
        if ($status === 'approved') {
            // First update the PC status to mark it as in use
            if ($pc_number && $lab) {
                $pc_sql = "INSERT INTO lab_pc (lab, pc_number, is_active, last_updated) 
                          VALUES (?, ?, 0, NOW())
                          ON DUPLICATE KEY UPDATE 
                          is_active = 0,
                          last_updated = NOW()";
                
                $pc_stmt = $conn->prepare($pc_sql);
                $pc_stmt->bind_param("si", $lab, $pc_number);
                $pc_stmt->execute();
            }
            
            // Then update the reservation status
            $sql = "UPDATE reservation 
                    SET status = 'active',
                        actual_time_in = CURRENT_TIMESTAMP
                    WHERE reservation_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $conn->commit();
                $response['success'] = true;
                $response['message'] = 'Reservation approved successfully';
            } else {
                throw new Exception("Failed to update reservation status");
            }
        } else if ($status === 'rejected') {
            $sql = "UPDATE reservation SET status = 'rejected' WHERE reservation_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $conn->commit();
                $response['success'] = true;
                $response['message'] = 'Reservation rejected successfully';
            } else {
                throw new Exception("Failed to update reservation status");
            }
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        $response['error'] = $e->getMessage();
        error_log("Error updating reservation: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit();
}
?>
