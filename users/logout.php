<?php
session_start();
session_destroy(); // Destroy all active sessions
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Logged Out Successfully!',
        text: 'You have been safely logged out.',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'index.php';
    });
</script>
</body>
</html>
