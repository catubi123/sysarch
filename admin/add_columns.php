<?php
include('db.php');
$conn = openConnection();

$sql = "ALTER TABLE reservation 
        ADD COLUMN IF NOT EXISTS approved_at DATETIME NULL,
        ADD COLUMN IF NOT EXISTS actual_time_in DATETIME NULL,
        ADD COLUMN IF NOT EXISTS actual_time_out DATETIME NULL";

if ($conn->query($sql)) {
    echo "Columns added successfully";
} else {
    echo "Error adding columns: " . $conn->error;
}

closeConnection($conn);
?>
