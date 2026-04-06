<?php
include('db.php');
session_start();

// Check if user is logged in and is a student
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}


$reg_no = $_SESSION['regno'];

// Get student details
$query = "
    SELECT login.user_id, student.name, student.dept, student.year
    FROM login
    JOIN student ON login.user_id = student.user_id
    WHERE login.user_id = '$reg_no'
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $name = $user_data['name'];
    $department = $user_data['dept'];
    $year = $user_data['year'];
} else {
    echo "<script>alert('Unable to retrieve student details.');</script>";
    $name = "N/A";
    $department = "N/A";
    $year = "N/A";
}

// Get outpass status counts
$status_query = "SELECT status, COUNT(*) as count FROM leaveapply WHERE user_id = '$reg_no' GROUP BY status";
$status_result = mysqli_query($conn, $status_query);
$status_counts = [
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0,
    'completed' => 0
];

while ($row = mysqli_fetch_assoc($status_result)) {
    switch ($row['status']) {
        case '1':
            $status_counts['pending'] = $row['count'];
            break;
        case '2':
            $status_counts['approved'] = $row['count'];
            break;
        case '3':
            $status_counts['rejected'] = $row['count'];
            break;
        case '4':
            $status_counts['completed'] = $row['count'];
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Outpass - Student Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4338ca;
            --primary-dark: #3730a3;
            --secondary: #818cf8;
            --accent: #6d28d9;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --sidebar-bg: #1e1b4b;
            --card-bg: #ffffff;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
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

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                margin-left: 0;
            }
        }

        /* Remove navbar-related styles */
        .navbar {
            display: none;
        }

        /* Adjust main-content styles */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Update responsive styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Info Cards */
        .info-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            background: linear-gradient(145deg, #ffffff, #f3f4f6);
            position: relative;
            z-index: 1;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .info-card .card-icon {
            font-size: 3rem;
            margin-bottom: 1.25rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0.9;
        }

        /* Status Cards */
        .status-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
            color: white;
            position: relative;
            backdrop-filter: blur(5px);
        }

        .status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .status-card:hover::before {
            opacity: 1;
        }

        .status-card .card-body {
            position: relative;
            z-index: 2;
            padding: 1.75rem;
        }

        .status-card .card-icon {
            position: absolute;
            right: 20px;
            bottom: 20px;
            font-size: 4rem;
            opacity: 0.15;
            transition: all 0.4s ease;
        }

        .status-card:hover .card-icon {
            opacity: 0.25;
            transform: scale(1.2) rotate(15deg);
        }

        .status-card h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        /* Quick Action Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1.25rem;
        }

        .list-group-item-action {
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .list-group-item-action:hover {
            background-color: rgba(var(--primary), 0.05);
            transform: translateX(5px);
        }

        /* Additional Hover Effects */
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/images/logo-white.png" alt="Logo" class="img-fluid mb-3" width="160">
            <h5 class="mb-0">Student Portal</h5>
        </div>
        <div class="list-group list-group-flush px-2 pt-3">
            <a href="student_dashboard.php" class="nav-link active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="profile.php" class="nav-link">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="apply.php" class="nav-link">
                <i class="fas fa-file-alt"></i> Apply Outpass
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
        <!-- Student Info Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6" data-aos="fade-up">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-primary">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h5 class="text-muted">Name</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($name); ?></h4>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-success">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h5 class="text-muted">Registration No</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($reg_no); ?></h4>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-warning">
                        <i class="fas fa-building"></i>
                    </div>
                    <h5 class="text-muted">Department</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($department); ?></h4>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-info">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="text-muted">Year</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($year); ?></h4>
                </div>
            </div>
        </div>

        <!-- Outpass Status Cards -->
        <div class="row g-4">
            <div class="col-md-3 col-sm-6" data-aos="fade-up">
                <div class="status-card h-100" style="background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title">Pending Outpasses</h5>
                        <h1 class="mb-0"><?php echo $status_counts['pending']; ?></h1>
                        <p class="small mb-0">Waiting for approval</p>
                        <i class="fas fa-clock card-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="status-card h-100" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title">Approved Outpasses</h5>
                        <h1 class="mb-0"><?php echo $status_counts['approved']; ?></h1>
                        <p class="small mb-0">Ready to use</p>
                        <i class="fas fa-check-circle card-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="status-card h-100" style="background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title">Rejected Outpasses</h5>
                        <h1 class="mb-0"><?php echo $status_counts['rejected']; ?></h1>
                        <p class="small mb-0">Not approved</p>
                        <i class="fas fa-times-circle card-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="status-card h-100" style="background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title">Completed Outpasses</h5>
                        <h1 class="mb-0"><?php echo $status_counts['completed']; ?></h1>
                        <p class="small mb-0">Past outpasses</p>
                        <i class="fas fa-calendar-check card-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-6" data-aos="fade-up">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="apply.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Apply for New Outpass
                            </a>
                            <a href="profile.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-edit me-2"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Outpass approved for 15 Nov 2023
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-clock text-warning me-2"></i>
                                New outpass submitted for review
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom Script -->
    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>