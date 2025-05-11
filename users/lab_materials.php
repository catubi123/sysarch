<?php
session_start();
date_default_timezone_set('Asia/Manila'); // Add this line for Philippine timezone
include('db.php');

// Add user data fetch for notifications
$user_id = $_SESSION['id_number'] ?? null;
$notif_query = "SELECT COUNT(*) as count FROM notification WHERE id_number = ?";
$notif_stmt = $con->prepare($notif_query);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();
$notif_count = $notif_result->fetch_assoc()['count'];

// Get materials with optional category filter
$category = $_GET['category'] ?? '';
$query = "SELECT * FROM lab_materials";
if ($category) {
    $query .= " WHERE category = ?";
}
$query .= " ORDER BY created_at DESC";

$stmt = $con->prepare($query);
if ($category) {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$materials = $stmt->get_result();

// Get unique categories for filter
$categories = $con->query("SELECT DISTINCT category FROM lab_materials ORDER BY category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .material-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
    <!-- Add SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Add Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a href="home.php" class="nav-link text-white">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="#" class="nav-link text-white position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                    <i class="fas fa-bell"></i> Notifications
                    <?php if ($notif_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $notif_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="history.php" class="nav-link text-white">
                    <i class="fas fa-history"></i> History
                </a>
                <a href="edit.php" class="nav-link text-white">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                </a>
                <a href="lab_materials.php" class="nav-link text-white active">
                    <i class="fa-solid fa-book"></i> Lab Materials
                </a>
                </a>
                <a href="view_schedules.php" class="nav-link text-white">
                   <i class="fas fa-calendar-alt"></i> Lab Schedules
               </a>
                <a href="reservation.php" class="nav-link text-white">
                    <i class="fas fa-calendar-check"></i> Reservation
                </a>
                <a href="index.php" class="btn btn-danger ms-lg-3">Log out</a>
            </div>
        </div>
    </nav>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="notificationModalLabel">
                        <i class="fas fa-bell"></i> Notifications
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $notifications_query = "SELECT notification_id, message FROM notification WHERE id_number = ? ORDER BY notification_id DESC";
                    $notifications_stmt = $con->prepare($notifications_query);
                    $notifications_stmt->bind_param("i", $user_id);
                    $notifications_stmt->execute();
                    $notifications = $notifications_stmt->get_result();

                    if ($notifications && $notifications->num_rows > 0) {
                        while ($row = $notifications->fetch_assoc()) {
                            echo '<div class="alert alert-info mb-2">';
                            echo '<div class="d-flex justify-content-between align-items-center">';
                            echo '<div><i class="fas fa-info-circle me-2"></i>' . htmlspecialchars($row['message']) . '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="text-center text-muted">';
                        echo '<i class="fas fa-bell-slash fa-2x mb-2"></i>';
                        echo '<p>No notifications available</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Lab Resources</h2>
            </div>
            <div class="col-auto">
                <select class="form-select" onchange="window.location.href='?category='+this.value">
                    <option value="">All Categories</option>
                    <option value="Programming" <?= $category === 'Programming' ? 'selected' : '' ?>>Programming</option>
                    <option value="Database" <?= $category === 'Database' ? 'selected' : '' ?>>Database</option>
                    <option value="Networking" <?= $category === 'Networking' ? 'selected' : '' ?>>Networking</option>
                    <option value="Web Development" <?= $category === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                    <option value="Others" <?= $category === 'Others' ? 'selected' : '' ?>>Others</option>
                </select>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while($material = $materials->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100 material-card">
                    <?php if($material['image_path']): ?>
                    <img src="/sysarch/uploads/materials/<?= basename(htmlspecialchars($material['image_path'])) ?>" 
                         class="card-img-top" alt="Material Image"
                         style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($material['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($material['description']) ?></p>
                        <a href="<?= htmlspecialchars($material['website_url']) ?>" 
                           class="btn btn-primary" target="_blank">
                            Visit Website
                        </a>
                    </div>
                    <div class="card-footer text-muted">
                        Category: <?= htmlspecialchars($material['category']) ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
