<?php
include("db.php");
session_start();

// // Authentication check
if (!isset($_SESSION['regno'])) {
    header("Location: login.php");
    exit();
}



$faculty_id = $_SESSION['regno'];

// // Get faculty details
$query = "SELECT * FROM faculty WHERE user_id = '$faculty_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $name = $user_data['faculty_name'];
    $department = $user_data['dept'];
     $phoneno = $user_data['phoneno'];
      $mail = $user_data['mailid'];
} else {
    $name = "N/A";
    $department = "N/A";
}

// Handle form submission
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $name = mysqli_real_escape_string($conn, $_POST['name']);
//     $email = mysqli_real_escape_string($conn, $_POST['email']);
//     $phone = mysqli_real_escape_string($conn, $_POST['phone']);
//     $department = mysqli_real_escape_string($conn, $_POST['department']);

//     // Handle password change if provided
//     $password_update = "";
//     if (!empty($_POST['new_password'])) {
//         $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
//         $password_update = ", password = '$new_password'";
//     }

//     $update_query = "UPDATE faculty SET 
//                     faculty_name = '$name',
//                     email = '$email',
//                     phone = '$phone',
//                     dept = '$department'
//                     $password_update
//                     WHERE user_id = '$faculty_id'";

//     if (mysqli_query($conn, $update_query)) {
//         $success = "Profile updated successfully!";
//         // Refresh data
//         $result = mysqli_query($conn, $query);
//         $faculty_data = mysqli_fetch_assoc($result);
//     } else {
//         $error = "Error updating profile: " . mysqli_error($conn);
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Settings | Faculty Dashboard</title>

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
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .list-group {
            padding: 1rem 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            margin: 0.25rem 1rem;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(79, 70, 229, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
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

        /* Profile Card */
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

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--dark);
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
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
            <a href="faculty_dashboard.php" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="Fac_approve_outpass.php" class="nav-link ">
                <i class="fas fa-check-circle"></i> Approve Outpass
            </a>
            <a href="faculty_profile.php" class="nav-link active">
                <i class="fas fa-user-cog"></i> Profile Settings
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
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="assets/images/default-avatar.jpg" alt="Profile" class="profile-avatar mb-3">
                        <h4><?php echo htmlspecialchars($faculty_data['faculty_name'] ?? 'Faculty'); ?></h4>
                        <p class="mb-0"><?php echo htmlspecialchars($faculty_data['dept'] ?? 'Department'); ?></p>
                    </div>

                    <!-- Profile Information -->
                    <div class="profile-body">
                        <!-- Faculty Information Section -->
                        <h5 class="mb-4">Faculty Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Faculty ID</label>
                                    <h6><?php echo htmlspecialchars($faculty_id); ?></h6>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="info-group">
                                    <label>Faculty Name</label>
                                    <h6><?php echo htmlspecialchars($name); ?></h6>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Department</label>
                                    <h6><?php echo htmlspecialchars($department); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Email Address</label>
                                    <h6><?php echo htmlspecialchars($mail); ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Phone Number</label>
                                    <h6><?php echo htmlspecialchars($phoneno); ?></h6>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Update Information Section -->
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <h5 class="mb-4">Update Information</h5>
                        <form method="POST" action="faculty_profile.php">
                            <div class="row g-4">
                                
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($faculty_data['email'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($faculty_data['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">Change Password</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
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

        // Password confirmation validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>

</html>