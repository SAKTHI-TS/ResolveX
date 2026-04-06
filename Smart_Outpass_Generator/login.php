<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpass Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
    <div id="particles-js"></div>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-university text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">Outpass Management System</h3>
                            <p class="text-muted">Please login to continue</p>
                        </div>
                        <form id="loginForm" action="login_backend.php" method="post">
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <select class="form-select" id="roleSelect" required>
                                        <option value="">Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="advisor">Class Advisor</option>
                                        <option value="hod">HOD</option>
                                        <option value="security">Security</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="username" name="reg_no" placeholder="Username/ID" required>
                                    <div class="invalid-feedback">Please enter your username/ID</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Please enter your password</div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span class="btn-text">Login</span>
                            </button>
                            <div class="text-center">
                                <a href="forgot-password.html" class="text-decoration-none">
                                    <i class="fas fa-key me-1"></i>Forgot Password?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  
    <script>
        particlesJS.load('particles-js', 'particles-config.json', function() {
            console.log('particles.js loaded');
        });
    </script>
</body>

</html>