<?php
session_start();
include('db.php');
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert(icon, title, text, redirect = true) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false
            }).then((result) => {
                if (redirect) {
                    window.location.href = 'reservation.php';
                }
            });
        }
    </script>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['idNumber'];
    $lab = $_POST['lab'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $time = $_POST['timeIn'];
    $pc_number = (int)$_POST['selectedPC']; // Get the selected PC number

    // Check for existing pending reservations
    $check_query = "SELECT COUNT(*) as count FROM reservation WHERE id_number = ? AND status = 'pending'";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("i", $id_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        echo "<script>
            showAlert('error', 'Cannot Create Reservation', 'You already have a pending reservation.');
        </script>";
    } else {
        // Insert new reservation with PC number
        $query = "INSERT INTO reservation (id_number, lab, pc_number, purpose, reservation_date, reservation_time, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isisss", $id_number, $lab, $pc_number, $purpose, $date, $time);

        if ($stmt->execute()) {
            echo "<script>
                showAlert('success', 'Reservation Created', 'Your reservation has been submitted successfully!');
            </script>";
        } else {
            echo "<script>
                showAlert('error', 'Error', 'Failed to create reservation. Please try again.');
            </script>";
        }
    }
}
?>
</body>
</html>
