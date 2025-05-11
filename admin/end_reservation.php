<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id'])) {
    try {
        $conn->begin_transaction();
        
        $reservation_id = $_POST['reservation_id'];
        
        // First get the reservation details to get PC and lab info
        $get_details_sql = "SELECT pc_number, lab FROM reservation WHERE reservation_id = ?";
        $details_stmt = $conn->prepare($get_details_sql);
        $details_stmt->bind_param("i", $reservation_id);
        $details_stmt->execute();
        $result = $details_stmt->get_result();
        $reservation = $result->fetch_assoc();
        
        if ($reservation) {
            // Update PC status to available
            $pc_sql = "UPDATE lab_pc 
                      SET is_active = 1, 
                          last_updated = NOW() 
                      WHERE lab = ? AND pc_number = ?";
            $pc_stmt = $conn->prepare($pc_sql);
            $pc_stmt->bind_param("si", $reservation['lab'], $reservation['pc_number']);
            $pc_stmt->execute();
            
            // Update reservation status
            $sql = "UPDATE reservation 
                    SET status = 'completed',
                        actual_time_out = CURRENT_TIMESTAMP
                    WHERE reservation_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $reservation_id);
            
            if ($stmt->execute()) {
                $conn->commit();
                $_SESSION['success'] = "Reservation completed successfully";
            } else {
                throw new Exception("Error completing reservation");
            }
        } else {
            throw new Exception("Reservation not found");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        error_log("Error ending reservation: " . $e->getMessage());
    }
}

header("Location: sit-in.php?tab=reservations");
exit();
?>
