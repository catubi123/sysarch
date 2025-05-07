<?php
session_start();
require_once('db.php'); // Change include to require_once

// Add user data fetch for notifications like in home.php
$user_id = $_SESSION['id'];
$notif_query = "SELECT COUNT(*) as count FROM notification WHERE id_number = ?";
$notif_stmt = $con->prepare($notif_query);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();
$notif_count = $notif_result->fetch_assoc()['count'];

// Check if user is logged in and has necessary session data
if (!isset($_SESSION['id']) || !isset($_SESSION['studentName'])) {
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['studentName'] = $row['fname'] . ' ' . $row['lname'];
    } else {
        // Redirect to login if user data can't be found
        header("Location: index.php");
        exit();
    }
}

// Verify database connection
if (!isset($con) || $con->connect_error) {
    die("Database connection failed: " . ($con->connect_error ?? "Connection not established"));
}

$id = $_SESSION['id'];
$studentName = $_SESSION['studentName'];

// Check for existing reservation first
if ($id) {
    $check_pending = "SELECT COUNT(*) as pending_count FROM reservation WHERE id_number = ? AND status = 'pending'";
    $stmt = $con->prepare($check_pending);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pending = $result->fetch_assoc();

    if ($pending['pending_count'] > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Active Reservation Found',
                    text: 'You already have a pending reservation. Please wait for it to be processed before making a new one.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Return to Dashboard',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'home.php';
                    }
                });
            });
        </script>";
    }
}

// Define selected_lab before using it
$selected_lab = $_POST['lab'] ?? null;

if ($selected_lab) {
    // Query to get available PCs
    $query = "SELECT pn.* 
              FROM pc_numbers pn
              LEFT JOIN pc_status ps ON pn.lab_number = ps.lab_number 
              AND pn.pc_number = ps.pc_number 
              WHERE (ps.is_active IS NULL OR ps.is_active = 1) 
              AND pn.lab_number = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $selected_lab);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display available PCs
    while($row = $result->fetch_assoc()) {
        echo '<div class="computer-icon" data-pc="' . $row['pc_number'] . '">';
        echo '<i class="fas fa-desktop"></i>';
        echo '<span class="pc-number">PC-' . str_pad($row['pc_number'], 2, '0', STR_PAD_LEFT) . '</span>';
        echo '</div>';
    }
}

// SweetAlert success/error handlers
if (isset($_SESSION['swal_success'])) {
    $swal = $_SESSION['swal_success'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '" . $swal['title'] . "',
                text: '" . $swal['text'] . "',
                icon: '" . $swal['icon'] . "',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                timer: 3000
            });
        });
    </script>";
    unset($_SESSION['swal_success']);
}

