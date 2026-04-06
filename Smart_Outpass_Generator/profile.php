<?php
include('db.php');
session_start();
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}
$reg_no = $_SESSION['regno'];
$query = "
    SELECT login.user_id, student.name, student.dept, student.year,student.dob, student.mentorname,student.fname,student.fmobileno,student.smobileno,student.images
    FROM login
    JOIN student ON login.user_id = student.user_id
    WHERE login.user_id = '$reg_no'
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $name = $user_data['name'];
    $dept = $user_data['dept'];
    $year = $user_data['year'];
    $dob = $user_data['dob'];
    $mentorn = $user_data['mentorname'];
    $fname = $user_data['fname'];
    $fmobileno = $user_data['fmobileno'];
    $smobileno = $user_data['smobileno'];
    $ima = $user_data['images'];
} else {
    echo "<script>alert('Unable to retrieve user details.');</script>";
    $name = "N/A";
    $department = "N/A";
    $year = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Settings | Student Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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

        /* Remove navbar styles */
        .navbar {
            display: none;
        }

        /* Adjust main content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Update responsive styles */
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

        .profile-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            background: var(--card-bg);
        }

        .profile-header {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            background-color: #e2e8f0;
        }

        .profile-body {
            padding: 2rem;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-group label {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
        }

        .info-group h6 {
            margin-top: 0.5rem;
            color: var(--dark);
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
        <div class="list-group list-group-flush px-3 pt-3">
            <a href="student_dashboard.php" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="profile.php" class="nav-link active">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="apply.php" class="nav-link">
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
        <!-- Profile Content -->
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="uploads/<?php echo $ima ?>" alt="Profile" class="profile-avatar mb-3">
                        <h4><?php echo htmlspecialchars($name); ?></h4>
                        <p class="mb-0"><?php echo htmlspecialchars($dept); ?> Department</p>
                    </div>

                    <!-- Profile Information -->
                    <div class="profile-body">
                        <!-- Student Information Section -->
                        <h5 class="mb-4">Student Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Registration Number</label>
                                    <h6><?php echo htmlspecialchars($reg_no); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Department</label>
                                    <h6><?php echo htmlspecialchars($dept); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Date of Birth</label>
                                    <h6><?php echo htmlspecialchars($dob); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Year</label>
                                    <h6><?php echo htmlspecialchars($year); ?></h6>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Contact Information Section -->
                        <h5 class="mb-4">Contact Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Student Mobile</label>
                                    <h6><?php echo htmlspecialchars($smobileno); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Parent Mobile</label>
                                    <h6><?php echo htmlspecialchars($fmobileno); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Mentor Name</label>
                                    <h6><?php echo htmlspecialchars($mentorn); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Father Name</label>
                                    <h6><?php echo htmlspecialchars($fname); ?></h6>
                                </div>
                            </div>
                        </div>
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