<?php
session_start();
include('db.php');

$registration_status = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $MName = mysqli_real_escape_string($con, $_POST['MName']);
    $Course = mysqli_real_escape_string($con, $_POST['Course']);
    $Level = mysqli_real_escape_string($con, $_POST['Level']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    $query = "INSERT INTO user (id, lname, fname, MName, Course, Level, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $id, $lname, $fname, $MName, $Course, $Level, $username, $password);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            $registration_status = 'success';
        } else {
            $registration_status = 'error';
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $registration_status = 'error';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-primary d-flex align-items-center min-vh-100">
    <div class="card shadow-lg p-4 rounded-4 mx-auto" style="max-width: 400px;">
        <h2 class="text-center mb-3 text-primary">Registration</h2>
        <form method="post">
            <label class="form-label">IDNO</label>
            <input class="form-control" type="number" name="id" required>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Lastname</label>
                    <input class="form-control" type="text" name="lname" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Firstname</label>
                    <input class="form-control" type="text" name="fname" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middlename</label>
                    <input class="form-control" type="text" name="MName">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select class="form-select" name="Course" required>
                        <option value=""></option>
                        <option value="BSED">BSED</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSCPE">BSCPE</option>
                        <option value="BSCRIM">BSCRIM</option>
                        <option value="BSCA">BSCA</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BPED">BPED</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Yr/Level</label>
                    <select class="form-select" name="Level" required>
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </div>

            <label class="form-label">Username</label>
            <input class="form-control" type="text" name="username" required>

            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>

            <div class="d-grid">
                <button class="btn btn-primary mt-3">Register</button>
            </div>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="index.php" class="text-decoration-none">Log in</a>.</p>
            </div>
        </form>
    </div>

    <?php if ($registration_status): ?>
    <script>
        <?php if ($registration_status === 'success'): ?>
            Swal.fire({
                title: 'Success!',
                text: 'Registration successful!',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        <?php else: ?>
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred during registration.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
    <?php endif; ?>

</body>
</html>
