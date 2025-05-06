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
      width: 60px;
      height: 80px; /* Increased height to accommodate number */
      border: 2px solid transparent;
      border-radius: 10px;
      display: flex;
      flex-direction: column; /* Stack items vertically */
      align-items: center;
      justify-content: center;
      font-size: 28px;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
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

        <div id="computerIcons" class="d-flex flex-wrap gap-2 justify-content-start"></div>
      </div>
    </div>
  </div>
</div>

<script>
 const labComputers = {
  '524': generateComputers(50),
  '526': generateComputers(50),
  '528': generateComputers(50),
  '530': generateComputers(50),
  '542': generateComputers(50),
  '544': generateComputers(50)
};

function generateComputers(count) {
  const computers = [];
  for (let i = 1; i <= count; i++) {
    computers.push(`PC-${String(i).padStart(2, '0')}`);
  }
  return computers;
}

function syncLabSelection() {
    const selectedLab = document.getElementById('lab').value;
    updateComputerControl(selectedLab);
}

function updateComputerControl(lab) {
    const container = document.getElementById('computerIcons');
    container.innerHTML = '';

    if (lab) {
        document.getElementById('labSelector').value = lab;
        
        // Clear previous selection
        const existingInput = document.getElementById('pcNumberInput');
        if (existingInput) {
            existingInput.remove();
        }

        // Create all PC icons first
        for (let i = 1; i <= 50; i++) {
            const icon = document.createElement('div');
            icon.className = 'computer-icon';
            icon.innerHTML = `
                <i class="fas fa-desktop"></i>
                <span class="pc-number">PC-${String(i).padStart(2, '0')}</span>
            `;
            icon.setAttribute('data-pc', i);
            icon.setAttribute('data-lab', lab);
            
            // Add tooltip for unavailable PCs
            icon.title = 'Click to select this PC';
            container.appendChild(icon);
        }

        // Check PC status from the database
        $.ajax({
            url: '../admin/get_lab_status.php',
            method: 'GET',
            data: { lab: lab },
            success: function(response) {
                if (response.pcs) {
                    response.pcs.forEach(pc => {
                        const pcElement = document.querySelector(`.computer-icon[data-pc="${pc.number}"][data-lab="${lab}"]`);
                        if (pcElement) {
                            if (!pc.is_active) {
                                pcElement.classList.add('unavailable');
                                pcElement.title = 'This PC is currently in use';
                                pcElement.onclick = null;
                            } else {
                                pcElement.onclick = function() {
                                    if (!this.classList.contains('unavailable')) {
                                        // Remove selection from all PCs
                                        document.querySelectorAll('.computer-icon').forEach(el => 
                                            el.classList.remove('selected'));
                                        
                                        // Add selection to clicked PC
                                        this.classList.add('selected');
                                        
                                        // Update form inputs
                                        const pcInput = document.getElementById('pcNumberInput') || 
                                            document.createElement('input');
                                        pcInput.type = 'hidden';
                                        pcInput.id = 'pcNumberInput';
                                        pcInput.name = 'pc_number';
                                        pcInput.value = pc.number;
                                        
                                        const labInput = document.getElementById('labNumberInput') || 
                                            document.createElement('input');
                                        labInput.type = 'hidden';
                                        labInput.id = 'labNumberInput';
                                        labInput.name = 'lab_number';
                                        labInput.value = lab;
                                        
                                        const form = document.getElementById('reservationForm');
                                        form.appendChild(pcInput);
                                        form.appendChild(labInput);
                                    }
                                };
                            }
                        }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to fetch PC status',
                    icon: 'error',
                    timer: 2000
                });
            }
        });
    }
}

// Add periodic refresh to keep PC status updated
setInterval(function() {
    const selectedLab = document.getElementById('lab').value;
    if (selectedLab) {
        updateComputerControl(selectedLab);
    }
}, 5000); // Check every 5 seconds

// Update the form submission handler
document.getElementById('reservationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Get form values
    const purpose = document.getElementById('purpose').value;
    const lab = document.getElementById('lab').value;
    const date = document.getElementById('date').value;
    const timeIn = document.getElementById('timeIn').value;
    const pcNumber = document.getElementById('pcNumberInput');
    
    // Validate all required fields
    if (!purpose || !lab || !date || !timeIn) {
        Swal.fire({
            title: 'Error!',
            text: 'Please fill in all required fields',
            icon: 'error',
            confirmButtonColor: '#d33'
        });
        return;
    }

    // Check if PC is selected
    if (!pcNumber || !pcNumber.value) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select a computer from the laboratory',
            icon: 'error',
            confirmButtonColor: '#d33'
        });
        return;
    }

    // Validate date
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select today or a future date',
            icon: 'error',
            confirmButtonColor: '#d33'
        });
        return;
    }

    // Show confirmation dialog
    Swal.fire({
        title: 'Confirm Reservation',
        html: `Are you sure you want to make a reservation for:<br>
               Lab ${lab}<br>
               PC ${pcNumber.value}<br>
               Date: ${date}<br>
               Time: ${timeIn}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit reservation'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>
</body>
</html>