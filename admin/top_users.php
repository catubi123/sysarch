<?php
session_start();
include 'db.php';

$conn = openConnection();

// Fetch top 10 users based on points
$query = "SELECT id, fname, MName, lname, Course, points, image 
          FROM user 
          WHERE role = 'user' 
          ORDER BY points DESC 
          LIMIT 10";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Top Users - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .user-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .points-badge {
            font-size: 1.1em;
            padding: 8px 15px;
        }
        .rank-column {
            width: 80px;
            text-align: center;
            font-weight: bold;
        }
        .text-bronze {
            color: #cd7f32;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'admin_navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Top Users Leaderboard</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="rank-column">Rank</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            while($user = $result->fetch_assoc()): 
                                $fullName = $user['fname'] . ' ' . $user['MName'] . ' ' . $user['lname'];
                                $image = 'PERSON.png'; // Default image in assets folder
                            ?>
                            <tr>
                                <td class="rank-column">
                                    <?php 
                                    $rankClass = '';
                                    if($rank == 1) $rankClass = 'text-warning'; // Gold
                                    elseif($rank == 2) $rankClass = 'text-secondary'; // Silver
                                    elseif($rank == 3) $rankClass = 'text-bronze'; // Bronze
                                    ?>
                                    <span class="<?php echo $rankClass; ?>">#<?php echo $rank++; ?></span>
                                </td>
                                <td>
                                    <img src="../assets/<?php echo $image; ?>" 
                                         alt="Profile" 
                                         class="user-image">
                                </td>
                                <td><?php echo htmlspecialchars($fullName); ?></td>
                                <td><?php echo htmlspecialchars($user['Course']); ?></td>
                                <td>
                                    <span class="badge bg-success points-badge">
                                        <?php echo number_format($user['points']); ?> pts
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
