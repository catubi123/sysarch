<?php
session_start();
require_once('db.php');

// Add navbar
include('admin_navbar.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $website_url = $_POST['website_url'];
    $category = $_POST['category'];
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/materials/";  // Change this to parent directory
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "uploads/materials/" . $file_name;  // Store relative path without ../
        }
    }
    
    $query = "INSERT INTO lab_materials (title, description, website_url, image_path, category) 
              VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $title, $description, $website_url, $image_path, $category);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Material added successfully!";
    } else {
        $_SESSION['error'] = "Error adding material: " . $con->error;
    }
    
    header("Location: manage_materials.php");
    exit();
}

// Get existing materials
$materials = $con->query("SELECT * FROM lab_materials ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lab Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add New Material</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Website URL</label>
                                <input type="url" name="website_url" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="Programming">Programming</option>
                                    <option value="Database">Database</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image (Optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle"></i> Add Material
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-list"></i> Materials List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Website</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($material = $materials->fetch_assoc()): ?>
                                    <tr data-id="<?= $material['material_id'] ?>">
                                        <td><?= htmlspecialchars($material['title']) ?></td>
                                        <td><?= htmlspecialchars($material['category']) ?></td>
                                        <td>
                                            <a href="<?= htmlspecialchars($material['website_url']) ?>" 
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-external-link-alt"></i> Visit
                                            </a>
                                        </td>
                                        <td>
                                            <button onclick="deleteMaterial(<?= $material['material_id'] ?>)" 
                                                    class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Handle success/error messages
        <?php if(isset($_SESSION['success'])): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?= $_SESSION['success'] ?>',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?= $_SESSION['error'] ?>',
                icon: 'error'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        // Handle material deletion
        function deleteMaterial(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get the row element before deletion
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    
                    // Show loading state in the row
                    row.innerHTML = '<td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td>';
                    
                    // Use fetch for AJAX request
                    fetch(`delete_material.php?id=${id}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row immediately
                            row.remove();
                            Swal.fire('Deleted!', data.message, 'success');
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Failed to delete material', 'error');
                        // Refresh the page if there's an error
                        location.reload();
                    });
                }
            });
        }
    </script>
</body>
</html>
