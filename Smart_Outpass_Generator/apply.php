<?php
include('db.php');
session_start();
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}
$reg_no = $_SESSION['regno'];

$query = "
    SELECT login.user_id, leaveapply.*
    FROM login
    JOIN leaveapply ON login.user_id = leaveapply.user_id
    WHERE login.user_id = '$reg_no'";

$result = mysqli_query($conn, $query);

$query1 = "
    SELECT login.user_id, student.mphoneno, student.name, student.Dept, student.Year
    FROM login
    JOIN student ON login.user_id = student.user_id
    WHERE login.user_id = '$reg_no'
";
$result1 = mysqli_query($conn, $query1);

if ($result1 && mysqli_num_rows($result1) > 0) {
    $user_data = mysqli_fetch_assoc($result1);
    $mentor = $user_data['mphoneno'];
    $name = $user_data['name'];
    $dept = $user_data['Dept'];
    $year = $user_data['Year'];
} else {
    echo "<script>alert('Unable to retrieve user details.');</script>";
    $name = "N/A";
    $department = "N/A";
    $year = "N/A";
}

// Get outpass status counts
$status_counts = array(
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0,
    'out' => 0
);

if ($result) {
    $result_copy = $result;
    while ($row = mysqli_fetch_assoc($result_copy)) {
        switch ($row['status']) {
            case 1:
                $status_counts['pending']++;
                break;
            case 2:
            case 3:
                $status_counts['approved']++;
                break;
            case 4:
                $status_counts['out']++;
                break;
            case 5:
            case 6:
                $status_counts['rejected']++;
                break;
        }
    }
    mysqli_data_seek($result, 0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Outpass Management System - Student Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
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

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        /* Navbar Styles */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.75rem 1.5rem;
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 999;
            transition: all 0.3s ease;
        }

        /* Main Content adjustment */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            width: calc(100% - 280px);
        }

        /* Stats Cards Responsiveness */
        .stats-card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            font-size: 2.5rem;
            position: absolute;
            top: 20px;
            right: 20px;
            opacity: 0.2;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(10deg);
            opacity: 0.3;
        }

        /* Table Card Styles */
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            background: #0d6efd;
            font-weight: 600;
            border: none;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-color: rgba(215, 12, 12, 0.04);
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: var(--warning);
        }

        .badge-approved {
            background-color: var(--success);
        }

        .badge-rejected {
            background-color: var(--danger);
        }

        .badge-out {
            background-color: var(--primary);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background: var(--primary);
            color: white;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
        }

        /* DataTable Customization */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.375rem 2rem 0.375rem 0.75rem;
        }
    </style>
</head>

