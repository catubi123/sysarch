<?php
include('db.php');

$lab = $_GET['lab'] ?? '';
$response = ['available' => 0, 'used' => 0];

if ($lab) {
    // Get available PCs (checked by admin)
    $query = "SELECT COUNT(*) as count FROM pc_status 
              WHERE lab = ? AND status = 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['available'] = $result->fetch_assoc()['count'];

    // Get used PCs (currently reserved)
    $query = "SELECT COUNT(*) as count FROM reservation 
              WHERE lab = ? AND status = 'approved' 
              AND reservation_date = CURDATE()";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['used'] = $result->fetch_assoc()['count'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
