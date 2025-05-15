
if($status === 'approved') {
    // Create notification
    $notification_message = "Your reservation for Lab {$lab_number} on " . date('M d, Y', strtotime($reservation_date)) . 
                          " at " . date('g:i A', strtotime($time_in)) . " has been approved.";
    
    $insert_notif = "INSERT INTO notification (id_number, message, type, created_at) 
                     VALUES (?, ?, 'success', NOW())";
    $notif_stmt = $con->prepare($insert_notif);
    $notif_stmt->bind_param("is", $user_id, $notification_message);
    $notif_stmt->execute();
}
