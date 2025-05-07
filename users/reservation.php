<?php
session_start();
require_once('db.php'); // Change include to require_once

// Check if user is logged in and has necessary session data
if (!isset($_SESSION['id']) || !isset($_SESSION['studentName'])) {
    // If no session data, fetch from database
    $query = "SELECT id, fname, lname FROM user WHERE username = ?";
    $stmt = $con->prepare($query);
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
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <div class="navbar-nav ms-auto">
      <a href="home.php" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
      <a href="#" class="nav-link text-white"><i class="fas fa-history"></i> History</a>
      <a href="edit.php" class="nav-link text-white"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</a>
      <a href="reservation.php" class="nav-link text-white"><i class="fas fa-calendar-check"></i> Reservation</a>
      <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
    </div>
  </div>
</nav>

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
              <div class="form-floating">
                <input type="text" class="form-control" id="remainingSession" name="remainingSession" value="30" readonly />
                <label><i class="fas fa-hourglass-half"></i> Session Duration</label>
              </div>
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
            console.log('Response:', response); // Debug line
            container.innerHTML = '';
            
            if (response && response.success && Array.isArray(response.pcs)) {
                // Create PC icons in numerical order
                for (let i = 1; i <= 50; i++) {
                    const pc = response.pcs.find(p => p.number === i) || { number: i, is_active: true };
                    const icon = document.createElement('div');
                    icon.className = `computer-icon ${pc.is_active ? '' : 'unavailable'}`;
                    
                    icon.innerHTML = `
                        <i class="fas fa-desktop"></i>
                        <span class="pc-number">PC ${String(i).padStart(2, '0')}</span>
                    `;
                    icon.setAttribute('data-pc', i);
                    
                    if (pc.is_active) {
                        icon.onclick = function() {
                            selectPC(this, i);
                        };
                        icon.title = `Click to select PC ${String(i).padStart(2, '0')}`;
                    } else {
                        icon.title = `PC ${String(i).padStart(2, '0')} is not available`;
                    }
                    
                    container.appendChild(icon);
                }
            } else {
                container.innerHTML = '<div class="alert alert-warning">No PCs found for this laboratory</div>';
                console.error('Invalid response format:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.log('Response Text:', xhr.responseText); // Debug line
            container.innerHTML = '<div class="alert alert-danger">Error loading PCs. Please try again.</div>';
        }
    });
}

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
    
    // Update hidden input
    let pcInput = document.getElementById('pcNumberInput');
    if (!pcInput) {
        pcInput = document.createElement('input');
        pcInput.type = 'hidden';
        pcInput.id = 'pcNumberInput';
        pcInput.name = 'pc_number';
        document.getElementById('reservationForm').appendChild(pcInput);
    }
    pcInput.value = pcNumber;

    // Show selection feedback
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
    
    // Validate PC selection
    const pcNumber = document.getElementById('pcNumberInput')?.value;
    if (!pcNumber) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select a PC before submitting',
            icon: 'error'
        });
        return;
    }

    // Validate date
    const selectedDate = new Date(document.getElementById('date').value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        Swal.fire({
            title: 'Invalid Date',
            text: 'Please select today or a future date',
            icon: 'error'
        });
        return;
    }

    // Show confirmation dialog
    Swal.fire({
        title: 'Confirm Reservation',
        html: `Are you sure you want to reserve:<br>
               Lab ${document.getElementById('lab').value}<br>
               PC ${String(pcNumber).padStart(2, '0')}<br>
               Date: ${document.getElementById('date').value}<br>
               Time: ${document.getElementById('timeIn').value}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit reservation'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form via AJAX
            const formData = new FormData(this);
            
            $.ajax({
                url: 'process_reservation.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Reservation Submitted!',
                            html: `Your reservation details:<br>
                                  Lab: ${response.details.lab}<br>
                                  PC: ${String(response.details.pc).padStart(2, '0')}<br>
                                  Date: ${response.details.date}<br>
                                  Time: ${response.details.time}`,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Clear form and reset PC selection
                            document.getElementById('reservationForm').reset();
                            document.querySelectorAll('.computer-icon').forEach(pc => 
                                pc.classList.remove('selected'));
                            document.getElementById('pcNumberInput')?.remove();
                            
                            // Reset lab selection and computer grid
                            document.getElementById('lab').value = '';
                            document.getElementById('labSelector').value = '';
                            document.getElementById('computerGrid').innerHTML = '';

                            // Set default date and time again
                            const today = new Date().toISOString().split('T')[0];
                            document.getElementById('date').value = today;
                            const now = new Date();
                            now.setMinutes(0);
                            document.getElementById('timeIn').value = now.toTimeString().slice(0, 5);
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to submit reservation. Please try again.',
                        icon: 'error'
                    });
                }
            });
        }
    });
});

// Initialize form validation and date/time inputs
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').min = today;
    document.getElementById('date').value = today;

    // Set default time to current hour
    const now = new Date();
    now.setMinutes(0);
    document.getElementById('timeIn').value = now.toTimeString().slice(0, 5);
});
</script>
</body>
</html>