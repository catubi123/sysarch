<?php
include('db.php');

// Create pc_numbers table if not exists
$con->query("CREATE TABLE IF NOT EXISTS pc_numbers (
    lab_number VARCHAR(10),
    pc_number INT,
    PRIMARY KEY (lab_number, pc_number)
)");

// Clear existing data
$con->query("TRUNCATE TABLE pc_numbers");

// Labs and PCs to populate
$labs = ['524', '526', '528', '530', '542', '544'];
$pcs_per_lab = 50;

// Insert PCs for each lab
$stmt = $con->prepare("INSERT INTO pc_numbers (lab_number, pc_number) VALUES (?, ?)");

foreach ($labs as $lab) {
    for ($pc = 1; $pc <= $pcs_per_lab; $pc++) {
        $stmt->bind_param("si", $lab, $pc);
        $stmt->execute();
    }
}

echo "PC numbers setup complete!";
