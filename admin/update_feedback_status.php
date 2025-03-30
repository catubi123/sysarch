<?php
include('../users/db.php');

if(isset($_POST['id']) && isset($_POST['rating'])) {
    $id = (int)$_POST['id'];
    $rating = (int)$_POST['rating'];
    
    // Add error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Check if table structure is correct
    $check_table = "DESCRIBE feedback";
    $table_result = $con->query($check_table);
    $columns = [];
    while($row = $table_result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    // If columns don't exist, create them
    if (!in_array('admin_rating', $columns)) {
        $alter_table = "ALTER TABLE feedback 
                       ADD COLUMN status VARCHAR(20) DEFAULT 'pending',
                       ADD COLUMN admin_rating INT DEFAULT NULL,
                       ADD COLUMN read_date DATETIME DEFAULT NULL";
        $con->query($alter_table);
    }
    
    // Now proceed with the update
    $query = "UPDATE feedback SET status = 'read', admin_rating = ?, read_date = NOW() WHERE id = ?";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit;
    }
    
    $stmt->bind_param("ii", $rating, $id);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo 'missing_parameters';
}
?>
