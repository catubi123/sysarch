<?php
include('../users/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lab'])) {
    $lab = $_POST['lab'];
    
    // Get PC statuses
    $query = "SELECT ps.pc_number, ps.is_available,
              CASE 
                WHEN EXISTS (
                    SELECT 1 FROM student_sit_in 
                    WHERE sit_lab = ? AND pc_number = ps.pc_number 
                    AND status = 'Active'
                ) THEN 'in-use'
                WHEN ps.is_available = 1 THEN 'checked'
                ELSE 'unchecked'
              END as status
              FROM pc_status ps
              WHERE ps.lab_number = ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $lab, $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pcs = [];
    $stats = [
        'checked' => 0,
        'unchecked' => 0,
        'inUse' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $pcs[] = [
            'number' => (int)$row['pc_number'],
            'status' => $row['status']
        ];
        $stats[$row['status'] === 'in-use' ? 'inUse' : 
              ($row['status'] === 'checked' ? 'checked' : 'unchecked')]++;
    }
    
    echo json_encode([
        'pcs' => $pcs,
        'stats' => $stats
    ]);
}
?>
