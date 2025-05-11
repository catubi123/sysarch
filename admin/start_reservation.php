<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    
    $conn->begin_transaction();
    
    try {
        // Debug output
        error_log("Starting reservation: " . $reservation_id);

        // Update reservation status to active
        $sql = "UPDATE reservation 
                SET status = 'active',
                    actual_time_in = CURRENT_TIMESTAMP,
                    approved_at = CURRENT_TIMESTAMP
                WHERE reservation_id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error activating reservation: " . $stmt->error);
        }

        error_log("Reservation updated successfully");
        
        $conn->commit();
        $_SESSION['success'] = "Reservation approved and activated successfully";
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error in start_reservation.php: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
    }
}

// Redirect with timer parameter to force fresh load
header("Location: sit-in.php?tab=reservations&t=" . time());
exit();
?>
