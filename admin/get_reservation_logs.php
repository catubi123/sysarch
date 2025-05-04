<?php
include('../users/db.php');

$status = $_GET['status'] ?? 'approved';
$query = "SELECT r.*, u.fname, u.lname 
          FROM reservation r 
          JOIN user u ON r.id_number = u.id 
          WHERE r.status = ? 
          ORDER BY r.reservation_date DESC, r.reservation_time DESC 
          LIMIT 10";

$stmt = $con->prepare($query);
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alertClass = $status === 'approved' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert {$alertClass} mb-2'>";
        echo "<div class='d-flex justify-content-between align-items-center'>";
        echo "<div>";
        echo "<strong>{$row['fname']} {$row['lname']}</strong><br>";
        echo "Lab {$row['lab']} - PC {$row['pc_number']}<br>";
        echo "<small>{$row['reservation_date']} {$row['reservation_time']}</small>";
        echo "</div>";
        echo "<div><i class='fas fa-" . ($status === 'approved' ? 'check' : 'times') . "-circle'></i></div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div class='text-center text-muted py-3'>";
    echo "<i class='fas fa-info-circle'></i><br>";
    echo "No {$status} reservations found";
    echo "</div>";
}
?>
```
```php
<?php
include('db.php');

$query = "SELECT r.*, u.fname, u.lname 
          FROM reservation r 
          LEFT JOIN user u ON r.id_number = u.id 
          ORDER BY r.reservation_date DESC, r.reservation_time DESC";

$result = $con->query($query);
$data = array();

while($row = $result->fetch_assoc()) {
    $status_class = match($row['status']) {
        'pending' => 'text-warning',
        'approved' => 'text-success',
        'rejected' => 'text-danger',
        default => ''
    };

    $data['data'][] = array(
        $row['reservation_date'],
        $row['reservation_time'],
        $row['id_number'],
        'Lab ' . $row['lab'],
        'PC-' . str_pad($row['pc_number'], 2, '0', STR_PAD_LEFT),
        $row['purpose'],
        "<span class='{$status_class}'>" . ucfirst($row['status']) . "</span>"
    );
}

if (empty($data)) {
    $data['data'] = array();
}

header('Content-Type: application/json');
echo json_encode($data);
?>
