<?php
require_once('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Verify database connection
if (!isset($con) || $con->connect_error) {
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . ($con->connect_error ?? "Connection not established")
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input parameters
    if (empty($_POST['id']) || empty($_POST['status'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
        exit;
    }

    $reservation_id = $_POST['id'];
    $status = $_POST['status'];
    $current_datetime = date('Y-m-d H:i:s');
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');

    if ($reservation_id && $status) {
        // Start transaction
        $con->begin_transaction();

        try {
            if ($status === 'approved') {
                // First get the reservation details with explicit PC number selection
                $stmt = $con->prepare("SELECT r.*, u.fname, u.lname, u.course, u.level, r.pc_number 
                                     FROM reservation r 
                                     JOIN user u ON r.id_number = u.id 
                                     WHERE r.reservation_id = ?");
                $stmt->bind_param("i", $reservation_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if (!$result || $result->num_rows === 0) {
                    throw new Exception("Reservation not found");
                }
                
                $reservation = $result->fetch_assoc();

                // Debug output
                error_log("Debug - Lab: " . $reservation['lab'] . ", PC: " . $reservation['pc_number']);

                // Validate PC number
                if (empty($reservation['pc_number']) || $reservation['pc_number'] == 0) {
                    throw new Exception("Invalid PC number in reservation");
                }

                // Check if PC exists and is available
                $stmt = $con->prepare("SELECT COUNT(*) as pc_exists, 
                                             (SELECT is_active FROM lab_pc 
                                              WHERE lab = ? AND pc_number = ? LIMIT 1) as is_active 
                                      FROM lab_pc 
                                      WHERE lab = ? AND pc_number = ?");
                $stmt->bind_param("sisi", 
                    $reservation['lab'], 
                    $reservation['pc_number'],
                    $reservation['lab'], 
                    $reservation['pc_number']
                );
                $stmt->execute();
                $pc_check = $stmt->get_result()->fetch_assoc();

                if ($pc_check['pc_exists'] == 0) {
                    throw new Exception("PC #{$reservation['pc_number']} not found in Laboratory {$reservation['lab']}");
                }

                if ($pc_check['is_active'] == 0) {
                    throw new Exception("PC #{$reservation['pc_number']} is already in use");
                }

                // Update reservation with sit-in start time
                $stmt = $con->prepare("UPDATE reservation 
                                     SET status = ?, 
                                         sit_in_started_at = ?,
                                         updated_at = ? 
                                     WHERE reservation_id = ?");
                $stmt->bind_param("sssi", $status, $current_datetime, $current_datetime, $reservation_id);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update reservation status");
                }

                // Insert into student_sit_in table
                $stmt = $con->prepare("INSERT INTO student_sit_in 
                                     (id_number, sit_purpose, sit_lab, pc_number, 
                                      time_in, sit_date, status, reservation_id) 
                                     VALUES (?, ?, ?, ?, ?, ?, 'Active', ?)");
                $stmt->bind_param("ssssssi", 
                    $reservation['id_number'],
                    $reservation['purpose'],
                    $reservation['lab'],
                    $reservation['pc_number'],
                    $current_time,
                    $current_date,
                    $reservation_id
                );
                $stmt->execute();

                // Update PC status to in-use (is_active = 0) with error checking
                $stmt = $con->prepare("UPDATE lab_pc 
                                     SET is_active = 0 
                                     WHERE lab = ? AND pc_number = ?");
                $stmt->bind_param("si", $reservation['lab'], $reservation['pc_number']);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update PC status");
                }
                
                if ($stmt->affected_rows === 0) {
                    throw new Exception("No PC was updated");
                }

                $con->commit();
                echo json_encode([
                    'success' => true,
                    'debug' => [
                        'lab' => $reservation['lab'],
                        'pc_number' => $reservation['pc_number']
                    ]
                ]);

            } else {
                // For rejected status, just update the reservation
                $stmt = $con->prepare("UPDATE reservation 
                                     SET status = ?,
                                         updated_at = ? 
                                     WHERE reservation_id = ?");
                $stmt->bind_param("ssi", $status, $current_datetime, $reservation_id);
                $stmt->execute();
                $con->commit();
                echo json_encode(['success' => true]);
                exit;
            }

        } catch (Exception $e) {
            $con->rollback();
            error_log("Reservation error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'error' => $e->getMessage(),
                'debug' => [
                    'reservation_id' => $reservation_id,
                    'status' => $status,
                    'details' => isset($reservation) ? [
                        'lab' => $reservation['lab'] ?? 'not set',
                        'pc_number' => $reservation['pc_number'] ?? 'not set'
                    ] : 'reservation not loaded'
                ]
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
