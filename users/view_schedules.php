<?php
session_start();
require_once('db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Get user ID for notifications
$username = $_SESSION['username'];
$user_query = "SELECT id FROM user WHERE username = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];

// Get notification count
$notif_query = "SELECT COUNT(*) as count FROM notification WHERE id_number = ?";
$notif_stmt = $con->prepare($notif_query);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();
$notif_count = $notif_result->fetch_assoc()['count'];

// Get selected laboratory filter
$selected_lab = isset($_GET['lab']) ? $_GET['lab'] : 'all';

// Modify query for prepared statement
$query = "SELECT * FROM lab_schedules";
$params = [];
$types = "";

if ($selected_lab !== 'all') {
    $query .= " WHERE lab_number = ?";
    $params[] = $selected_lab;
    $types .= "s";
}

$query .= " ORDER BY 
    CASE 
        WHEN day = 'Monday' THEN 1
        WHEN day = 'Tuesday' THEN 2
        WHEN day = 'Wednesday' THEN 3
        WHEN day = 'Thursday' THEN 4
        WHEN day = 'Friday' THEN 5
        WHEN day = 'Saturday' THEN 6
    END, 
    start_time";

$stmt = $con->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$schedules = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .schedule-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .schedule-header {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
            padding: 1rem;
        }
        .schedule-table th {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
        }
        .badge-lab {
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 8px;
        }
        .day-filter {
            cursor: pointer;
            padding: 5px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .day-filter.active {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <?php include('../includes/navbar.php'); ?>

    <div class="container mt-4">
        <div class="schedule-card">
            <div class="schedule-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= $selected_lab === 'all' ? 'All Laboratories' : 'Lab ' . htmlspecialchars($selected_lab) ?> Schedule
                </h3>
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm me-2" id="labFilter" onchange="filterByLab(this.value)">
                        <option value="all">All Laboratories</option>
                        <?php
                        $labs = ['524', '526', '528', '530', '542', '544'];
                        foreach ($labs as $lab) {
                            $selected = $selected_lab === $lab ? 'selected' : '';
                            echo "<option value=\"$lab\" $selected>Lab $lab</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Professor</th>
                            <?php if ($selected_lab === 'all'): ?>
                                <th>Laboratory</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($schedules->num_rows > 0): ?>
                            <?php while ($schedule = $schedules->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($schedule['day']) ?></td>
                                    <td>
                                        <?= date('h:i A', strtotime($schedule['start_time'])) ?> - 
                                        <?= date('h:i A', strtotime($schedule['end_time'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($schedule['subject']) ?></td>
                                    <td><?= htmlspecialchars($schedule['professor_name']) ?></td>
                                    <?php if ($selected_lab === 'all'): ?>
                                        <td>
                                            <span class="badge bg-primary">
                                                Lab <?= htmlspecialchars($schedule['lab_number']) ?>
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $selected_lab === 'all' ? '5' : '4' ?>" class="text-center">
                                    <div class="p-3">
                                        <i class="fas fa-calendar-xmark text-muted fs-4"></i>
                                        <p class="mb-0 mt-2">No schedules found for <?= $selected_lab === 'all' ? 'any laboratory' : 'Lab ' . htmlspecialchars($selected_lab) ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterByLab(lab) {
            window.location.href = 'view_schedules.php?lab=' + lab;
        }

        // Add active class to current lab in filter
        document.addEventListener('DOMContentLoaded', function() {
            const labFilter = document.getElementById('labFilter');
            const currentLab = '<?= $selected_lab ?>';
            if (labFilter) {
                labFilter.value = currentLab;
            }
        });
    </script>
</body>
</html>
