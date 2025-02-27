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
    // Get form data
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $MName = $_POST['MName'];
    $course = $_POST['Course']; // Ensure proper casing
    $level = $_POST['Level'];   // Ensure proper casing
    $email = $_POST['email'];
    $address = $_POST['address'];
    $user_id = $_POST['id'];

    // Handle image upload (optional)
    $image_path = $user_data['image']; // Default to current image if no new image uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        
        // Ensure upload directory exists
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
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'home.php';</script>";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

</head>
<body class="w3-light-grey">

<!-- Navbar -->
<div class="w3-bar w3-blue">
    <span class="w3-bar-item w3-large">Dashboard</span>
    <a href="index.php" class="w3-bar-item w3-button w3-right w3-red w3-round-xlarge">Log out</a>
    <a href="edit.php" class="w3-bar-item w3-button w3-right">
    Edit Profile <i class="fa-solid fa-pencil"></i>
    </a>
    <a href="home.php" class=" w3-blue w w3-bar-item w3-button w3-right">
    Home <i class="w3-margin-left glyphicon glyphicon-home"></i>
</a>

</div>

<!-- Profile Edit Form -->
<div class="w3-card-4 w3-white w3-padding w3-round-xxlarge" style="max-width:420px;margin:auto;margin-top:30px;">
    <h2 class="w3-center">Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        
        <label>Profile Image</label>
        <input class="w3-input w3-border" type="file" name="image" accept="image/*">
        
        <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">

        <label>Lastname</label>
        <input class="w3-input w3-border" type="text" name="lname" value="<?php echo $user_data['lname']; ?>" required>
        
        <label>Firstname</label>
        <input class="w3-input w3-border" type="text" name="fname" value="<?php echo $user_data['fname']; ?>" required>
        
        <label>MiddleName</label>
        <input class="w3-input w3-border" type="text" name="MName" value="<?php echo $user_data['MName']; ?>">
        
        <label>Email</label>
        <input class="w3-input w3-border" type="email" name="email" value="<?php echo $user_data['email']; ?>" required>

        <label>Address</label>
        <input class="w3-input w3-border" type="text" name="address" value="<?php echo $user_data['address']; ?>">

        <label>Course</label>
        <select class="w3-input w3-border" name="Course" required>
            <?php 
            $courses = ['BSED', 'BSIT', 'BSCPE', 'BSCRIM', 'BSCA', 'BSCS', 'BPED'];
            foreach ($courses as $course) {
                $selected = ($user_data['course'] == $course) ? 'selected' : '';
                echo "<option value='$course' $selected>$course</option>";
            }
            ?>
        </select>

        <label>Yr/Level</label>
        <select class="w3-input w3-border" name="Level" required>
            <?php 
            for ($i = 1; $i <= 4; $i++) {
                $selected = ($user_data['level'] == $i) ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>

        <p><button type="submit" class="w3-button w3-cyan w3-round-xlarge">Save Changes</button></p>
    </form>
</div>

</body>
</html>
