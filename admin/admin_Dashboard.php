<?php
session_start();
date_default_timezone_set('Asia/Manila'); // Add this line for Philippine timezone
include 'db.php'; // Include your database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_name = 'Mark'; // Replace with actual admin name if available
    $date = date('F d, Y h:i:s A'); // Format: Month Day, Year Hours:Minutes:Seconds AM/PM
    $message = $_POST['message'];

    $conn = openConnection();
    $stmt = $conn->prepare("INSERT INTO announce (admin_name, date, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_name, $date, $message);
    $stmt->execute();
    $stmt->close();
    closeConnection($conn);

    // Redirect to avoid form resubmission
    header("Location: admin_Dashboard.php");
    exit();
}

// Fetch statistics from database
$conn = openConnection();

// Get total registered students from user table
$student_query = "SELECT COUNT(*) as count FROM user WHERE role = 'student'";
$student_result = $conn->query($student_query);
$students_registered = $student_result->fetch_assoc()['count'];

// Get current active sit-ins
$active_query = "SELECT COUNT(*) as count FROM student_sit_in WHERE status = 'Active'";
$active_result = $conn->query($active_query);
$current_sit_in = $active_result->fetch_assoc()['count'];

// Get total sit-ins from student_sit_in table
$total_query = "SELECT COUNT(*) as count FROM student_sit_in";
$total_result = $conn->query($total_query);
$total_sit_in = $total_result->fetch_assoc()['count'];

// Update pie chart data based on sit-in purposes
$purpose_query = "SELECT sit_purpose, COUNT(*) as count FROM student_sit_in GROUP BY sit_purpose";
$purpose_result = $conn->query($purpose_query);
$purposes = [];
$purpose_counts = [];
while($row = $purpose_result->fetch_assoc()) {
    $purposes[] = $row['sit_purpose'];
    $purpose_counts[] = $row['count'];
}

// Get total registered users by role
$users_query = "SELECT role, COUNT(*) as count FROM user GROUP BY role";
$users_result = $conn->query($users_query);
$user_stats = array();
while($row = $users_result->fetch_assoc()) {
    $user_stats[$row['role']] = $row['count'];
}

// Get total users count (excluding admin)
$total_users_query = "SELECT COUNT(*) as total FROM user WHERE role != 'admin'";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total'];

// Fetch announcements from the database
$announcements = [];

