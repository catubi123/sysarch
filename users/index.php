<?php
session_start();
ob_start(); // Start output buffering
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    // Check if the user is a regular user
    $user_query = "SELECT * FROM user WHERE username = ?";
    $user_stmt = mysqli_prepare($con, $user_query);

    if ($user_stmt) {
        mysqli_stmt_bind_param($user_stmt, "s", $username);
        mysqli_stmt_execute($user_stmt);
        $user_result = mysqli_stmt_get_result($user_stmt);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_data = mysqli_fetch_assoc($user_result);

            if (password_verify($password, $user_data["password"])) {
                $_SESSION['username'] = $username;

                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            text: 'Welcome, " . htmlspecialchars($username) . "!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'home.php';
                        });
                    });
                </script>";
                exit();
            }
        }
        mysqli_stmt_close($user_stmt);
    }

    // Check if the user is an admin
    $admin_query = "SELECT * FROM admin WHERE USER_NAME = ?";
    $admin_stmt = mysqli_prepare($con, $admin_query);

    if ($admin_stmt) {
        mysqli_stmt_bind_param($admin_stmt, "s", $username);
        mysqli_stmt_execute($admin_stmt);
        $admin_result = mysqli_stmt_get_result($admin_stmt);

        if ($admin_result && mysqli_num_rows($admin_result) > 0) {
            $admin_data = mysqli_fetch_assoc($admin_result);

            if (password_verify($password, $admin_data["PASSWORD_HASH"])) {
                $_SESSION['username'] = $username;
                
                $_SESSION['role'] = 'admin';

                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Admin Login Successful!',
                            text: 'Welcome, Admin " . htmlspecialchars($username) . "!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'admin_Dashboard.php';
                        });
                    });
                </script>";
                exit();
            }
        }
        mysqli_stmt_close($admin_stmt);
    }

    // If login fails
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Invalid username or password!',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>";
}
ob_end_flush(); // Flush the output buffer
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
            <img src="images/ccs.png" alt="Image 1" class="rounded-circle" style="width:80px;height:80px;">
            <img src="images/ucmain.jpg" alt="Image 2" class="rounded-circle ms-2" style="width:80px;height:80px;">
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
            timer: '<?php echo $_SESSION['alert']['type'] === 'success' ? 2000 : 4000; ?>',
            showConfirmButton: false
        });

        // Remove alert from session after displaying
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
};
</script>

</body>
</html>
