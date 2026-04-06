<?php
include("db.php");
session_start();

// Authentication check
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}



$faculty_id = $_SESSION['regno'];

// Get faculty details
$query0 = "SELECT * FROM hod WHERE user_id = '$faculty_id'";
$result0 = mysqli_query($conn, $query0);

if ($result0 && mysqli_num_rows($result0) > 0) {
    $user_data = mysqli_fetch_assoc($result0);
    $name = $user_data['hod_name'];
    $department = $user_data['dept'];
} else {
    $name = "N/A";
    $department = "N/A";
}

$query = "SELECT student.name, student.user_id, leaveapply.* 
          FROM student 
          JOIN leaveapply ON student.user_id = leaveapply.user_id  
          WHERE leaveapply.dept = '$department'  
            AND leaveapply.status = '2'";

$result = mysqli_query($conn, $query);
$compcount = mysqli_num_rows($result);

$query1 = "SELECT student.name, student.user_id, leaveapply.* 
          FROM student 
          JOIN leaveapply ON student.user_id = leaveapply.user_id  
          WHERE leaveapply.dept = '$department'  
            AND leaveapply.status = '3'";
$result1 = mysqli_query($conn, $query1);
$compcount1 = mysqli_num_rows($result1);

$query2 = "SELECT student.name, student.user_id, leaveapply.* 
          FROM student 
          JOIN leaveapply ON student.user_id = leaveapply.user_id  
          WHERE leaveapply.dept = '$department'  
            AND leaveapply.status = '4'";
