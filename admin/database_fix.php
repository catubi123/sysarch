<?php
include('db.php');
$conn = openConnection();

$sql = "DESCRIBE reservation";
$result = $conn->query($sql);
if ($result) {
    echo "Current columns in reservation table:<br>";
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . "<br>";
    }
}

// Add missing columns
$alterSql = "ALTER TABLE reservation 
             MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending',
             ADD COLUMN IF NOT EXISTS approved_at DATETIME NULL,
             ADD COLUMN IF NOT EXISTS actual_time_in DATETIME NULL,
             ADD COLUMN IF NOT EXISTS actual_time_out DATETIME NULL";

if ($conn->query($alterSql)) {
    echo "<br>Table structure updated successfully";
} else {
    echo "<br>Error updating table: " . $conn->error;
}

closeConnection($conn);
?>
