<?php
session_start();
include('db.php');

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You need to log in first!'); window.location.href = 'index.php';</script>";
    exit();
}

// Fetch user data using username
$username = $_SESSION['username'];
$query = "SELECT id, lname, fname, MName, email, course, level, image, address FROM user WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found!'); window.location.href = 'index.php';</script>";
    exit();
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $MName = $_POST['MName'];
    $course = $_POST['Course'];
    $level = $_POST['Level'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $user_id = $_POST['id'];

    // Handle image upload (optional)
    $image_path = $user_data['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_extensions)) {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.');</script>";
        } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<script>alert('Error uploading image.'); window.history.back();</script>";
            exit();
        } else {
            $image_path = $target_file;
        }
    }

    // Update user data in the database
    $update_query = "UPDATE user SET lname = ?, fname = ?, MName = ?, email = ?, course = ?, level = ?, image = ?, address = ? WHERE id = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("ssssssssi", $lname, $fname, $MName, $email, $course, $level, $image_path, $address, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['profile_updated'] = true;
header('Location: home.php');
exit();

        } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-primary bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php">Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
            <a href="edit.php" class="nav-link text-white"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
        </div>
    </div>
</nav>


<!-- Profile Edit Form -->
<div class="card shadow-lg rounded-4 p-4 mx-auto mt-4" style="max-width: 420px;">
    <h2 class="text-center">Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <label class="form-label">Profile Image</label>
        <input class="form-control" type="file" name="image" accept="image/*">

        <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">

        <label class="form-label">Lastname</label>
        <input class="form-control" type="text" name="lname" value="<?php echo $user_data['lname']; ?>" required>

        <label class="form-label">Firstname</label>
        <input class="form-control" type="text" name="fname" value="<?php echo $user_data['fname']; ?>" required>

        <label class="form-label">MiddleName</label>
        <input class="form-control" type="text" name="MName" value="<?php echo $user_data['MName']; ?>">

        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo $user_data['email']; ?>" required>

        <label class="form-label">Address</label>
        <input class="form-control" type="text" name="address" value="<?php echo $user_data['address']; ?>">

        <label class="form-label">Course</label>
        <select class="form-select" name="Course" required>
            <?php 
            $courses = ['BSED', 'BSIT', 'BSCPE', 'BSCRIM', 'BSCA', 'BSCS', 'BPED'];
            foreach ($courses as $course) {
                $selected = ($user_data['course'] == $course) ? 'selected' : '';
                echo "<option value='$course' $selected>$course</option>";
            }
            ?>
        </select>

        <label class="form-label">Yr/Level</label>
        <select class="form-select" name="Level" required>
            <?php 
            for ($i = 1; $i <= 4; $i++) {
                $selected = ($user_data['level'] == $i) ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>

        <p><button type="submit" class="btn btn-primary w-100 mt-3">Save Changes</button></p>
    </form>
</div>
<script>
function showAlert(title, text, icon) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon
    });
}

// Trigger success alert after profile update
<?php if (isset($_SESSION['profile_updated']) && $_SESSION['profile_updated']) { ?>
    showAlert('Success!', 'Your profile has been successfully updated.', 'success');
    <?php unset($_SESSION['profile_updated']); ?>
<?php } ?>
</script>

</body>
</html>