$result2 = mysqli_query($conn, $query2);
$compcount2 = mysqli_num_rows($result2);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Outpass Approval | Faculty Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #818cf8;
            --accent: #10b981;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --sidebar-bg: #1e293b;
            --card-bg: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.08);
        }

        /* Matching apply.php styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--sidebar-bg);
            color: white;
            height: 100vh;
            position: fixed;
            width: 280px;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 1rem;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(108, 92, 231, 0.2);
            color: white;
            transform: translateX(5px);
        }

        /* Remove Navbar Styles */
        .navbar {
            display: none;
        }

        /* Adjust Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Stats Cards */
        .stats-card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
            color: white;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            font-size: 2.5rem;
            position: absolute;
            top: 20px;
            right: 20px;
            opacity: 0.2;
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-top: 2rem;
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem 0;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--dark);
            padding: 0.75rem 1rem;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover,
        .nav-tabs .nav-link.active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
            border: none;
        }

        .table thead th {
            background: rgba(49, 75, 238, 0.78);
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-color: rgba(0, 0, 0, 0.05);
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        /* Enhanced Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            z-index: 1;
        }

        .modal-title {
            position: relative;
            z-index: 2;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body {
            padding: 2rem;
            background: white;
        }

        .modal-body .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }

        .modal-body .form-control-static {
            background: var(--light);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modal-footer {
            background: rgba(0, 0, 0, 0.02);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }

        .modal-dialog {
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .btn-close-white:hover {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }

            .main-content {
                margin-left: 0;
            }

            .navbar {
                left: 0;
            }

            .sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/images/logo-white.png" alt="Logo" class="img-fluid mb-3" width="160">
            <h5 class="mb-0">Faculty Portal</h5>
        </div>
        <div class="list-group list-group-flush px-3 pt-3">
            <a href="hod.php" class="nav-link ">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="hodapp.php" class="nav-link active">
                <i class="fas fa-check-circle"></i> Approve Outpass
            </a>

            <div class="mt-4">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);">
                    <div class="card-body position-relative">
                        <i class="fas fa-clock stats-icon"></i>
                        <h5 class="card-title">Pending Outpasses</h5>
                        <h1 class="mb-0"><?php echo $compcount; ?></h1>
                        <p class="small mb-0">Waiting for your approval</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%);">
                    <div class="card-body position-relative">
                        <i class="fas fa-check-circle stats-icon"></i>
                        <h5 class="card-title">Approved Outpasses</h5>
                        <h1 class="mb-0"><?php echo $compcount1; ?></h1>
                        <p class="small mb-0">Already approved</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);">
                    <div class="card-body position-relative">
                        <i class="fas fa-external-link-alt stats-icon"></i>
                        <h5 class="card-title">Students Out</h5>
                        <h1 class="mb-0"><?php echo $compcount2; ?></h1>
                        <p class="small mb-0">Currently outside campus</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outpass Table -->
        <div class="card table-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <ul class="nav nav-tabs" id="outpassTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            <i class="fas fa-clock me-2"></i> Pending (<?php echo $compcount; ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                            <i class="fas fa-check-circle me-2"></i> Approved (<?php echo $compcount1; ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="out-tab" data-bs-toggle="tab" data-bs-target="#out" type="button" role="tab">
                            <i class="fas fa-external-link-alt me-2"></i> Out (<?php echo $compcount2; ?>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Pending Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover" id="pendingTable">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Date</th>
                                        <th>Reason</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $row['name']; ?></div>
                                                        <small class="text-muted"><?php echo $row['user_id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                                            <td><?php echo $row['reason']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-details" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                                    <i class="fas fa-eye"></i> View
                                                </button>


                                                <button class="btn btn-sm btn-success approve-btn" value="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button class="btn btn-sm btn-danger reject-btn" value="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Approved Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover" id="approvedTable">
                                <thead class="table-header">
                                    <tr>
                                        <th>Student</th>
                                        <th>Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    while ($row = mysqli_fetch_array($result1)) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $row['name']; ?></div>
                                                        <small class="text-muted"><?php echo $row['user_id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                                            <td><?php echo $row['reason']; ?></td>
                                            <td>
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Out Tab -->
                    <div class="tab-pane fade" id="out" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover" id="outTable">
                                <thead class="table-header">
                                    <tr>
                                        <th>Student</th>
                                        <th>Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result2)) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo $row['name']; ?></div>
                                                        <small class="text-muted"><?php echo $row['user_id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                                            <td><?php echo $row['reason']; ?></td>
                                            <td>
                                                <span class="badge bg-danger"><i class="fas fa-external-link-alt me-1"></i> Outside Campus</span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailsModalLabel">
                        <i class="fas fa-id-card-alt me-2"></i> Outpass Details
                    </h5>

                </div>

                <div class="modal-body">
                    <!-- Application Information -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Application Information</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Application No</label>
                                <div class="fw-bold" id="AppID">-</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Pass Type</label>
                                <div class="fw-bold" id="detailPassType">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Status Information</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Date & Time</label>
                                <div class="fw-bold" id="detailDateTime">-</div>
                            </div>

                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Reason for Leave</h6>
                        <div class="form-control-static" id="detailReason"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize DataTables and other existing code
        $(document).ready(function() {
            $('#pendingTable').DataTable();
            $('#approvedTable').DataTable();
            $('#outTable').DataTable();

            // Toggle sidebar on mobile
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('active');

                if ($(window).width() < 992) {
                    if ($('#sidebar').hasClass('active')) {
                        $('.navbar').css('left', '280px');
                        $('.main-content').css('margin-left', '280px');
                    } else {
                        $('.navbar').css('left', '0');
                        $('.main-content').css('margin-left', '0');
                    }
                }
            });

            // Handle view details button click with proper modal handling
            $(document).on('click', '.view-details', function() {
                var outpassId = $(this).data('id');
                console.log(outpassId);

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: {
                        fetch_details: true,
                        id: outpassId
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        console.log(data);
                        if (data.status == 200) {
                            console.log(data);
                            $('#AppID').text(data.data.id);
                            $('#detailPassType').text(data.data.category);
                            $('#detailDateTime').text(data.data.date);
                            $('#detailReason').text(data.data.reason);


                        } else {
                            $('#detailReason').text("Failed to load details.");
                        }
                    },
                    error: function() {
                        $('#detailReason').text("Error loading details.");
                    }
                });
            });

            $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);
            if (confirm('Are you sure you want to approve the User ?')) {

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: {
                        'approve': true,
                        'ids': id
                    },
                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {
                            alert(res.message);
                        } else {
                            Swal.fire({
                                title: "Success",
                                text: "User Approved",
                                icon: "success"
                            });
                            $('#pendingTable').load(location.href + " #pendingTable");

                            $('#pendingTable').DataTable().destroy();

                            $("#pendingTable").load(location.href + " #pendingTable > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#pendingTable').DataTable();
                            });
                            $('#outpassTabs').load(location.href + " #outpassTabs");
                        }
                    }
                })
            }


        })

        $(document).on('click', '.reject-btn', function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);
            if (confirm('Are you sure you want to reject the User ?')) {

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: {
                        'reject': true,
                        'ids': id
                    },
                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {
                            alert(res.message);
                        } else {
                            Swal.fire({
                                title: "Success",
                                text: "User Rejected",
                                icon: "success"
                            });
                           $('#pendingTable').load(location.href + " #pendingTable");

                            $('#pendingTable').DataTable().destroy();

                            $("#pendingTable").load(location.href + " #pendingTable > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#pendingTable').DataTable();
                            });
                            $('#outpassTabs').load(location.href + " #outpassTabs");
                        }
                    }
                })
            }


        })

            // Ensure proper modal cleanup on close



            // Adjust layout on window resize
            $(window).resize(function() {
                if ($(window).width() >= 992) {
                    $('.navbar').css('left', '280px');
                    $('.main-content').css('margin-left', '280px');
                } else {
                    if (!$('#sidebar').hasClass('active')) {
                        $('.navbar').css('left', '0');
                        $('.main-content').css('margin-left', '0');
                    }
                }
            });

            // Initialize Bootstrap modal
            const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'), {
                keyboard: true,
                backdrop: true
            });

            // Handle modal close button click
            $('.btn-close, .btn[data-bs-dismiss="modal"]').on('click', function() {
                $('#detailsModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

            // Handle escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#detailsModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });
    </script>
</body>

</html>