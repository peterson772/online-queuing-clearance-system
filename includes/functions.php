<?php
require_once 'config.php';

// Sanitize input
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Display message
function displayMessage($type, $message) {
    $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
    return "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
            $message
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Get student by ID
function getStudentById($id) {
    global $conn;
    $id = (int)$id;
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
    return mysqli_fetch_assoc($result);
}

// Get all active offices
function getAllOffices() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM offices WHERE active = 1 ORDER BY office_name");
    $offices = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $offices[] = $row;
    }
    return $offices;
}

// Get clearance status for a student
function getClearanceStatus($student_id) {
    global $conn;
    $sql = "SELECT o.*, COALESCE(c.status, 'pending') as status, c.cleared_date
            FROM offices o
            LEFT JOIN clearance c ON o.id = c.office_id AND c.student_id = $student_id
            WHERE o.active = 1
            ORDER BY o.id";
    $result = mysqli_query($conn, $sql);
    $status = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $status[] = $row;
    }
    return $status;
}

// Get queue position for a student in an office
function getQueuePosition($student_id, $office_id) {
    global $conn;
    $sql = "SELECT COUNT(*) as position FROM queues 
            WHERE office_id = $office_id 
            AND status = 'waiting' 
            AND id <= (SELECT id FROM queues WHERE student_id = $student_id AND office_id = $office_id AND status = 'waiting')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['position'];
}

// Generate queue number
function generateQueueNumber($office_id) {
    global $conn;
    $date = date('Ymd');
    $sql = "SELECT COUNT(*) as total FROM queues WHERE DATE(joined_at) = CURDATE() AND office_id = $office_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $number = str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);
    return $date . '-' . $office_id . '-' . $number;
}

// Get active queues for a student
function getStudentQueues($student_id) {
    global $conn;
    $sql = "SELECT q.*, o.office_name, o.office_code 
            FROM queues q 
            JOIN offices o ON q.office_id = o.id 
            WHERE q.student_id = $student_id AND q.status = 'waiting' 
            ORDER BY q.joined_at";
    $result = mysqli_query($conn, $sql);
    $queues = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $queues[] = $row;
    }
    return $queues;
}

// Add notification
function addNotification($student_id, $type, $message) {
    global $conn;
    $student_id = (int)$student_id;
    $type = sanitize($type);
    $message = sanitize($message);
    $sql = "INSERT INTO notifications (student_id, type, message) VALUES ($student_id, '$type', '$message')";
    return mysqli_query($conn, $sql);
}
?>
