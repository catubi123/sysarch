<?php
include('db.php');

// Labs to populate
$labs = ['524', '526', '528', '530', '542', '544'];
$pcs_per_lab = 50;

// Clear existing data
$con->query("TRUNCATE TABLE pc_numbers");

// Prepare insert statement
$stmt = $con->prepare("INSERT INTO pc_numbers (lab_number, pc_number) VALUES (?, ?)");

// Insert PCs for each lab
foreach ($labs as $lab) {
    for ($pc = 1; $pc <= $pcs_per_lab; $pc++) {
        $stmt->bind_param("si", $lab, $pc);
        $stmt->execute();
    }
}

echo "PC numbers populated successfully!";
