<?php
include('../users/db.php');

if(isset($_POST['sit_id'])) {
    $sit_id = (int)$_POST['sit_id'];
    $current_time = date('h:i A');
    $status = 'Completed';

    // Update sit-in record with time out
    $query = "UPDATE student_sit_in 
             SET time_out = ?, status = ?, completed_at = NOW() 
             WHERE sit_id = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssi", $current_time, $status, $sit_id);
    
    if ($stmt->execute()) {
        // Notify user for feedback
        $notify_query = "INSERT INTO notifications (user_id, message, type) 
                        SELECT id_number, 'Please rate your recent lab session', 'feedback_required'
                        FROM student_sit_in WHERE sit_id = ?";
        $notify_stmt = $con->prepare($notify_query);
        $notify_stmt->bind_param("i", $sit_id);
        $notify_stmt->execute();
        
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }
} else {
    echo 'missing_id';
}
?>
