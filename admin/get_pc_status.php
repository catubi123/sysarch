<?php
include('db.php');

header('Content-Type: application/json');

if (isset($_GET['lab']) && isset($_GET['pc_number'])) {
    $lab = $_GET['lab'];
    $pc_number = $_GET['pc_number'];

    // Check if PC is working (from pc_status table)
    $status_query = "SELECT status FROM pc_status WHERE lab = ? AND pc_number = ?";
    $stmt = $con->prepare($status_query);
    $stmt->bind_param("si", $lab, $pc_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $response = [
        'checked' => false,
        'unavailable' => false
    ];

    if ($row = $result->fetch_assoc()) {
        $response['checked'] = $row['status'] == 1;
    }

    // Check if PC is currently reserved
    $reserved_query = "SELECT 1 FROM reservation 
                      WHERE lab = ? AND pc_number = ? 
                      AND status = 'approved' 
                      AND reservation_date = CURDATE()";
    $stmt = $con->prepare($reserved_query);
    $stmt->bind_param("si", $lab, $pc_number);
    $stmt->execute();
    $response['unavailable'] = $stmt->get_result()->num_rows > 0;

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
?>
