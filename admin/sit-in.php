<?php
session_start();
include('db.php');
$conn = openConnection();

// Query for sit-ins only
$sql = "SELECT s.*, u.fname, u.lname, u.course, u.level 
        FROM student_sit_in s
        JOIN user u ON s.id_number = u.id
        WHERE s.status = 'Active'
        ORDER BY s.sit_date DESC, s.time_in DESC";
$result = $conn->query($sql);

// Debug output for sit-ins query
if (!$result) {
    error_log("Sit-ins query error: " . $conn->error);
}

// Modified query for active reservations with proper field selection
$reservations_sql = "SELECT r.*, 
                    u.fname, u.lname, u.course, u.level, 
                    u.points as user_points,
                    COALESCE(remaining_sessions, 0) as remaining_sessions,
                    CASE WHEN r.points_awarded = 1 THEN 1 ELSE 0 END as points_awarded
                    FROM reservation r
                    JOIN user u ON r.id_number = u.id
                    WHERE r.status = 'active' 
                    ORDER BY r.actual_time_in DESC";

$reservations_result = $conn->query($reservations_sql);

// Add error logging
if (!$reservations_result) {
    error_log("Reservations query error: " . $conn->error);
    die("Error fetching reservations: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sit-ins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 20px;
        }
        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }
        .computer-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin-top: 10px;
        }

        .computer-icon {
            aspect-ratio: 1;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            padding: 5px;
        }

        .computer-icon:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        .computer-icon.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .computer-icon.unavailable {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }

        .pc-number {
            font-size: 12px;
            margin-top: 5px;
        }

        .small-dropdown {
            font-size: 0.875rem;
        }
        .select2-container .select2-selection--single {
            height: 36px;
        }
        .form-select, .form-control {
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-white">
    <?php include 'admin_navbar.php' ?>
    
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Laboratory Sit-in Management</h3>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="sitInTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab">
                            <i class="fas fa-users"></i> Current Sit-ins
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">
                            <i class="fas fa-calendar-check"></i> Current Reservations
                        </button>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="sitInTabContent">
                    <!-- Current Sit-ins Tab -->
                    <div class="tab-pane fade show active" id="current" role="tabpanel">
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Number</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Purpose</th>
                                        <th>Laboratory</th>
                                        <th>Time In</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sit_purpose']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sit_lab']); ?></td>
                                        <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sit_date']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <form action="timeout_sitin.php" method="POST" class="me-1">
                                                    <input type="hidden" name="sit_id" value="<?php echo $row['sit_id']; ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm">Time Out</button>
                                                </form>
                                                <form action="add_point.php" method="POST">
                                                    <input type="hidden" name="sit_id" value="<?php echo $row['sit_id']; ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['id_number']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-plus"></i> Add Point
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Current Reservations Tab -->
                    <div class="tab-pane fade" id="reservations" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Number</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Purpose</th>
                                        <th>Laboratory</th>
                                        <th>PC Number</th>
                                        <th>Time In</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $reservations_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                                        <td>Lab <?php echo htmlspecialchars($row['lab']); ?></td>
                                        <td>PC <?php echo htmlspecialchars($row['pc_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['actual_time_in']); ?></td>
                                        <td><?php echo htmlspecialchars($row['reservation_date']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <form action="end_reservation.php" method="POST" class="me-1">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm">Time Out</button>
                                                </form>
                                                <?php if (!$row['points_awarded']): ?>
                                                    <?php if ($row['remaining_sessions'] < 3): ?>
                                                        <form action="add_reservation_point.php" method="POST">
                                                            <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                            <input type="hidden" name="user_id" value="<?php echo $row['id_number']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                Add Point (Sessions: <?php echo $row['remaining_sessions']; ?>/3)
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm" disabled title="Maximum sessions reached">
                                                            Max Sessions (3/3)
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>Points Awarded</button>
                                                <?php endif; ?>
                                            </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select a student',
                dropdownCssClass: 'small-dropdown'
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL parameters for active tab
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'reservations') {
                // Get tab elements
                const reservationsTab = document.querySelector('#reservations-tab');
                const reservationsPane = document.querySelector('#reservations');
                const currentTab = document.querySelector('#current-tab');
                const currentPane = document.querySelector('#current');
                
                // Switch to reservations tab
                if (reservationsTab && reservationsPane && currentTab && currentPane) {
                    currentTab.classList.remove('active');
                    currentPane.classList.remove('show', 'active');
                    reservationsTab.classList.add('active');
                    reservationsPane.classList.add('show', 'active');
                }
            }
        });
    </script>
</body>
</html>