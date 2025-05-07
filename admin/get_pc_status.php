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
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
        $query = "SELECT lp.pc_number, lp.is_active, lp.last_updated,
                 CASE WHEN r.reservation_id IS NOT NULL THEN 0 ELSE lp.is_active END as final_status
                 FROM lab_pc lp
                 LEFT JOIN reservation r ON lp.lab = r.lab 
                    AND lp.pc_number = r.pc_number
                    AND r.status = 'approved'
                    AND r.reservation_date = CURRENT_DATE
                    AND r.reservation_time <= CURRENT_TIME
                    AND DATE_ADD(r.reservation_time, INTERVAL 1 HOUR) >= CURRENT_TIME
                 WHERE lp.lab = ?
                 ORDER BY lp.pc_number";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $lab);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pcs = [];
        while ($row = $result->fetch_assoc()) {
            $pcs[] = [
                'number' => (int)$row['pc_number'],
                'is_active' => (bool)$row['final_status'],
                'last_updated' => $row['last_updated']
            ];
        }

        // Get current approved reservations
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $reservation_query = "SELECT r.reservation_id, r.id_number, r.lab, r.pc_number, 
                                    r.reservation_date, r.reservation_time, r.status,
                                    u.fname, u.lname 
                             FROM reservation r 
                             LEFT JOIN user u ON r.id_number = u.id 
                             WHERE r.lab = ? 
                             AND r.status = 'approved' 
                             AND r.reservation_date = ? 
                             AND r.reservation_time <= ? 
                             AND DATE_ADD(r.reservation_time, INTERVAL 1 HOUR) >= ?";
        
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
