<?php
require_once('db.php');

header('Content-Type: application/json');

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Purpose:
// 1. Returns status of all PCs in a specific lab
// 2. Creates PC records if they don't exist
// 3. Used by both admin and user interfaces
// 4. Returns JSON with PC availability status

if (isset($_GET['lab'])) {
    $lab = $_GET['lab'];
    
    try {
        // Create temporary records for any missing PCs
        $stmt = $con->prepare("INSERT IGNORE INTO lab_pc (lab, pc_number, is_active)
                             SELECT ?, number, 1
                             FROM (
                                 SELECT 1 as number
                                 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
                                 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
                                 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15
                                 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20
                                 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25
                                 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30
                                 UNION SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35
                                 UNION SELECT 36 UNION SELECT 37 UNION SELECT 38 UNION SELECT 39 UNION SELECT 40
                                 UNION SELECT 41 UNION SELECT 42 UNION SELECT 43 UNION SELECT 44 UNION SELECT 45
                                 UNION SELECT 46 UNION SELECT 47 UNION SELECT 48 UNION SELECT 49 UNION SELECT 50
                             ) numbers");
        $stmt->bind_param("s", $lab);
        $stmt->execute();

        // Get PC status
        $query = "SELECT pc_number, is_active 
                 FROM lab_pc 
                 WHERE lab = ? 
                 ORDER BY pc_number ASC";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $lab);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pcs = [];
        while ($row = $result->fetch_assoc()) {
            $pcs[] = [
                'number' => (int)$row['pc_number'],
                'is_active' => (bool)$row['is_active']
            ];
        }
        
        echo json_encode(['success' => true, 'pcs' => $pcs]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Lab not specified']);
}
?>
