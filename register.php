<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize($_POST['student_id']);
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $faculty = sanitize($_POST['faculty']);
    $department = sanitize($_POST['department']);
    $year = (int)$_POST['year'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    $errors = [];
    
    if ($password != $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    
    $check = mysqli_query($conn, "SELECT id FROM students WHERE student_id = '$student_id'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = "Student ID already exists.";
    }
    
    $check = mysqli_query($conn, "SELECT id FROM students WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = "Email already registered.";
    }
    
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO students (student_id, full_name, email, phone, faculty, department, year_of_study, password) 
                VALUES ('$student_id', '$full_name', '$email', '$phone', '$faculty', '$department', $year, '$hashed')";
        if (mysqli_query($conn, $sql)) {
            $new_id = mysqli_insert_id($conn);
            $offices = getAllOffices();
            foreach ($offices as $office) {
                $office_id = $office['id'];
                mysqli_query($conn, "INSERT INTO clearance (student_id, office_id) VALUES ($new_id, $office_id)");
            }
            $_SESSION['success'] = "Registration successful! Please login.";
            redirect('login.php');
        } else {
            $errors[] = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - USJ Clearance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Student Registration</h4>
                        <?php 
                        if (!empty($errors)) {
                            foreach($errors as $e) echo displayMessage('danger', $e);
                        }
                        if (isset($_SESSION['success'])) {
                            echo displayMessage('success', $_SESSION['success']);
                            unset($_SESSION['success']);
                        }
                        ?>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Student ID</label>
                                    <input type="text" name="student_id" class="form-control" autocomplete="off" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Faculty</label>
                                    <input type="text" name="faculty" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Department</label>
                                    <input type="text" name="department" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Year of Study</label>
                                    <input type="number" name="year" class="form-control" min="1" max="5" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" minlength="6" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php">Already have an account? Login</a><br>
                            <a href="index.php">← Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Clear any autofill on registration page
    window.addEventListener('load', function() {
        document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"]').forEach(function(input) {
            input.value = '';
        });
    });
    
    window.addEventListener('pageshow', function() {
        document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"]').forEach(function(input) {
            input.value = '';
        });
    });
</script>
</body>
</html>
