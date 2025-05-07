<?php
session_start();
require_once(__DIR__ . '/../config/db.php');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No material specified for deletion";
    header("Location: manage_materials.php");
    exit();
}

$material_id = intval($_GET['id']);

// Get image path before deletion
$query = "SELECT image_path FROM lab_materials WHERE material_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $material_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Delete the image file if it exists
    if ($row['image_path'] && file_exists("../" . $row['image_path'])) {
        unlink("../" . $row['image_path']);
    }
}

// Delete the database record
$query = "DELETE FROM lab_materials WHERE material_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $material_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Material deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting material";
}

header("Location: manage_materials.php");
exit();
?>
