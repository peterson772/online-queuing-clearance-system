<?php
require_once 'config.php';

// Check student login
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

// Check admin login
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Require student login
function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        redirect('login.php');
    }
}

// Require admin login
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        redirect('admin/index.php');
    }
}

// Student login (using password_verify)
function studentLogin($student_id, $password) {
    global $conn;
    $student_id = sanitize($student_id);
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['full_name'];
            return true;
        }
    }
    return false;
}

// Admin login
function adminLogin($username, $password) {
    global $conn;
    $username = sanitize($username);
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];
            return true;
        }
    }
    return false;
}

// Logout
function logout() {
    session_destroy();
    redirect('../index.php');
}
?>
