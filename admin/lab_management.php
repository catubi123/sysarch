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
    </style>
</head>
<body>
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#laboratory">
                <i class="fas fa-laptop"></i> Laboratory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#logs">
                <i class="fas fa-history"></i> Logs
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Laboratory Tab -->
        <div class="tab-pane fade show active" id="laboratory">
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

            <!-- Existing Laboratory Management Card -->
            <div class="card">
                <div class="header-gradient">
                    <h4 class="mb-0"><i class="fas fa-laptop-code"></i> Laboratory Management</h4>
                </div>
                <div class="card-body">
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
                                    <span class="badge bg-success">Available</span>
                                    <span class="badge bg-danger">Unavailable</span>
                                    <span class="badge bg-info">Selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Computer Grid -->
                    <div id="computerGrid" class="d-flex flex-wrap gap-2 justify-content-center"></div>
                </div>
            </div>
        </div>

        <!-- Logs Tab -->
        <div class="tab-pane fade" id="logs">
            <div class="row">
                <!-- Approved Reservations -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="header-gradient">
                            <h5 class="mb-0"><i class="fas fa-check"></i> Approved Reservations</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="approvedTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Lab</th>
                                        <th>PC</th>
                                        <th>Student ID</th>
                                        <th>Purpose</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Rejected Reservations -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="header-gradient">
                            <h5 class="mb-0"><i class="fas fa-times"></i> Rejected Reservations</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="rejectedTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Lab</th>
                                        <th>PC</th>
                                        <th>Student ID</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function generateComputers(lab) {
            const container = document.getElementById('computerGrid');
            container.innerHTML = '';
            
            for (let i = 1; i <= 50; i++) {
                const pc = document.createElement('div');
                pc.className = 'computer-icon';
                pc.innerHTML = `
                    <i class="fas fa-desktop"></i>
                    <span class="pc-number">PC-${String(i).padStart(2, '0')}</span>
                `;
                pc.setAttribute('data-pc', i);
                pc.setAttribute('data-lab', lab);
                
                pc.onclick = function() {
                    if (!this.classList.contains('unavailable')) {
                        togglePCStatus(this);
                    }
                };
                
                checkPCStatus(lab, i, pc);
                container.appendChild(pc);
            }
            updatePCCounts(lab);
        }

        function togglePCStatus(pcElement) {
            const pcNumber = pcElement.getAttribute('data-pc');
            const lab = pcElement.getAttribute('data-lab');
            
            $.ajax({
                url: 'update_pc_status.php',
                method: 'POST',
                data: {
                    pc_number: pcNumber,
                    lab: lab,
                    action: pcElement.classList.contains('checked') ? 'uncheck' : 'check'
                },
                success: function(response) {
                    if (response.success) {
                        pcElement.classList.toggle('checked');
                        updatePCCounts(lab);
                    }
                }
            });
        }

        function checkPCStatus(lab, pcNumber, pcElement) {
            $.ajax({
                url: 'get_pc_status.php',
                method: 'GET',
                data: { lab: lab, pc_number: pcNumber },
                success: function(response) {
                    if (response.checked) {
                        pcElement.classList.add('checked');
                    }
                    if (response.unavailable) {
                        pcElement.classList.add('unavailable');
                    }
                }
            });
        }

        function updatePCCounts(lab) {
            $.ajax({
                url: 'get_pc_counts.php',
                data: { lab: lab },
                success: function(response) {
                    $('#availablePCs').text(response.available);
                    $('#usedPCs').text(response.used);
                }
            });
        }

        function filterLab() {
            const lab = document.getElementById('labFilter').value;
            if (lab) {
                generateComputers(lab);
            }
        }

        // Initialize DataTables and event handlers
        $(document).ready(function() {
            $('#approvedTable').DataTable({
                ajax: 'get_approved_reservations.php',
                order: [[0, 'desc']]
            });
            
            $('#rejectedTable').DataTable({
                ajax: 'get_rejected_reservations.php',
                order: [[0, 'desc']]
            });

            // Auto-refresh data every 30 seconds
            setInterval(function() {
                const selectedLab = $('#labFilter').val();
                if (selectedLab) {
                    updatePCCounts(selectedLab);
                }
                $('#approvedTable').DataTable().ajax.reload();
                $('#rejectedTable').DataTable().ajax.reload();
            }, 30000);
        });
    </script>
</body>
</html>

