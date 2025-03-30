<?php
include('db.php');
include('admin_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .star-rating {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .feedback-status {
            font-weight: bold;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        .status-pending {
            color: #dc3545;
        }
        .status-read {
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Student Feedback</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Date</th>
                                <th>Student ID</th>
                                <th>Feedback</th>
                                <th>Admin Rating</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT f.*, u.fname, u.lname, u.id as student_id, 
                                    DATE_FORMAT(f.date_submitted, '%m/%d/%Y %l:%i %p') as formatted_date
                                    FROM feedback f 
                                    LEFT JOIN user u ON f.user_id = u.username 
                                    ORDER BY f.date_submitted DESC";
                            $result = mysqli_query($con, $query);
                            
                            while($row = mysqli_fetch_assoc($result)) {
                                $admin_rating = $row['admin_rating'] ?? 0;
                                $admin_stars = str_repeat('★', $admin_rating) . str_repeat('☆', 5 - $admin_rating);
                                $status_class = $row['status'] == 'pending' ? 'status-pending' : 'status-read';
                                $button_display = $row['status'] == 'pending' ? '' : 'disabled';
                                
                                echo "<tr>
                                        <td>".htmlspecialchars($row['formatted_date'])."</td>
                                        <td>".htmlspecialchars($row['student_id'])."</td>
                                        <td>".htmlspecialchars($row['message'])."</td>
                                        <td><span class='star-rating'>{$admin_stars}</span></td>
                                        <td><span class='feedback-status {$status_class}'>".ucfirst(htmlspecialchars($row['status']))."</span></td>
                                        <td>
                                            <button onclick='markAsRead({$row['id']})' class='btn btn-success btn-sm' {$button_display}>
                                                <i class='fas fa-check'></i> Mark as Read
                                            </button>
                                        </td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function markAsRead(feedbackId) {
        Swal.fire({
            title: 'Rate this feedback',
            html: `
                <div class="rating mb-3">
                    <div class="stars" style="font-size: 2em;">
                        <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Mark as Read',
            preConfirm: () => {
                const rating = document.querySelector('input[name="rating"]:checked')?.value;
                if (!rating) {
                    Swal.showValidationMessage('Please select a rating');
                    return false;
                }
                return rating;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const rating = result.value;
                console.log('Sending request with:', { id: feedbackId, rating: rating });
                
                fetch('update_feedback_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${feedbackId}&rating=${rating}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Server response:', data);
                    if(data === 'success') {
                        Swal.fire('Success!', 'Feedback marked as read with rating.', 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', 'Failed to update feedback status: ' + data, 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.fire('Error!', 'Failed to connect to server', 'error');
                });
            }
        });
    }
    </script>

    <style>
    .stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }
    .stars input {
        display: none;
    }
    .stars label {
        cursor: pointer;
        padding: 0 0.2em;
        color: #ddd;
    }
    .stars label:hover,
    .stars label:hover ~ label,
    .stars input:checked ~ label {
        color: #ffc107;
    }
    </style>
</body>
</html>
