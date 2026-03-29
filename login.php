<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize($_POST['student_id']);
    $password = $_POST['password'];
    
    if (studentLogin($student_id, $password)) {
        redirect('dashboard.php');
    } else {
        $error = "Invalid Student ID or Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - USJM Clearance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Disable autofill background color */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-md-4 mx-auto">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Student Login</h4>
                        <?php if(isset($error)) echo displayMessage('danger', $error); ?>
                        
                        <form method="POST" autocomplete="off">
                            <!-- Hidden fields to trick browser -->
                            <input type="text" style="display:none">
                            <input type="password" style="display:none">
                            
                            <div class="mb-3">
                                <label>Student ID</label>
                                <input type="text" name="student_id" id="student_id" class="form-control" 
                                       autocomplete="off" required>
                            </div>
                            
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" 
                                       autocomplete="off" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="register.php">New student? Register</a><br>
                            <a href="index.php">← Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Force clear the field every time the page loads
        document.getElementById('student_id').value = '';
        document.getElementById('password').value = '';
        
        // Also clear on page load
        window.addEventListener('load', function() {
            document.getElementById('student_id').value = '';
            document.getElementById('password').value = '';
        });
        
        // Also clear when page is restored from cache
        window.addEventListener('pageshow', function() {
            document.getElementById('student_id').value = '';
            document.getElementById('password').value = '';
        });
        
        // Clear when clicking on the field
        document.getElementById('student_id').addEventListener('click', function() {
            this.value = '';
        });
        
        // Prevent right-click context menu on input
        document.getElementById('student_id').addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>
