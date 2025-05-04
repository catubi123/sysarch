<?php
session_start();
include('../users/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = (int)$_POST['id'];
    $status = $_POST['status'];
    $current_time = date('H:i:s');
    $current_date = date('Y-m-d');

    try {
        $con->begin_transaction();

        // Get reservation details
        $get_query = "SELECT * FROM reservation WHERE reservation_id = ?";
        $stmt = $con->prepare($get_query);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $reservation = $stmt->get_result()->fetch_assoc();

        if (!$reservation) {
            throw new Exception("Reservation not found");
        }

        // Update reservation status
        $update_query = "UPDATE reservation SET status = ? WHERE reservation_id = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("si", $status, $reservation_id);
        $update_stmt->execute();

        if ($status === 'approved') {
            // Create sit-in entry with pc_number
            $sit_query = "INSERT INTO student_sit_in (id_number, sit_purpose, sit_lab, pc_number, time_in, sit_date, status) 
                          VALUES (?, ?, ?, ?, ?, ?, 'Active')";
            $sit_stmt = $con->prepare($sit_query);
            $current_time = date('H:i:s');
            $current_date = date('Y-m-d');
            
            $sit_stmt->bind_param("ississ", 
                $reservation['id_number'],
                $reservation['purpose'],
                $reservation['lab'],
                $reservation['pc_number'],  // Include pc_number from reservation
                $current_time,
                $current_date
            );
            
            if (!$sit_stmt->execute()) {
                throw new Exception("Failed to create sit-in entry: " . $con->error);
            }

            // Create notification
            $notif_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
            $notif_stmt = $con->prepare($notif_query);
            $message = "Your reservation for Lab {$reservation['lab']} has been approved. You can now use PC #{$reservation['pc_number']}.";
            $notif_stmt->bind_param("is", $reservation['id_number'], $message);
            $notif_stmt->execute();
        }

        $con->commit();
        echo "success";
    } catch (Exception $e) {
        $con->rollback();
        echo "error: " . $e->getMessage();
    }
}
?>
