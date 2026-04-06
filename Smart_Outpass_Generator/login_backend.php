<?php
session_start();
include('db.php'); // Include the database connection file

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if faculty ID and password match
    $query = "SELECT * FROM login WHERE user_id = '$reg_no' AND password = '$password'";
    $result = mysqli_query($conn, $query);
   

    // If user exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['regno'] = $reg_no;
        $_SESSION['role'] = $row['role'];
        if ($_SESSION['role']  == "Students") {
            header("Location: student_dashboard.php");
        }else if($_SESSION['role'] == "Fac") {
            header("Location: faculty_dashboard.php");
        }
        else if($_SESSION['role'] == "HOD") {
            header("Location: hod.php");
        }
        else  {
            header("Location: login.php");
        }
        exit();

       
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Student ID or Password. Please try again.'); window.location.href='login.php';</script>";
    }
} else {
    header("Location: login.php"); // Redirect back to login page if accessed without form submission
    exit();
}
