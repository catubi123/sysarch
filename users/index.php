<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = openConnection();

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Check if the user is an admin
    $admin_query = "SELECT * FROM admin WHERE USER_NAME = ?";
    $admin_stmt = mysqli_prepare($conn, $admin_query);

    if ($admin_stmt) {
        mysqli_stmt_bind_param($admin_stmt, "s", $username);
        mysqli_stmt_execute($admin_stmt);
        $admin_result = mysqli_stmt_get_result($admin_stmt);

        if ($admin_result && mysqli_num_rows($admin_result) > 0) {
            $admin_data = mysqli_fetch_assoc($admin_result);

            // Corrected password verification logic
            if (password_verify($password, $admin_data["PASSWORD_HASH"])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['ADMIN_ID'] = $admin_data['ADMIN_ID'];
                $_SESSION['USER_NAME'] = $admin_data['USER_NAME'];
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Login Successful!',
                    'redirect' => 'admin_Dashboard.php'
                ];
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'title' => 'Oops...',
                    'message' => 'Invalid Username or Password'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Oops...',
                'message' => 'Invalid Username or Password'
            ];
        }

        mysqli_stmt_close($admin_stmt);
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => '❗ Database error! Please try again later.'
        ];
    }

    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CCS Sit In Monitoring System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-primary d-flex align-items-center min-vh-100">
    <div class="card shadow-lg p-4 rounded-4 mx-auto" style="max-width: 400px;">
        <div class="text-center mb-4">
            <img src="ccs.png" alt="Image 1" class="rounded-circle" style="width:80px;height:80px;">
            <img src="ucmain.jpg" alt="Image 2" class="rounded-circle ms-2" style="width:80px;height:80px;">
        </div>
        <h2 class="text-center text-primary">CCS Sit Monitoring System</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" type="text" name="username" placeholder="Enter username" required autocomplete="off">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password" placeholder="Enter password" required autocomplete="off">
            </div>

            <div class="d-grid">
                <button class="btn btn-primary">Login</button>
            </div>

            <div class="text-center mt-3">
                <p>Don't have an account? <a href="signup.php" class="text-decoration-none">Register</a>.</p>
            </div>
        </form>
    </div>

<script>
window.onload = function () {
    <?php if(isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['alert']['type']; ?>',
            title: '<?php echo $_SESSION['alert']['title']; ?>',
            text: '<?php echo $_SESSION['alert']['message']; ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            <?php if (!empty($_SESSION['alert']['redirect'])): ?>
                window.location.href = '<?php echo $_SESSION['alert']['redirect']; ?>';
            <?php endif; ?>
        });

        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
};
</script>

</body>
</html>