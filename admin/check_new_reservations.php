<?php
session_start();
require_once('../users/db.php');

try {
    // Get notifications from admin_notification table
    $query = "SELECT an.*, r.reservation_date, r.time_in, r.lab, u.fname, u.lname 
              FROM admin_notification an
              LEFT JOIN reservation r ON an.reservation_id = r.reservation_id
              LEFT JOIN user u ON r.id_number = u.id
              WHERE an.is_read = 0 
              ORDER BY an.created_at DESC";
              
    $result = $con->query($query);
    $notifications = array();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $notifications[] = array(
                'id' => $row['notification_id'],
                'student_name' => $row['fname'] . ' ' . $row['lname'],
                'message' => $row['message'],
                'type' => $row['type'],
                'lab' => $row['lab'],
                'date' => $row['reservation_date'],
                'time' => $row['time_in'],
                'created_at' => $row['created_at']
            );
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($notifications);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
