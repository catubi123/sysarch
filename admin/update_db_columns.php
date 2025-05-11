<?php
include('db.php');
$conn = openConnection();

$sql = "ALTER TABLE reservation 
        ADD COLUMN IF NOT EXISTS approved_at DATETIME NULL,
        ADD COLUMN IF NOT EXISTS actual_time_in DATETIME NULL,
        ADD COLUMN IF NOT EXISTS actual_time_out DATETIME NULL,
        MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'";

if ($conn->query($sql)) {
    echo "Database updated successfully";
} else {
    echo "Error updating database: " . $conn->error;
}

closeConnection($conn);
?>
