<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];
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
            flex-direction: row-reverse;
            justify-content: center;
        }
        .star-label {
            cursor: pointer;
            font-size: 24px;
            color: #ddd;
            padding: 0 2px;
        }
        .star-label:hover,
        .star-label:hover ~ .star-label,
        input[type="radio"]:checked ~ .star-label {
            color: #ffc107;
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
            <a href="history.php" class="nav-link text-white active">
                <i class="fas fa-history"></i> History
            </a>
            <a href="edit.php" class="nav-link text-white">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="reservation.php" class="nav-link text-white">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-history"></i> My Lab History</h4>
        </div>
        <div class="card-body">
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
                        // Get user's ID first
                        $user_query = "SELECT id FROM user WHERE username = ?";
                        $user_stmt = $con->prepare($user_query);
                        $user_stmt->bind_param("s", $username);
                        $user_stmt->execute();
                        $user_result = $user_stmt->get_result();
                        $user_id = $user_result->fetch_assoc()['id'];

                        // Modified query to show all records but highlight newest ones
                        $sit_in_query = "SELECT s.*, 
                                       f.id as feedback_id,
                                       f.rating as rating,
                                       CASE 
                                           WHEN s.sit_id = (
                                               SELECT MAX(sit_id) 
                                               FROM student_sit_in 
                                               WHERE id_number = s.id_number 
                                               AND status = 'Completed'
                                           ) THEN 1 
                                           ELSE 0 
                                       END as is_newest
                                       FROM student_sit_in s
                                       LEFT JOIN feedback f ON s.sit_id = f.sit_id AND f.user_id = ?
                                       WHERE s.id_number = ? 
                                       AND s.status = 'Completed'
                                       ORDER BY s.sit_date DESC, s.sit_id DESC";
                        
                        $sit_stmt = $con->prepare($sit_in_query);
                        $sit_stmt->bind_param("si", $username, $user_id);
                        $sit_stmt->execute();
                        $result = $sit_stmt->get_result();

                        while($row = $result->fetch_assoc()) {
                            $has_feedback = isset($row['feedback_id']);
                            $is_newest = $row['is_newest'] == 1;
                            $stars = $has_feedback ? str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) : '☆☆☆☆☆';
                            
                            echo "<tr class='" . ($is_newest ? 'table-info' : '') . "'>
                                    <td>{$row['sit_date']}</td>
                                    <td>{$row['sit_purpose']}</td>
                                    <td>{$row['sit_lab']}</td>
                                    <td>{$row['time_in']}</td>
                                    <td>{$row['time_out']}</td>
                                    <td><span class='text-warning'>{$stars}</span></td>
                                    <td>";
                            
                            if ($is_newest && !$has_feedback) {
                                echo "<button class='btn btn-warning btn-sm' onclick='showFeedbackModal({$row['sit_id']})'>
                                        <i class='fas fa-star'></i> Rate Now!
                                    </button>";
                            } else if ($has_feedback) {
                                echo "<span class='badge bg-success'>Rated</span>";
                            } else {
                                echo "<span class='badge bg-secondary'>Not Available</span>";
                            }
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function showFeedbackModal(sitInId) {
    Swal.fire({
        title: 'Rate Your Experience',
        html: `
            <div class="stars mb-3">
                <div class="d-flex justify-content-center">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>
            </div>
            <textarea id="feedback" class="form-control" placeholder="Share your experience..." rows="3"></textarea>
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
    console.log('Submitting feedback:', { sitInId, rating, feedback }); // Debug line
    
    fetch('submit_feedback.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `sit_in_id=${sitInId}&rating=${rating}&feedback=${feedback}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Server response:', data); // Debug line
        if(data.includes('success')) {
            Swal.fire('Success!', 'Thank you for your feedback!', 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Error!', 'Failed to submit feedback: ' + data, 'error');
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
