<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($password, $user_data["password"])) {
                $_SESSION['username'] = $username;
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => '✅ Login Successful!',
                    'redirect' => 'home.php'
                ];
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'title' => 'Oops...',
                    'message' => '❌ Invalid Username or Password'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Oops...',
                'message' => '❌ Invalid Username or Password'
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => '❗ Database error! Please try again later.'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CCS Sit In Monitoring System</title>
    <link rel="stylesheet" href="w3.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="w3-blue">
    <div class="w3-card-4 w3-white w3-padding w3-round-xxlarge w3-animate-top" style="max-width:400px;margin:auto;margin-top:50px;">
        <div class="w3-center">
            <img src="ccs.png" alt="Image 1" class="w3-circle" style="width:80px;height:80px;margin:10px;">
            <img src="ucmain.jpg" alt="Image 2" class="w3-circle" style="width:80px;height:80px;margin:10px;">
        </div>
        <h2 class="w3-center">CCS Sit Monitoring System</h2>
        <form method="POST">
            <label>Username</label>
            <input class="w3-input w3-border" type="text" name="username" placeholder="Enter username" required autocomplete="off">
            <label>Password</label>
            <input class="w3-input w3-border" type="password" name="password" placeholder="Enter password" required autocomplete="off">
            <p><button class="w3-button w3-cyan w3-round-xlarge">Login</button></p>

            <div class="container signin">
                <p>Don't have an account? <a href="signup.php">Register</a>.</p>
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
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
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
