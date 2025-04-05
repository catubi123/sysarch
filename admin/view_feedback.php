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
    <!-- Add FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Updated query to use created_at instead of date_submitted
                            $query = "SELECT f.*, u.fname, u.lname, u.id as student_id, 
                                    DATE_FORMAT(f.created_at, '%m/%d/%Y %l:%i %p') as formatted_date
                                    FROM feedback f 
                                    LEFT JOIN user u ON f.user_id = u.username 
                                    ORDER BY f.created_at DESC";
                            $result = mysqli_query($con, $query);
                            
                            if ($result && $result->num_rows > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $rating_stars = str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']);
                                    echo "<tr>
                                            <td>{$row['formatted_date']}</td>
                                            <td>{$row['student_id']}</td>
                                            <td>" . htmlspecialchars($row['message']) . "</td>
                                            <td><span class='text-warning'>{$rating_stars}</span></td>
                                            <td>
                                                <button type='button' class='btn btn-danger btn-sm' onclick='deleteFeedback({$row['id']})'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No feedback available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function markAsRead(feedbackId) {
        fetch('update_feedback_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${feedbackId}`
        })
        .then(response => response.text())
        .then(data => {
            if(data === 'success') {
                location.reload();
            } else {
                alert('Error updating feedback status');
            }
        })
        .catch(error => {
            alert('Error updating feedback status');
        });
    }

    function deleteFeedback(feedbackId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${feedbackId}`
                })
                .then(response => response.text())
                .then(data => {
                    if(data === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'Feedback has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Failed to delete feedback.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'Something went wrong!',
                        'error'
                    );
                });
            }
        });
    }
    </script>
</body>
</html>
