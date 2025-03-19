<?php
require_once __DIR__ . '/admin/db.php';

function hasActiveSitIn($userId) {
    $conn = openConnection();
    
    $query = "SELECT COUNT(*) as active_count FROM student_sit_in 
              WHERE id_number = ? AND status = 'Active'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    closeConnection($conn);
    return $row['active_count'] > 0;  // Changed to check for any active sit-in
}
?>
