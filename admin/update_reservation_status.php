<?php
require_once('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['id'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($reservation_id && $status) {
        // Start transaction
        $con->begin_transaction();

        try {
            // Update reservation status
            $update_sql = "UPDATE reservation SET status = ? WHERE reservation_id = ?";
            $stmt = $con->prepare($update_sql);
            $stmt->bind_param("si", $status, $reservation_id);
            
            if ($stmt->execute()) {
                // If approved, start sit-in session
                if ($status === 'approved') {
                    // Get reservation details
                    $get_details = "SELECT r.* FROM reservation r WHERE r.reservation_id = ?";
                    $stmt = $con->prepare($get_details);
                    $stmt->bind_param("i", $reservation_id);
                    $stmt->execute();
                    $reservation = $stmt->get_result()->fetch_assoc();

                    if ($reservation) {
                        // Create sit-in entry
                        $create_sitin = "INSERT INTO student_sit_in (
                            id_number,
                            sit_purpose,
                            sit_lab,
                            pc_number,
                            time_in,
                            time_out,
                            sit_date,
                            status
                        ) VALUES (?, ?, ?, ?, ?, '', ?, 'active')";
                        
                        $stmt = $con->prepare($create_sitin);
                        $stmt->bind_param(
                            "isssss", 
                            $reservation['id_number'],
                            $reservation['purpose'],
                            $reservation['lab'],
                            $reservation['pc_number'],
                            $reservation['reservation_time'],
                            $reservation['reservation_date']
                        );
                        $stmt->execute();

                        // Update PC status
                        $update_pc = "INSERT INTO pc_status (pc_number, lab_number, is_active) 
                                    VALUES (?, ?, 0)
                                    ON DUPLICATE KEY UPDATE is_active = 0";
                        $stmt = $con->prepare($update_pc);
                        $stmt->bind_param("is", $reservation['pc_number'], $reservation['lab']);
                        $stmt->execute();
                    }
                }
                
                $con->commit();
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to update reservation status');
            }
        } catch (Exception $e) {
            $con->rollback();
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Database error occurred']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
