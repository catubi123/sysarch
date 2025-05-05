<?php
include('db.php');

// Create pc_numbers table if it doesn't exist
$con->query("CREATE TABLE IF NOT EXISTS pc_numbers (
    lab_number VARCHAR(10),
    pc_number INT,
    PRIMARY KEY (lab_number, pc_number)
)");

// Clear existing data
$con->query("TRUNCATE TABLE pc_numbers");

// Insert PCs for each lab
$labs = ['524', '526', '528', '530', '542', '544'];
$stmt = $con->prepare("INSERT INTO pc_numbers (lab_number, pc_number) VALUES (?, ?)");

foreach ($labs as $lab) {
    for ($pc = 1; $pc <= 50; $pc++) {
        $stmt->bind_param("si", $lab, $pc);
        $stmt->execute();
    }
}

echo "Lab PCs setup complete!";