// Update the announcement fetch query with better ordering
$sql = "SELECT * FROM announce ORDER BY date DESC, announce_id DESC";  // This ensures newest first by date and ID

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>College of Computer Studies Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            padding-top: 60px; /* Add space for fixed navbar */
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevent double scrollbars */
        }
        .main-content {
            height: calc(100vh - 60px); /* Subtract navbar height */
            overflow-y: auto;
            padding: 20px;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .highlight-text {
            background-color: #0d6efd;
            color: #FFFFFF;
            padding: 8px 12px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card {
            height: 100%; /* Ensures both cards are the same height */
        }
        .announcement-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
            padding-right: 5px;
        }
        .announcement-item {
            padding: 15px; /* Adds space around each announcement */
            margin-bottom: 10px; /* Separates announcements for better readability */
            border: 1px solid #ddd; /* Adds a light border for definition */
            border-radius: 5px; /* Softens the edges */
            background-color: #f9f9f9; /* Light background for contrast */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for a modern look */
        }
        .feedback-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
            padding-right: 5px;
        }
        .feedback-item {
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        /* Custom scrollbar styling */
        .announcement-container::-webkit-scrollbar,
        .feedback-container::-webkit-scrollbar {
            width: 8px;
        }
        .announcement-container::-webkit-scrollbar-track,
        .feedback-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .announcement-container::-webkit-scrollbar-thumb,
        .feedback-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .announcement-container::-webkit-scrollbar-thumb:hover,
        .feedback-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<?php include 'admin_navbar.php' ?>
<div class="main-content">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h4 class="highlight-text">Statistics</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center bg-primary text-white">
                            <h3><?php echo $total_users; ?></h3>
                            <p class="mb-0">Students Registered</p>   <!-- Changed from "Total Users" to "Students Registered" -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center bg-info text-white">
                            <h3><?php echo $current_sit_in; ?></h3>
                            <p class="mb-0">Currently Sit-in</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center bg-warning text-dark">
                            <h3><?php echo $total_sit_in; ?></h3>
                            <p class="mb-0">Total Sit-in</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <canvas id="statisticsChart" style="max-width: 400px; max-height: 400px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h4 class="highlight-text">Announcement</h4>
                <form method="POST" action="">
                    <textarea class="form-control mb-2" name="message" placeholder="Type your new announcement here..." rows="4" required></textarea>
                    <button type="submit" class="btn btn-success">Create</button>
                </form>
                <div class="announcement-container">
                    <ul class="list-unstyled">
                        <?php foreach ($announcements as $announcement) { ?>
                            <li class="announcement-item" id="announcement-<?php echo $announcement['announce_id']; ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($announcement['date']); ?></strong>
                                        <br>
                                        <span class="text-muted"><i class="fas fa-user"></i> Admin: <?php echo htmlspecialchars($announcement['admin_name']); ?></span>
                                        <p class="mt-2 announcement-text-<?php echo $announcement['announce_id']; ?>">
                                            <?php echo htmlspecialchars($announcement['message']); ?>
                                        </p>
                                    </div>
                                    <div class="btn-group ms-2">
                                        <button class="btn btn-sm btn-warning me-1" 
                                                onclick="editAnnouncement(<?php echo $announcement['announce_id']; ?>, this)">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="deleteAnnouncement(<?php echo $announcement['announce_id']; ?>, this)">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    

<!-- Add Edit Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editAnnouncementForm">
                    <input type="hidden" id="edit_announcement_id">
                    <textarea class="form-control" id="edit_announcement_message" rows="4" required></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateAnnouncement()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function editAnnouncement(id, button) {
    // Get the announcement text from the current announcement item
    const announcementText = document.querySelector(`.announcement-text-${id}`).innerText;
    
    // Set values in modal
    document.getElementById('edit_announcement_id').value = id;
    document.getElementById('edit_announcement_message').value = announcementText;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
    modal.show();
}

function updateAnnouncement() {
    const id = document.getElementById('edit_announcement_id').value;
    const message = document.getElementById('edit_announcement_message').value;
    
    if (!message.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Announcement cannot be empty!'
        });
        return;
    }

    fetch('process_announcement.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&id=${encodeURIComponent(id)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            // Update only the specific announcement text
            const announcementText = document.querySelector(`.announcement-text-${id}`);
            if (announcementText) {
                announcementText.textContent = message;
            }
            
            // Close modal and show success message
            bootstrap.Modal.getInstance(document.getElementById('editAnnouncementModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Announcement has been updated successfully.'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update announcement'
            });
        }
    });
}

function deleteAnnouncement(id, button) {
    // Get the specific announcement element
    const announcementItem = document.getElementById(`announcement-${id}`);
    if (!announcementItem) {
        console.error('Announcement element not found');
        return;
    }

    const announcementText = announcementItem.querySelector(`.announcement-text-${id}`).innerText;
    const preview = announcementText.length > 50 ? announcementText.substring(0, 50) + '...' : announcementText;

    Swal.fire({
        title: 'Delete Announcement?',
        text: `Are you sure you want to delete: "${preview}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('process_announcement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&id=${encodeURIComponent(id)}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    // Remove only the specific announcement element
                    announcementItem.remove();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The announcement has been deleted.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete announcement: ' + data
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete announcement'
                });
            });
        }
    });
}
</script>

<script>
    const ctx2 = document.getElementById('statisticsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($purposes); ?>,
            datasets: [{
                data: <?php echo json_encode($purpose_counts); ?>,
                backgroundColor: ['#36A2EB', '#FF6384', '#FF9F40', '#FFCE56', '#4BC0C0']
            }]
        },
        options: {
            maintainAspectRatio: false, // Ensures size flexibility
            responsive: true
        }
    });
</script>
</body>
</html>
