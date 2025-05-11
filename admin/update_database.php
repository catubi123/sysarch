<?php
include('db.php');
$conn = openConnection();

try {
    // Add new columns if they don't exist
    $alterQueries = [
        "ALTER TABLE reservation ADD COLUMN IF NOT EXISTS approved_at DATETIME NULL",
        "ALTER TABLE reservation ADD COLUMN IF NOT EXISTS actual_time_in DATETIME NULL",
        "ALTER TABLE reservation ADD COLUMN IF NOT EXISTS actual_time_out DATETIME NULL"
    ];

    foreach ($alterQueries as $query) {
        if (!$conn->query($query)) {
            throw new Exception("Error executing query: " . $query . "\nError: " . $conn->error);
        }
    }

    echo "Database updated successfully";
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage();
}

closeConnection($conn);
?>