if (isset($_SESSION['swal_error'])) {
    $swal = $_SESSION['swal_error'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '" . $swal['title'] . "',
                text: '" . $swal['text'] . "',
                icon: '" . $swal['icon'] . "',
                confirmButtonColor: '#d33'
            });
        });
    </script>";
    unset($_SESSION['swal_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservation Form</title>
  <link rel="stylesheet" href="w3.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- Add SweetAlert2 CSS and JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .card {
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .header-gradient {
      background: linear-gradient(45deg, #0d6efd, #0a58ca);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      margin: -1rem -1rem 1.5rem -1rem;
    }
    .form-floating {
      margin-bottom: 1rem;
    }
    .form-control:read-only {
      background-color: #f8f9fa;
    }
    .required::after {
      content: "*";
      color: red;
      margin-left: 4px;
    }
    .computer-icon {
      width: 100%;
      height: 80px;
      border: 2px solid #dee2e6;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
      margin: 0;
      padding: 5px;
    }
    .computer-icon:hover {
      background-color: #e2e6ea;
    }
    .computer-icon.selected {
      border-color: #0d6efd;
      background-color: #cfe2ff;
    }
    .computer-icon.unavailable {
      opacity: 0.5;
      cursor: not-allowed;
    }
    .pc-number {
      font-size: 12px;
      margin-top: 5px;
      color: #666;
    }
    .computer-grid-container {
      display: grid;
      grid-template-columns: repeat(10, 1fr); /* Changed from 5 to 10 columns */
      gap: 10px;
      padding: 15px;
      max-height: 600px;
      overflow-y: auto;
      background: #fff;
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <div class="navbar-nav ms-auto">
      <a href="home.php" class="nav-link text-white">
        <i class="fas fa-home"></i> Home
      </a>
      <a href="#" class="nav-link text-white position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
        <i class="fas fa-bell"></i> Notifications
        <?php if ($notif_count > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?php echo $notif_count; ?>
          </span>
        <?php endif; ?>
      </a>
      <a href="history.php" class="nav-link text-white">
        <i class="fas fa-history"></i> History
      </a>
      <a href="edit.php" class="nav-link text-white">
        <i class="fa-solid fa-pen-to-square"></i> Edit Profile
      </a>
      <a href="lab_materials.php" class="nav-link text-white">
        <i class="fa-solid fa-book"></i> Lab Materials
      </a>
      <a href="reservation.php" class="nav-link text-white active">
        <i class="fas fa-calendar-check"></i> Reservation
      </a>
      <a href="index.php" class="btn btn-danger ms-lg-3">Log out</a>
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
  <div class="row">
    <!-- Left Column: Laboratory Reservation -->
    <div class="col-md-6">
      <div class="card p-3">
        <div class="header-gradient">
          <h2 class="text-center mb-0"><i class="fas fa-calendar-plus"></i> Laboratory Reservation</h2>
        </div>

        <!-- Reservation Form -->
        <form method="POST" action="process_reservation.php" id="reservationForm" class="needs-validation" novalidate>
          <div class="row g-3">
            <!-- Left Column -->
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control" id="idNumber" name="idNumber" value="<?php echo isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) : ''; ?>" readonly />
                <label><i class="fas fa-id-card"></i> ID Number</label>
              </div>

              <div class="form-floating">
                <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo isset($_SESSION['studentName']) ? htmlspecialchars($_SESSION['studentName']) : ''; ?>" readonly />
                <label><i class="fas fa-user"></i> Student Name</label>
              </div>

              <div class="form-floating">
                <select class="form-select" id="purpose" name="purpose" required>
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
                <label class="required"><i class="fas fa-tasks"></i> Purpose</label>
                <div class="invalid-feedback">Please select a purpose</div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
              <div class="form-floating">
                <select class="form-select" id="lab" name="lab" required onchange="syncLabSelection()">
                  <option value="">Select Laboratory</option>
                  <option value="524">524</option>
                  <option value="526">526</option>
                  <option value="528">528</option>
                  <option value="530">530</option>
                  <option value="542">542</option>
                  <option value="544">544</option>
                </select>
                <label class="required"><i class="fas fa-laptop"></i> Laboratory</label>
                <div class="invalid-feedback">Please select a laboratory</div>
              </div>

              <div class="form-floating">
                <input type="date" class="form-control" id="date" name="date" required />
                <label class="required"><i class="fas fa-calendar"></i> Date</label>
                <div class="invalid-feedback">Please select a date</div>
              </div>

              <div class="form-floating">
                <input type="time" class="form-control" id="timeIn" name="timeIn" required />
                <label class="required"><i class="fas fa-clock"></i> Time In</label>
                <div class="invalid-feedback">Please select a time</div>
              </div>
            </div>

            <!-- Full Width Elements -->
            <div class="col-12 position-relative">
            </div>

            <div class="col-12">
              <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Important Information:
                <ul class="mb-0">
                  <li>Laboratory is open 24/7</li>
                  <li>Fields marked with <span class="text-danger">*</span> are required</li>
                  <li>Please be responsible with laboratory equipment usage</li>
                  <li>Reservations can be made for any time</li>
                </ul>
              </div>
            </div>

            <div class="col-12 d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-calendar-check"></i> Submit Reservation</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Column: Computer Control -->
    <div class="col-md-6">
      <div class="card p-3">
        <div class="header-gradient">
          <h4 class="text-center mb-0"><i class="fas fa-computer"></i> Computer Control</h4>
        </div>

        <div class="form-floating mb-3 mt-3">
          <select class="form-select" id="labSelector" disabled>
            <option value="">Select Laboratory</option>
            <option value="524">524</option>
            <option value="526">526</option>
            <option value="528">528</option>
            <option value="530">530</option>
            <option value="542">542</option>
            <option value="544">544</option>
          </select>
          <label for="labSelector"><i class="fas fa-laptop-code"></i> Select Lab</label>
        </div>

        <div id="computerGrid" class="computer-grid-container">
            <!-- PCs will be loaded here -->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Modify syncLabSelection function to remove auto-refresh
function syncLabSelection() {
    const selectedLab = document.getElementById('lab').value;
    document.getElementById('labSelector').value = selectedLab;
    if (selectedLab) {
        updateComputerControl(selectedLab);
    }
}

function updateComputerControl(lab) {
    const container = document.getElementById('computerGrid');
    if (!lab) return;
    
    container.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading PCs...</div>';

    $.ajax({
        url: '../admin/get_pc_status.php',
        method: 'GET',
        data: { lab: lab },
        success: function(response) {
            console.log('PC Status Response:', response); // Debug line
            container.innerHTML = '';
            
            if (response && response.success) {
                const pcs = response.pcs || [];
                
                // Always generate 50 PCs
                for (let i = 1; i <= 50; i++) {
                    const pc = pcs.find(p => p.number === i) || { number: i, is_active: true };
                    const icon = document.createElement('div');
                    
                    // Set unavailable class if PC is not active
                    icon.className = `computer-icon ${(!pc.is_active) ? 'unavailable' : ''}`;
                    
                    icon.innerHTML = `
                        <i class="fas fa-desktop"></i>
                        <span class="pc-number">PC ${String(i).padStart(2, '0')}</span>
                        ${(!pc.is_active) ? '<span class="badge bg-danger">In Use</span>' : ''}
                    `;
                    icon.setAttribute('data-pc', i);
                    
                    if (pc.is_active) {
                        icon.onclick = function() {
                            selectPC(this, i);
                        };
                        icon.title = `Click to select PC ${String(i).padStart(2, '0')}`;
                    } else {
                        icon.title = `PC ${String(i).padStart(2, '0')} is currently in use`;
                    }
                    
                    container.appendChild(icon);
                }
            } else {
                container.innerHTML = '<div class="alert alert-warning">Error loading PCs: ' + 
                    (response.error || 'Unknown error') + '</div>';
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            container.innerHTML = '<div class="alert alert-danger">Failed to load PCs. Please try again.</div>';
        }
    });
}

// Add selectPC function
function selectPC(element, pcNumber) {
    if (element.classList.contains('unavailable')) {
        Swal.fire({
            title: 'Not Available',
            text: 'This PC is currently not available',
            icon: 'warning',
            timer: 1500
        });
        return;
    }

    // Remove selection from all PCs
    document.querySelectorAll('.computer-icon').forEach(pc => 
        pc.classList.remove('selected'));
    
    // Add selection to clicked PC
    element.classList.add('selected');
    
    // Create or update hidden input for PC number
    let pcInput = document.getElementById('pcNumberInput');
    if (!pcInput) {
        pcInput = document.createElement('input');
        pcInput.type = 'hidden';
        pcInput.id = 'pcNumberInput';
        pcInput.name = 'pc_number';
        document.getElementById('reservationForm').appendChild(pcInput);
    }
    pcInput.value = pcNumber;

    Swal.fire({
        title: 'PC Selected',
        text: `You selected PC ${String(pcNumber).padStart(2, '0')}`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

// Update the form submission handler
document.getElementById('reservationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Check if a PC is selected
    const pcNumber = document.getElementById('pcNumberInput')?.value;
    if (!pcNumber) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select a PC before submitting',
            icon: 'error'
        });
        return;
    }

    // Show loading state
    Swal.fire({
        title: 'Submitting...',
        text: 'Please wait while we process your reservation',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit form
    this.submit();
});

// Add form validation
document.getElementById('reservationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Check if a PC is selected
    const pcNumber = document.getElementById('pcNumberInput')?.value;
    if (!pcNumber) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select a PC before submitting',
            icon: 'error'
        });
        return;
    }

    // Continue with form submission if PC is selected
    this.submit();
});
</script>
</body>
</html>