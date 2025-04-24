<?php
// Start the session
session_start();

// Add at the top of the file after session_start():
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('db.php');
require_once '../check_active_sitin.php';
$conn = openConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$userData = null;
$error = null;

// Search logic
if (isset($_GET['search'])) {
    $searchID = trim($_GET['search']); // Remove unnecessary spaces

    if (!empty($searchID)) {
        // Updated query to include remaining_session
        $stmt = $conn->prepare("SELECT id, lname, fname, MName, course, level, email, image, remaining_session FROM user WHERE id = ?");
        $stmt->bind_param("s", $searchID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            $error = "No user found with that ID.";
        }
        $stmt->close();
    } else {
        $error = "Please enter a valid ID.";
    }
} 

closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
<!-- Navbar -->
<?php include 'admin_navbar.php' ?>
<!-- Search Bar -->
<div class="container mt-4">
    <form method="GET" class="input-group mb-4">
        <input type="text" name="search" class="form-control" placeholder="Enter ID Number..." required>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Display User Data -->
    <?php if ($userData): ?>
    <div class="card p-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-2">
                <?php
                if (!empty($userData['image'])) {
                    // Get just the filename from the stored path
                    $fileName = basename($userData['image']);
                    
                    // Construct path to uploads directory
                    $imageUrl = '/sysarch/uploads/' . $fileName;
                    
                    // Verify file exists and is valid image type
                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imageUrl;
                    if (!file_exists($fullPath) || !in_array(strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $imageUrl = '/sysarch/assets/PERSON.png';
                    }
                } else {
                    $imageUrl = '/sysarch/assets/PERSON.png';
                }
                ?>
                <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                     alt="Profile Image" 
                     class="img-fluid rounded-circle" 
                     style="width: 150px; height: 150px; object-fit: cover;" 
                     onerror="this.src='/sysarch/assets/PERSON.png';" />
            </div>
            <div class="col-md-10">
                <!-- Updated Data Display -->
                <p class="p-2"><strong>Name:</strong> <?php echo htmlspecialchars($userData['fname'] . ' ' . $userData['MName'] . ' ' . $userData['lname']); ?></p>
                <p class="p-2"><strong>ID:</strong> <?php echo htmlspecialchars($userData['id']); ?></p>
                <p class="p-2"><strong>Course:</strong> <?php echo htmlspecialchars($userData['course']); ?></p>
                <p class="p-2"><strong>Year Level:</strong> <?php echo htmlspecialchars($userData['level']); ?></p>
                <p class="p-2"><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                <p class="p-2"><strong>Remaining Sessions:</strong> <?php echo htmlspecialchars($userData['remaining_session']); ?></p>
                
                <?php 
                if ($userData['remaining_session'] > 0): 
                    if (!hasActiveSitIn($userData['id'])):
                ?>
                <!-- Existing sit-in form -->
                <form action="process_sitin.php" method="POST" class="mt-3">
                    <input type="hidden" name="id_number" value="<?php echo htmlspecialchars($userData['id']); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="sit_purpose" class="form-select" required>
                                <option value="">Select Purpose</option>
                                <option value="ASP.net">ASP.NET Programming</option>
                                <option value="C#">C# Programming</option>
                                <option value="C++">C++ Programming</option>
                                <option value="C">C Programming</option>
                                <option value="Database">Database</option>
                                <option value="DGILOG">Digital Logic & Design</option>
                                <option value="Embedded">Embedded System & IoT</option>
                                <option value="Java">Java Programming</option>
                                <option value="Others">Others</option>
                                <option value="PHP">PHP Programming</option>
                                <option value="Python">Python Programming</option>
                                <option value="System">System Architecture and Integration</option>
                                <option value="Web">Web Design & Development</option>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-4">
                            <select name="sit_lab" class="form-select" required>
                                <option value="">Select Laboratory</option>
                                <option value="517">517</option>
                                <option value="524">524</option>
                                <option value="526">526</option>
                                <option value="528">528</option>
                                <option value="530">530</option>
                                <option value="542">542</option>
                                <option value="544">544</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Record Sit-in</button>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                <div class="alert alert-warning mt-3">
                    User already has an active sit-in. Please end the current sit-in before starting a new one.
                </div>
                <?php 
                    endif;
                else: 
                ?>
                <div class="alert alert-warning mt-3">
                    No remaining sessions available. Cannot record new sit-in.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php elseif ($error): ?>
    <div class="alert alert-warning mt-3"> <?php echo $error; ?> </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
