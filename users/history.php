<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];

// Update the user ID fetching section
$get_user_id_query = "SELECT id FROM user WHERE username = ?";
$user_stmt = $con->prepare($get_user_id_query);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_data = $user_result->fetch_assoc()) {
    $user_id = $user_data['id'];
    error_log("Found user ID: " . $user_id); // Debug log
} else {
    error_log("No user found for username: " . $username);
    $_SESSION['error'] = "User not found";
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .stars {
            display: flex;
            justify-content: center;
            flex-direction: row;
        }
        .star-label {
            cursor: pointer;
            font-size: 24px;
            color: #ddd;
            padding: 0 2px;
        }
        .star-label:hover,
        .star-label:hover ~ .star-label,
        input[type="radio"]:checked ~ .star-label,
        input[type="radio"]:hover ~ .star-label {
            color: #ffc107;
        }
        input[type="radio"] {
            display: none;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Make table header sticky */
        thead tr th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
        }
    </style>
</head>
<body class="w3-light-grey">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white">
                <i class="fas fa-home"></i> Home
            </a>
            <?php
            // Add notification count query
            $notif_query = "SELECT COUNT(*) as count FROM notification WHERE id_number = ?";
            $notif_stmt = $con->prepare($notif_query);
            $notif_stmt->bind_param("i", $user_id);
            $notif_stmt->execute();
            $notif_result = $notif_stmt->get_result();
            $notif_count = $notif_result->fetch_assoc()['count'];
            ?>
            <a href="#" class="nav-link text-white position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                <i class="fas fa-bell"></i> Notifications
                <?php if ($notif_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $notif_count; ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="history.php" class="nav-link text-white active">
                <i class="fas fa-history"></i> History
            </a>
            <a href="edit.php" class="nav-link text-white">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="lab_materials.php" class="nav-link text-white">
                <i class="fa-solid fa-book"></i> Lab Materials
            </a>
            <a href="view_schedules.php" class="nav-link text-white">
               <i class="fas fa-calendar-alt"></i> Lab Schedules
           </a>
            <a href="reservation.php" class="nav-link text-white">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="logout.php" class="btn btn-danger ms-lg-3">Log out</a>
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
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-history"></i> My Lab History</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="sit-in-tab" data-bs-toggle="tab" data-bs-target="#sit-in" type="button" role="tab">
                        Sit-in History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">
                        Reservation History
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="historyTabContent">
                <!-- Sit-in History Tab -->
                <div class="tab-pane fade show active" id="sit-in" role="tabpanel">
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>Lab Room</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Debug log
                                error_log("User ID for history: " . $user_id);

                                // Update the sit-in query section
                                $sit_in_query = "SELECT s.*, 
                                                 f.id as feedback_id, 
                                                 f.rating as rating,
                                                 f.message as feedback_text,
                                                 CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as has_feedback
                                                 FROM student_sit_in s
                                                 LEFT JOIN feedback f ON s.sit_id = f.sit_id AND f.user_id = ?
                                                 WHERE s.id_number = ?
                                                 ORDER BY s.sit_date DESC, s.time_in DESC";

                                $sit_stmt = $con->prepare($sit_in_query);
                                $sit_stmt->bind_param("ii", $user_id, $user_id); // Bind user_id twice
                                error_log("Executing sit-in query for user ID: " . $user_id); // Debug log
                                $sit_stmt->execute();
                                $result = $sit_stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $has_feedback = $row['has_feedback'] == 1;
                                        $stars = $has_feedback ? str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) : '☆☆☆☆☆';
                                        
                                        echo "<tr>
                                                <td>{$row['sit_date']}</td>
                                                <td>{$row['sit_purpose']}</td>
                                                <td>{$row['sit_lab']}</td>
                                                <td>{$row['time_in']}</td>
                                                <td>{$row['time_out']}</td>
                                                <td><span class='text-warning'>{$stars}</span></td>
                                                <td>";
                                        
                                        if ($row['status'] === 'Completed') {
                                            if (!$has_feedback) {
                                                echo "<button class='btn btn-warning btn-sm' onclick='showFeedbackModal({$row['sit_id']})'>
                                                        <i class='fas fa-heart'></i> Give Feedback
                                                    </button>";
                                            } else {
                                                echo "<button class='btn btn-secondary btn-sm' disabled>
                                                        <i class='fas fa-check'></i> Feedback Submitted
                                                    </button>";
                                            }
                                        } else {
                                            echo "<button class='btn btn-secondary btn-sm' disabled>
                                                    Not Available
                                                </button>";
                                        }
                                        echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No sit-in history found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reservations History Tab -->
                <div class="tab-pane fade" id="reservations" role="tabpanel">
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>Lab Room</th>
                                    <th>PC Number</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Update the reservations query section
                                $reservations_query = "SELECT * FROM reservation 
                                                      WHERE id_number = ? 
                                                      ORDER BY reservation_date DESC, reservation_time DESC";

                                $res_stmt = $con->prepare($reservations_query);
                                $res_stmt->bind_param("i", $user_id); // Changed to integer binding
                                error_log("Executing reservations query for user ID: " . $user_id); // Debug log
                                $res_stmt->execute();
                                $res_result = $res_stmt->get_result();

                                if ($res_result->num_rows > 0) {
                                    while($row = $res_result->fetch_assoc()) {
                                        $status_class = match($row['status']) {
                                            'pending' => 'warning',
                                            'active' => 'primary',
                                            'completed' => 'success',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                        
                                        echo "<tr>
                                                <td>{$row['reservation_date']}</td>
                                                <td>{$row['purpose']}</td>
                                                <td>Lab {$row['lab']}</td>
                                                <td>PC-{$row['pc_number']}</td>
                                                <td>" . (isset($row['actual_time_in']) ? $row['actual_time_in'] : $row['reservation_time']) . "</td>
                                                <td>" . (isset($row['actual_time_out']) ? $row['actual_time_out'] : 'N/A') . "</td>
                                                <td><span class='badge bg-{$status_class}'>" . ucfirst($row['status']) . "</span></td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No reservation history found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
function showFeedbackModal(sitInId) {
    Swal.fire({
        title: 'Rate Your Experience',
        html: `
            <div class="stars mb-3">
                <div class="d-flex justify-content-center">
                    <input type="radio" id="star5" name="rating" value="5"><label class="star-label" for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label class="star-label" for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label class="star-label" for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label class="star-label" for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label class="star-label" for="star1">★</label>
                </div>
            </div>
            <textarea id="feedback" class="form-control" placeholder="Please share your feedback..." rows="3"></textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'Submit',
        preConfirm: () => {
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            if (!rating) {
                Swal.showValidationMessage('Please select a rating');
                return false;
            }
            return {
                rating: rating,
                feedback: document.getElementById('feedback').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitFeedback(sitInId, result.value.rating, result.value.feedback);
        }
    });
}

function submitFeedback(sitInId, rating, feedback) {
    fetch('submit_feedback.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `sit_in_id=${sitInId}&rating=${rating}&feedback=${feedback}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                title: 'Success!',
                text: 'Thank you for your feedback!',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Find and update the button immediately
                const button = document.querySelector(`button[onclick="showFeedbackModal(${sitInId})"]`);
                if (button) {
                    const td = button.parentElement;
                    td.innerHTML = `
                        <button class='btn btn-secondary btn-sm' disabled>
                            <i class='fas fa-check'></i> Feedback Submitted
                        </button>
                    `;
                }
            });
        } else {
            Swal.fire('Error!', data.error || 'Failed to submit feedback', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Connection failed: ' + error, 'error');
    });
}
</script>
</body>
</html>
