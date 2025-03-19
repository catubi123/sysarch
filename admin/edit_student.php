<?php
include('db.php');
include('admin_navbar.php');

if (!isset($_GET['id'])) {
    header("Location: student_information.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM user WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $level = $_POST['level'];
    $course = $_POST['course'];
    
    $query = "UPDATE user SET fname=?, lname=?, MName=?, Level=?, Course=? WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $mname, $level, $course, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: student_information.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Student Information</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Number</label>
                            <input type="text" value="<?= htmlspecialchars($user['id']) ?>" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lname" value="<?= htmlspecialchars($user['lname']) ?>" class="form-control" placeholder="Enter your last name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="fname" value="<?= htmlspecialchars($user['fname']) ?>" class="form-control" placeholder="Enter your first name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="mname" value="<?= htmlspecialchars($user['MName']) ?>" class="form-control" placeholder="Enter your middle name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year Level</label>
                            <select name="level" class="form-select">
                                <?php for($i = 1; $i <= 4; $i++): ?>
                                    <option value="<?= $i ?>" <?= $user['Level'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-select">
                                <?php
                                $courses = ['BSIT', 'BSCS', 'BSBS', 'BSCJ', 'BSCA', 'BSHM', 'BSCPE'];
                                foreach($courses as $c):
                                ?>
                                    <option value="<?= $c ?>" <?= $user['Course'] == $c ? 'selected' : '' ?>><?= $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="student_information.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
