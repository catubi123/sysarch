<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $reservation_id = $_POST['reservation_id'];
    $points = 1; // Default point value
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First get the reservation details to get PC and lab info
        $get_details_sql = "SELECT pc_number, lab FROM reservation WHERE reservation_id = ?";
        $details_stmt = $conn->prepare($get_details_sql);
        $details_stmt->bind_param("i", $reservation_id);
        $details_stmt->execute();
        $result = $details_stmt->get_result();
        $reservation = $result->fetch_assoc();
        
        if ($reservation) {
            // 1. Update PC status to available
            $pc_sql = "UPDATE lab_pc 
                      SET is_active = 1, 
                          last_updated = NOW() 
                      WHERE lab = ? AND pc_number = ?";
            $pc_stmt = $conn->prepare($pc_sql);
            $pc_stmt->bind_param("si", $reservation['lab'], $reservation['pc_number']);
            $pc_stmt->execute();
            
            // 2. Add points to user
            $points_sql = "UPDATE user 
                          SET points = points + ?, 
                              remaining_sessions = remaining_sessions + ? 
                          WHERE id = ?";
            $points_stmt = $conn->prepare($points_sql);
            $points_stmt->bind_param("iii", $points, $points, $user_id);
            $points_stmt->execute();
            
            // 3. Update reservation status (complete session and mark points awarded)
            $update_sql = "UPDATE reservation 
                          SET status = 'completed',
                              points_awarded = 1,
                              actual_time_out = CURRENT_TIMESTAMP 
                          WHERE reservation_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $reservation_id);
            $update_stmt->execute();
            
            $conn->commit();
            $_SESSION['success'] = "Points awarded and session ended successfully";
        } else {
            throw new Exception("Reservation not found");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        error_log("Error in add_reservation_point: " . $e->getMessage());
    }
}

header("Location: sit-in.php?tab=reservations");
exit();
?>
