<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Get reservation details first
        $get_reservation = "SELECT r.id_number, r.reservation_date, r.reservation_time, r.lab 
                          FROM reservation r 
                          WHERE r.reservation_id = ?";
        $stmt = $con->prepare($get_reservation);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();

        // Update reservation status
        $update_query = "UPDATE reservation SET status = ? WHERE reservation_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        // Create notification message
        $message = $status === 'approved' 
            ? "Your reservation for Lab {$reservation['lab']} on {$reservation['reservation_date']} at {$reservation['reservation_time']} has been approved."
            : "Your reservation for Lab {$reservation['lab']} on {$reservation['reservation_date']} at {$reservation['reservation_time']} has been rejected.";

        // Insert notification
        $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
        $stmt = $con->prepare($notify_query);
        $stmt->bind_param("is", $reservation['id_number'], $message);
        $stmt->execute();

        // Commit transaction
        $con->commit();
        echo "success";
        
    } catch (Exception $e) {
        // Rollback on error
        $con->rollback();
        echo "error: " . $e->getMessage();
    }
} else {
    echo "invalid request";
}
?>