<body>
    

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="assets/images/logo-white.png" alt="Logo" class="img-fluid mb-3" width="160">
                <h5 class="mb-0">Student Portal</h5>
            </div>
            <div class="list-group list-group-flush px-3 pt-3">
                <a href="student_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="profile.php" class="nav-link">
                    <i class="fas fa-user"></i> My Profile
                </a>
                <a href="apply.php" class="nav-link active">
                    <i class="fas fa-file-alt"></i> Apply Outpass
                </a>
                <div class="mt-4">
                    <a href="logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid px-0">
                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3" data-aos="fade-up">
                        <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);">
                            <div class="card-body position-relative">
                                <i class="fas fa-clock stats-icon"></i>
                                <h5 class="card-title mb-3">Pending Applications</h5>
                                <h1 class="display-4 mb-0"><?php echo $status_counts['pending']; ?></h1>
                                <p class="small mb-0">Awaiting approval</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%);">
                            <div class="card-body position-relative">
                                <i class="fas fa-check-circle stats-icon"></i>
                                <h5 class="card-title mb-3">Approved Applications</h5>
                                <h1 class="display-4 mb-0"><?php echo $status_counts['approved']; ?></h1>
                                <p class="small mb-0">Ready to use</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);">
                            <div class="card-body position-relative">
                                <i class="fas fa-times-circle stats-icon"></i>
                                <h5 class="card-title mb-3">Rejected Applications</h5>
                                <h1 class="display-4 mb-0"><?php echo $status_counts['rejected']; ?></h1>
                                <p class="small mb-0">Not approved</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="stats-card text-white h-100" style="background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);">
                            <div class="card-body position-relative">
                                <i class="fas fa-external-link-alt stats-icon"></i>
                                <h5 class="card-title mb-3">Currently Out</h5>
                                <h1 class="display-4 mb-0"><?php echo $status_counts['out']; ?></h1>
                                <p class="small mb-0">Outside campus</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Table -->
                <div class="card table-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="outpassTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                    <i class="fas fa-list me-2"></i> All Applications
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="card-title mb-0">My Applications</h5>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="fas fa-plus me-2"></i>New Application
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table id="outpassTable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Pass Type</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $statusClass = '';
                                                    $statusMessage = '';

                                                    switch ($row['status']) {
                                                        case 1:
                                                            $statusClass = 'badge-pending';
                                                            $statusMessage = 'Pending';
                                                            break;
                                                        case 2:
                                                            $statusClass = 'badge-approved';
                                                            $statusMessage = 'Approved by Advisor';
                                                            break;
                                                        case 3:
                                                            $statusClass = 'badge-approved';
                                                            $statusMessage = 'Approved by HOD';
                                                            break;
                                                        case 4:
                                                            $statusClass = 'badge-out';
                                                            $statusMessage = 'OUT';
                                                            break;
                                                        case 5:
                                                            $statusClass = 'badge-rejected';
                                                            $statusMessage = 'Rejected by Advisor';
                                                            break;
                                                        case 6:
                                                            $statusClass = 'badge-rejected';
                                                            $statusMessage = 'Rejected by HOD';
                                                            break;
                                                        default:
                                                            $statusClass = 'badge-secondary';
                                                            $statusMessage = 'Unknown';
                                                    }
                                            ?>
                                                    <tr>
                                                        <td>OUT<?php echo $row['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                        <td><?php echo date('d M Y, h:i A', strtotime($row['date'])); ?></td>
                                                        <td>
                                                            <span class="badge rounded-pill status-badge <?php echo $statusClass; ?>">
                                                                <?php echo $statusMessage; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary view-details" data-id="<?php echo $row['id']; ?>">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Outpass Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="applyModalLabel">Apply for Outpass</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="outpassForm" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="userid" value="<?php echo $_SESSION['regno']; ?>">
                        <input type="hidden" name="dept" value="<?php echo $dept; ?>">
                        <input type="hidden" name="year" value="<?php echo $year; ?>">

                        <div class="mb-3">
                            <label for="outPass" class="form-label">Pass Type</label>
                            <select class="form-select" id="outPass" name="outPass" required>
                                <option value="" selected disabled>Select pass type</option>
                                <option value="Emergency Pass">Emergency Pass</option>
                                <option value="General Pass">General Pass</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="time" name="time" required>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Please inform your mentor about this outpass application
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-success" onclick="informMentor()">
                                <i class="fas fa-bell me-2"></i>Notify Mentor
                            </button>

                            <div>
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Outpass Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailsModalLabel">Outpass Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Application Information</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Application No:</span>
                            <span class="fw-bold" id="detailAppNo">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pass Type:</span>
                            <span class="fw-bold" id="detailPassType">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Date & Time:</span>
                            <span class="fw-bold" id="detailDateTime">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge rounded-pill" id="detailStatus">-</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Reason</h6>
                        <p class="bg-light p-3 rounded" id="detailReason">No reason provided</p>
                    </div>

                    <div id="rejectionSection" class="d-none">
                        <h6 class="text-muted mb-3">Rejection Reason</h6>
                        <p class="bg-light p-3 rounded text-danger" id="rejectionReason">No reason provided</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with correct configuration
            $('#outpassTable').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search applications..."
                },
                columnDefs: [{
                        orderable: false,
                        targets: 4
                    },
                    {
                        width: '80px',
                        targets: 4
                    }
                ]
            });

            // Updated form submission handler
            $('#outpassForm').submit(function(e) {
                e.preventDefault();

                // Log form data for debugging
                console.log('Form data:', $(this).serialize());

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: $(this).serialize() + "&save_user=true",
                    dataType: 'json',
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Outpass application submitted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#applyModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Something went wrong'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error details:', {
                            xhr,
                            status,
                            error
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to submit application. Please try again.'
                        });
                    }
                });
            });

            // Handle view details button click
            $(document).on('click', '.view-details', function() {
                var outpassId = $(this).data('id');
                console.log('Outpass ID:', outpassId);

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: {
                        'fetch_details': true,
                        'id': outpassId
                    },
                    dataType: 'json', // Add this line to parse JSON automatically
                    success: function(response) {
                        console.log('Response:', response);

                        if (response.status == 200) {
                            var data = response.data;

                            // Update modal content
                            console.log(data.id);
                            $('#detailAppNo').text('OUT' + data.id);
                            $('#detailPassType').text(data.category);
                            $('#detailDateTime').text(new Date(data.date).toLocaleString());
                            $('#detailReason').text(data.reason);

                            // Reset rejection section
                            $('#rejectionSection').addClass('d-none');

                            // Handle status badge
                            var statusBadge = $('#detailStatus');
                            statusBadge.removeClass('bg-success bg-danger bg-warning bg-info');

                            switch (parseInt(data.status)) {
                                case 1:
                                    statusBadge.addClass('bg-warning').text('Pending');
                                    break;
                                case 2:
                                    statusBadge.addClass('bg-success').text('Approved by Advisor');
                                    break;
                                case 3:
                                    statusBadge.addClass('bg-success').text('Approved by HOD');
                                    break;
                                case 4:
                                    statusBadge.addClass('bg-primary').text('OUT');
                                    break;
                                case 5:
                                    statusBadge.addClass('bg-danger').text('Rejected by Advisor');
                                    $('#rejectionSection').removeClass('d-none');
                                    $('#rejectionReason').text(data.rejection_reason || 'No reason provided');
                                    break;
                                case 6:
                                    statusBadge.addClass('bg-danger').text('Rejected by HOD');
                                    $('#rejectionSection').removeClass('d-none');
                                    $('#rejectionReason').text(data.rejection_reason || 'No reason provided');
                                    break;
                                default:
                                    statusBadge.addClass('bg-secondary').text('Unknown');
                            }

                            // Show the modal
                            $('#detailsModal').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to load details'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch details'
                        });
                    }
                });
            });
        });

        function informMentor() {
            var mentorPhone = "<?php echo $mentor; ?>";
            var message = "Dear Mentor,\n\nYour student <?php echo $name; ?> has applied for an outpass in the Smart Outpass Management System. Please review the application at your earliest convenience.\n\nThis is an automated notification.";

            var encodedMessage = encodeURIComponent(message);
            var whatsappUrl = "https://wa.me/91" + mentorPhone + "?text=" + encodedMessage;

            window.open(whatsappUrl, '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Notification Sent',
                text: 'Your mentor has been notified via WhatsApp',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Improved sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.body.classList.toggle('sidebar-active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>

</html>