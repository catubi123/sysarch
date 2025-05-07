<?php
require_once('db.php');
header('Content-Type: application/json');

if (isset($_GET['lab'])) {
    $lab = $_GET['lab'];
    
    try {
        // Create table if not exists
        $con->query("CREATE TABLE IF NOT EXISTS lab_pc (
            id INT PRIMARY KEY AUTO_INCREMENT,
            lab VARCHAR(10) NOT NULL,
            pc_number INT NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            UNIQUE KEY lab_pc_unique (lab, pc_number)
        )");

        // Insert default PC records if they don't exist
        $stmt = $con->prepare("INSERT IGNORE INTO lab_pc (lab, pc_number, is_active) 
                             SELECT ?, number, 1 
                             FROM (
                                 SELECT 1 as number UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
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
        $query = "SELECT pc_number, is_active FROM lab_pc WHERE lab = ? ORDER BY pc_number";
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

        // Get current approved reservations
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $reservation_query = "SELECT * FROM reservation 
                             WHERE lab = ? 
                             AND status = 'approved' 
                             AND reservation_date = ? 
                             AND reservation_time <= ? 
                             AND DATE_ADD(reservation_time, INTERVAL 1 HOUR) >= ?";
        
        $stmt = $con->prepare($reservation_query);
        $stmt->bind_param("ssss", $lab, $today, $current_time, $current_time);
        $stmt->execute();
        $reservation_result = $stmt->get_result();
        
        $reservations = [];
        while ($row = $reservation_result->fetch_assoc()) {
            $reservations[] = $row;
        }

        echo json_encode([
            'success' => true,
            'pcs' => $pcs,
            'reservations' => $reservations,
            'count' => count($pcs),
            'lab' => $lab
        ]);
        
    } catch (Exception $e) {
        error_log("PC Status Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Lab not specified'
    ]);
}
