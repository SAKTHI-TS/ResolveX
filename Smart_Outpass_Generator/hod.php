<?php
include('db.php');
session_start();

// // Authentication check
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}
$faculty_id = $_SESSION['regno'];
$query = "SELECT * FROM hod WHERE user_id = '$faculty_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $name = $user_data['hod_name'];
    $department = $user_data['dept'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard | Smart Outpass</title>

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
            border-right: 1px solid rgba(255, 255, 255, 0.05);
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
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(79, 70, 229, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }

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

        /* Info Cards */
        .info-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            height: 100%;
            background: var(--card-bg);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .info-card .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Status Cards */
        .status-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            height: 100%;
            color: white;
            position: relative;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 12px;
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/images/logo-white.png" alt="Logo" class="img-fluid" width="160">
            <h5 class="mb-0">Faculty Portal</h5>
        </div>
        <div class="sidebar-nav">
           <a href="hod.php" class="nav-link active  ">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="hodapp.php" class="nav-link  ">
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
        <!-- Welcome Banner -->
        <div class="welcome-banner animate__animated animate__fadeIn">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">Welcome back, Head Of Department <?php echo $name; ?>!</h2>
                    <p class="mb-0">
                        <i class="fas fa-building me-1"></i> <?php echo $department; ?> Department
                    </p>
                </div>
                <div class="col-md-4 text-md-end d-none d-md-block">
                    <i class="fas fa-chalkboard-teacher fa-4x opacity-25"></i>
                </div>
            </div>
        </div>

        <!-- Faculty Info Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-primary">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h5 class="text-muted">Faculty Name</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($name); ?></h4>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-success">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h5 class="text-muted">Faculty ID</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($faculty_id); ?></h4>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card text-center p-4">
                    <div class="card-icon text-warning">
                        <i class="fas fa-building"></i>
                    </div>
                    <h5 class="text-muted">Department</h5>
                    <h4 class="fw-bold"><?php echo htmlspecialchars($department); ?></h4>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="status-card" style="background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title">Pending Requests</h5>
                        <h1 class="mb-0">5</h1>
                        <p class="small mb-0">Need your attention</p>
                        <i class="fas fa-clock card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Sidebar toggle functionality
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>