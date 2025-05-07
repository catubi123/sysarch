<?php
session_start();
require_once(__DIR__ . '/../config/db.php');

// Check if it's an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

try {
    if (!isset($_GET['id'])) {
        throw new Exception("No material specified for deletion");
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
        $response = [
            'success' => true,
            'message' => 'Material deleted successfully'
        ];
    } else {
        throw new Exception("Error deleting material from database");
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

// Send appropriate response
if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $_SESSION[$response['success'] ? 'success' : 'error'] = $response['message'];
    header("Location: manage_materials.php");
}
exit();
?>
