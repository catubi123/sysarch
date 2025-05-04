<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Get user data and check existing reservations
$username = $_SESSION['username'];
$query = "SELECT u.id, CONCAT(u.lname, ', ', u.fname, ' ', u.MName) as full_name,
          (SELECT COUNT(*) FROM reservation r WHERE r.id_number = u.id AND r.status = 'pending') as pending_reservations
          FROM user u WHERE u.username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $_SESSION['id'] = $user_data['id'];
    $_SESSION['studentName'] = $user_data['full_name'];
    $has_pending = $user_data['pending_reservations'] > 0;
}

$id = $_SESSION['id'] ?? '';
$studentName = $_SESSION['studentName'] ?? '';

// Add JavaScript variables for SweetAlert
echo "<script>
    const hasPendingReservation = " . ($has_pending ? 'true' : 'false') . ";
    const studentName = '" . addslashes($studentName) . "';
</script>";
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
  <style>
    .card {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      border: none;
      border-radius: 1rem;
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
      width: 70px;
      height: 70px;
      border: 2px solid transparent;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
      padding: 5px;
      margin: 5px;
    }
    .computer-icon:hover {
      background-color: #e2e6ea;
      transform: translateY(-2px);
    }
    .computer-icon.selected {
      border-color: #0d6efd;
      background-color: #cfe2ff;
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
                <input type="text" class="form-control" id="idNumber" name="idNumber" 
                       value="<?php echo htmlspecialchars($id); ?>" readonly />
                <label><i class="fas fa-id-card"></i> ID Number</label>
              </div>

              <div class="form-floating">
                <input type="text" class="form-control" id="studentName" name="studentName" 
                       value="<?php echo htmlspecialchars($studentName); ?>" readonly />
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
                <div class="invalid-feedback">Please select a time between 8:00 AM and 5:00 PM</div>
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
                </ul>
              </div>
            </div>

            <div class="col-12 d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-calendar-check"></i> Submit Reservation</button>
            </div>
          </div>
          <input type="hidden" id="selectedPC" name="pc_number" value="">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    if (labComputers[lab]) {
        document.getElementById('labSelector').value = lab;
        labComputers[lab].forEach((pc, index) => {
            const icon = document.createElement('div');
            icon.className = 'computer-icon';
            icon.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-desktop"></i>
                    <div style="font-size: 12px; margin-top: 5px;">${pc}</div>
                </div>
            `;
            icon.title = pc;
            icon.onclick = function () {
                document.querySelectorAll('.computer-icon').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                // Store PC number (1-based index)
                document.getElementById('selectedPC').value = index + 1;
                console.log(`Selected PC: ${index + 1}`);
            };
            container.appendChild(icon);
        });
    }
}

function validateAndSubmit(event) {
    event.preventDefault();

    if (hasPendingReservation) {
        Swal.fire({
            icon: 'error',
            title: 'Cannot Create Reservation',
            text: 'You already have a pending reservation. Please wait for it to be completed.',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }

    const selectedPC = document.getElementById('selectedPC').value;
    if (!selectedPC) {
        Swal.fire({
            icon: 'error',
            title: 'No PC Selected',
            text: 'Please select a computer before submitting the reservation.',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }

    const form = event.target;
    if (!form.checkValidity()) {
        event.stopPropagation();
        form.classList.add('was-validated');
        return false;
    }

    // Get form values
    const lab = document.getElementById('lab').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('timeIn').value;
    const purpose = document.getElementById('purpose').value;

    Swal.fire({
        title: 'Confirm Reservation',
        html: `
            <p>Please confirm your reservation details:</p>
            <p><strong>Name:</strong> ${studentName}</p>
            <p><strong>Laboratory:</strong> ${lab}</p>
            <p><strong>Date:</strong> ${date}</p>
            <p><strong>Time:</strong> ${time}</p>
            <p><strong>Purpose:</strong> ${purpose}</p>
            <p><strong>PC Number:</strong> ${selectedPC}</p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm Reservation',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}

// Ensure form submission is handled
document.getElementById('reservationForm').onsubmit = validateAndSubmit;
</script>

</body>
</html>
