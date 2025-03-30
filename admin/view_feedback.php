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
                                $status_class = $row['status'] == 'pending' ? 'text-danger' : 'text-success';
                                $button_display = $row['status'] == 'pending' ? '' : 'disabled';
                                echo "<tr>
                                        <td>".htmlspecialchars($row['formatted_date'])."</td>
                                        <td>".htmlspecialchars($row['student_id'])."</td>
                                        <td>".htmlspecialchars($row['message'])."</td>
                                        <td class='{$status_class}'>".ucfirst(htmlspecialchars($row['status']))."</td>
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
    </script>
</body>
</html>
