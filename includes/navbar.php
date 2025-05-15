<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get notification count
$user_id = $_SESSION['id'] ?? null;
$notif_count = 0;

if ($user_id) {
    $notif_query = "SELECT COUNT(*) as count FROM notification WHERE id_number = ?";
    $notif_stmt = $con->prepare($notif_query);
    $notif_stmt->bind_param("i", $user_id);
    $notif_stmt->execute();
    $notif_result = $notif_stmt->get_result();
    $notif_count = $notif_result->fetch_assoc()['count'];
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">
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
            <a href="history.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> History
            </a>
            <a href="edit.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'edit.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="lab_materials.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'lab_materials.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-book"></i> Lab Materials
            </a>
            <a href="view_schedules.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'view_schedules.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> Lab Schedules
            </a>
            <a href="reservation.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="index.php" class="btn btn-danger ms-lg-3">Log out</a>
        </div>
    </div>
</nav>
