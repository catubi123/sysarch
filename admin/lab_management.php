<?php
include('db.php');
include('admin_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laboratory Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 1rem;
            margin-bottom: 2rem;
        }
        .header-gradient {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }
        .computer-icon {
            width: 60px;
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
            margin: 5px;
        }
        .computer-icon.checked {
            border-color: #198754;
            background-color: #d1e7dd;
        }
        .computer-icon.unavailable {
            border-color: #dc3545;
            background-color: #f8d7da;
            cursor: not-allowed;
        }
        .pc-number {
            font-size: 12px;
            margin-top: 5px;
        }
        .lab-filter {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .status-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .status-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
        }
        .pc-status-badges {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .pc-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        #computerGrid {
            display: grid;
            grid-template-columns: repeat(10, 1fr); /* 10 PCs per row */
            gap: 10px;
            padding: 15px;
            background: #fff;
            border-radius: 0.5rem;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid px-4 py-3">
        <!-- Laboratory Card -->
        <div class="card mb-4">
            <div class="header-gradient">
                <h4 class="mb-0"><i class="fas fa-laptop"></i> Laboratory</h4>
            </div>
            <div class="card-body">
                <!-- PC Status Cards -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="status-card bg-success text-white">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div class="status-number" id="availablePCs">0</div>
                            <div>Available PCs</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-card bg-danger text-white">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <div class="status-number" id="usedPCs">0</div>
                            <div>Used PCs</div>
                        </div>
                    </div>
                </div>

                <!-- Lab Filter -->
                <div class="lab-filter">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <select class="form-select" id="labFilter" onchange="filterLab()">
                                <option value="">Select Laboratory</option>
                                <option value="524">Lab 524</option>
                                <option value="526">Lab 526</option>
                                <option value="528">Lab 528</option>
                                <option value="530">Lab 530</option>
                                <option value="542">Lab 542</option>
                                <option value="544">Lab 544</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex gap-2">
                                <span class="badge bg-success">Working</span>
                                <span class="badge bg-danger">Not Working</span>
                                <span class="badge bg-info">Selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Computer Grid -->
                <div id="computerGrid" class="d-flex flex-wrap gap-2 justify-content-center">
                    <!-- PCs will be dynamically loaded here -->
                </div>
            </div>
        </div>

        <!-- Logs Card -->
        <div class="card">
            <div class="header-gradient">
                <h4 class="mb-0"><i class="fas fa-history"></i> Reservation Logs</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="reservationLogsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Student ID</th>
                                <th>Lab</th>
                                <th>PC Number</th>
                                <th>Purpose</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT r.*, u.fname, u.lname 
                                    FROM reservation r 
                                    LEFT JOIN user u ON r.id_number = u.id 
                                    ORDER BY r.reservation_date DESC, r.reservation_time DESC";
                            $result = $con->query($query);

                            while($row = $result->fetch_assoc()) {
                                $status_class = match($row['status']) {
                                    'pending' => 'text-warning',
                                    'approved' => 'text-success',
                                    'rejected' => 'text-danger',
                                    default => ''
                                };

                                echo "<tr>
                                    <td>{$row['reservation_date']}</td>
                                    <td>{$row['reservation_time']}</td>
                                    <td>{$row['id_number']}</td>
                                    <td>Lab {$row['lab']}</td>
                                    <td>PC-" . str_pad($row['pc_number'], 2, '0', STR_PAD_LEFT) . "</td>
                                    <td>{$row['purpose']}</td>
                                    <td class='{$status_class}'>" . ucfirst($row['status']) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Define the PC configurations for each lab
        const labComputers = {
            '524': Array.from({length: 50}, (_, i) => i + 1),
            '526': Array.from({length: 50}, (_, i) => i + 1),
            '528': Array.from({length: 50}, (_, i) => i + 1),
            '530': Array.from({length: 50}, (_, i) => i + 1),
            '542': Array.from({length: 50}, (_, i) => i + 1),
            '544': Array.from({length: 50}, (_, i) => i + 1)
        };

        function generateComputers(lab) {
            const container = document.getElementById('computerGrid');
            container.innerHTML = '';
            
            if (labComputers[lab]) {
                labComputers[lab].forEach((pcNumber) => {
                    const pc = document.createElement('div');
                    pc.className = 'computer-icon checked';
                    pc.innerHTML = `
                        <i class="fas fa-desktop"></i>
                        <span class="pc-number">PC-${String(pcNumber).padStart(2, '0')}</span>
                    `;
                    pc.setAttribute('data-pc', pcNumber);
                    pc.setAttribute('data-lab', lab);
                    
                    pc.onclick = function() {
                        togglePCStatus(this);
                    };
                    
                    container.appendChild(pc);
                });
                
                // Check actual status from database immediately after generating PCs
                checkLabStatus(lab);
            }
        }

        function togglePCStatus(pcElement) {
            const pcNumber = pcElement.getAttribute('data-pc');
            const lab = pcElement.getAttribute('data-lab');
            const isAvailable = pcElement.classList.contains('checked');
            
            Swal.fire({
                title: 'Confirm Status Change',
                text: `Are you sure you want to mark PC-${pcNumber} as ${isAvailable ? 'In Use' : 'Available'}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update_pc_status.php',
                        method: 'POST',
                        data: {
                            pc_number: pcNumber,
                            lab: lab,
                            active: !isAvailable
                        },
                        success: function(response) {
                            if (response.success) {
                                pcElement.classList.toggle('checked');
                                pcElement.classList.toggle('unavailable');
                                
                                // Update counters
                                const available = document.querySelectorAll('.computer-icon.checked').length;
                                const unavailable = document.querySelectorAll('.computer-icon.unavailable').length;
                                updatePCCounts(available, unavailable);
                                
                                // Store the PC status in localStorage
                                const pcStatus = {
                                    lab: lab,
                                    pc_number: pcNumber,
                                    is_active: !isAvailable
                                };
                                localStorage.setItem(`pc_${lab}_${pcNumber}`, JSON.stringify(pcStatus));
                                
                                Swal.fire({
                                    title: 'Updated!',
                                    text: `PC-${pcNumber} status updated successfully`,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        }

        function checkLabStatus(lab) {
            $.ajax({
                url: 'get_pc_status.php',
                method: 'GET',
                data: { lab: lab },
                success: function(response) {
                    if (response.pcs) {
                        let availableCount = 0;
                        let unavailableCount = 0;

                        document.querySelectorAll('.computer-icon').forEach(pc => {
                            const pcNumber = pc.getAttribute('data-pc');
                            const pcInfo = response.pcs.find(p => p.number == pcNumber);
                            
                            // Check localStorage for saved status
                            const savedStatus = localStorage.getItem(`pc_${lab}_${pcNumber}`);
                            if (savedStatus) {
                                const status = JSON.parse(savedStatus);
                                if (!status.is_active) {
                                    pc.classList.remove('checked');
                                    pc.classList.add('unavailable');
                                    unavailableCount++;
                                } else {
                                    pc.classList.remove('unavailable');
                                    pc.classList.add('checked');
                                    availableCount++;
                                }
                            } else if (pcInfo) {
                                if (!pcInfo.is_active) {
                                    pc.classList.remove('checked');
                                    pc.classList.add('unavailable');
                                    unavailableCount++;
                                } else {
                                    pc.classList.remove('unavailable');
                                    pc.classList.add('checked');
                                    availableCount++;
                                }
                            }
                        });

                        updatePCCounts(availableCount, unavailableCount);
                    }
                }
            });
        }

        function updatePCCounts(available, unavailable) {
            $('#availablePCs').text(available);
            $('#usedPCs').text(unavailable);
        }

        function filterLab() {
            const selectedLab = document.getElementById('labFilter').value;
            if (selectedLab) {
                generateComputers(selectedLab);
            }
        }

        // Initialize DataTable
        $(document).ready(function() {
            const table = $('#reservationLogsTable').DataTable({
                order: [[0, 'desc'], [1, 'desc']],
                pageLength: 10,
                language: {
                    search: "Search logs:",
                    lengthMenu: "Show _MENU_ entries"
                },
                processing: true
            });

            // Check for updates every 5 seconds
            setInterval(function() {
                const selectedLab = $('#labFilter').val();
                if (selectedLab) {
                    checkLabStatus(selectedLab);
                }
            }, 5000);
        });
    </script>
</body>
</html>
