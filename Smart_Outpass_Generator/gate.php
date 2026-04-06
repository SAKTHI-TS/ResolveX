<?php
include("db.php");
//Pending tab code
$query = "SELECT student.name,student.user_id,student.images, leaveapply.* from student JOIN leaveapply ON student.user_id=leaveapply.user_id WHERE status = '3' ";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gate Security Dashboard | Smart Outpass</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            min-height: 100vh;
            position: relative;
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
            overflow-y: auto;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .toggle-sidebar {
                display: block !important;
            }
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
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(79, 70, 229, 0.1);
            color: white;
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary);
            color: white;
            font-weight: 500;
            padding: 1rem;
            border: none;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
        }

        .student-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .student-image:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        /* Custom Buttons */
        .btn-out {
            background: var(--success);
            color: white;
            border: none;
            padding: 0.5rem 2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-out:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-out:disabled {
            background: #9ca3af;
            transform: none;
            cursor: not-allowed;
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem;
        }

        /* Toggle Sidebar Button */
        .toggle-sidebar {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Loading Spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="toggle-sidebar" aria-label="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="assets/images/logo-white.png" alt="Smart Outpass Logo" class="img-fluid mb-3" width="160">
            <h5 class="mb-0">Gate Security Portal</h5>
        </div>
        <nav class="nav flex-column mt-3">
            <a href="gate.php" class="nav-link active" aria-current="page">
                <i class="fas fa-shield-alt me-2"></i> Gate Control
            </a>
            <div class="mt-auto mb-4">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col">
                    <h4 class="mb-0">Gate Security Dashboard</h4>
                    <p class="text-muted">Manage student outpass verifications</p>
                </div>
            </div>

            <!-- Outpass Table Card -->
            <div class="card table-card" data-aos="fade-up">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="outpassTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Registration No</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Photo ID</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $s = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo $s; ?></td>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td>
                                            <center>
                                                <img src="uploads/<?php echo $row['images']; ?>" alt="Student ID" style="height:100px; width:100px;">
                                            </center>
                                        </td>

                                        <td class="text-center">
                                            <!-- Updated Bootstrap 5 Modal Trigger -->
                                            <button class="btn btn-sm btn-outline-primary view-details" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>



                                        <td>
                                            <center>
                                                <button type="button" value="<?php echo $row['id']; ?>" class="btn btn-success userapprove text-center" style="font-size: 1.2em;">OUT</button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                    $s++;
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

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
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Initialize Scripts -->
    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable with responsive features
            

            // Initialize AOS animations
            AOS.init();

            // Fix modal backdrop issue
            $(document).on('hidden.bs.modal', '.modal', function() {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });
        });
         $(document).ready(function() {
            //to display data tables
            $('#outpassTable').DataTable();
        });

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

        $(document).on('click', '.userapprove', function(e) {
            e.preventDefault();
            var id = $(this).val();
            console.log(id);
            if (confirm('Are you sure you want to approve the User ?')) {

                $.ajax({
                    type: "POST",
                    url: "back.php",
                    data: {
                        'update_status': true,
                        'ids': id
                    },
                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {
                            alert(res.message);
                        } else {
                            Swal.fire({
                                title: "Success",
                                text: "User Out",
                                icon: "success"
                            });
                            $('#outpassTable').load(location.href + " #outpassTable");

                            $('#outpassTable').DataTable().destroy();

                            $("#outpassTable").load(location.href + " #outpassTable > *", function() {
                                // Reinitialize the DataTable after the content is loaded
                                $('#outpassTable').DataTable();
                            });
                        }
                    }
                })
            }


        })

        // Add sidebar toggle functionality
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Hide sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                const sidebar = document.querySelector('.sidebar');
                const toggleBtn = document.querySelector('.toggle-sidebar');
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>

</html>