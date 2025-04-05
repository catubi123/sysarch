<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    // Check if ID exists
    $check_query = "SELECT id FROM user WHERE id = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("s", $id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        echo "<script>
            Swal.fire({
                title: 'Registration Failed',
                text: 'This ID number is already registered in the system.',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location.href = 'register.php';
            });
        </script>";
        exit();
    }
    
    // Continue with registration if ID is unique
    // ...existing registration code...
}
?>
