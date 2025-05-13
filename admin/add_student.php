<?php
ob_start();
session_start();
include('db.php');
include('admin_navbar.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Check if student ID or username already exists
        $check_query = "SELECT id, username FROM user WHERE id = ? OR username = ?";
        $check_stmt = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($check_stmt, "ss", $_POST['id'], $_POST['username']);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            throw new Exception("Student ID or Username already exists!");
        }

        // Get default remaining session value
        $query = "SELECT value FROM settings WHERE setting_name = 'default_sessions'";
        $result = mysqli_query($con, $query);
        $default_sessions = ($result && mysqli_num_rows($result) > 0) ? 
                            mysqli_fetch_assoc($result)['value'] : 30;

        // Validate required fields
        $required_fields = ['id', 'username', 'fname', 'lname', 'level', 'course', 'password'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required!");
            }
        }

        $id = $_POST['id'];
        $username = $_POST['username'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $mname = $_POST['mname'];
        $level = $_POST['level'];
        $course = $_POST['course'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO user (id, username, fname, lname, MName, Level, Course, password, role, remaining_session) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user', ?)";
        
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "ssssssssi", $id, $username, $fname, $lname, $mname, $level, $course, $password, $default_sessions);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        if (mysqli_affected_rows($con) > 0) {
            $_SESSION['success'] = true;
            $_SESSION['message'] = "Student added successfully!";
            header("Location: student_information.php");
            exit();
        } else {
            throw new Exception("No rows were inserted. Error: " . mysqli_error($con));
        }
    } catch (Exception $e) {
        $_SESSION['success'] = false;
        $_SESSION['message'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                    <h4 class="mb-0">Add New Student</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return validateForm();">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Student ID</label>
                                    <input type="text" class="form-control" name="id" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="fname" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="mname">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="lname" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Level</label>
                                    <select class="form-select" name="level" required>
                                        <option value="">Select Level</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Course</label>
                                    <select class="form-select" name="course" required>
                                        <option value="">Select Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSCS">BSCS</option>
                                        <option value="BSIS">BSIS</option>
                                        <option value="BSCA">BSCA</option>
                                        <option value="BSCJ">BSCJ</option>
                                        <option value="BSHM">BSHM</option>
                                        <option value="BSCPE">BSCPE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary me-2">Add Student</button>
                                <a href="student_information.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            if (password !== confirm_password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords do not match!'
                });
                return false;
            }
            return true;
        }

        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['success'] ? 'success' : 'error'; ?>',
                title: '<?php echo $_SESSION['success'] ? 'Success' : 'Error'; ?>',
                text: '<?php echo $_SESSION['message']; ?>'
            });
            <?php unset($_SESSION['message']); unset($_SESSION['success']); ?>
        <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>