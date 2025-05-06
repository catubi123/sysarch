<?php
require_once('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pc_number = $_POST['pc_number'] ?? null;
    $lab = $_POST['lab'] ?? null;
    $active = $_POST['active'] ?? null;

    if ($pc_number && $lab !== null && $active !== null) {
        // Start transaction
        $con->begin_transaction();

        try {
            // Delete any existing record first
            $delete_sql = "DELETE FROM pc_status WHERE pc_number = ? AND lab_number = ?";
            $delete_stmt = $con->prepare($delete_sql);
            $delete_stmt->bind_param("is", $pc_number, $lab);
            $delete_stmt->execute();

            // Insert new record
            $insert_sql = "INSERT INTO pc_status (pc_number, lab_number, is_active, last_updated) VALUES (?, ?, ?, NOW())";
            $insert_stmt = $con->prepare($insert_sql);
            $active_val = $active ? 1 : 0;
            $insert_stmt->bind_param("isi", $pc_number, $lab, $active_val);
            $insert_stmt->execute();

            $con->commit();
            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            $con->rollback();
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
