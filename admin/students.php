<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdminLogin();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM students WHERE id=$id");
    $_SESSION['success'] = "Student deleted.";
    redirect('students.php');
}

$students = mysqli_query($conn, "SELECT * FROM students ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel</span>
            <div class="navbar-nav ms-auto flex-row">
                <a class="nav-link text-white mx-2" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white mx-2" href="queues.php">Queues</a>
                <a class="nav-link text-white mx-2 active" href="students.php">Students</a>
                <a class="nav-link text-white mx-2" href="offices.php">Offices</a>
                <a class="nav-link text-white mx-2" href="reports.php">Reports</a>
                <a class="nav-link text-white mx-2" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h5>Registered Students</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = mysqli_fetch_assoc($students)): ?>
                        <tr>
                            <td><?php echo $s['id']; ?></td>
                            <td><?php echo $s['student_id']; ?></td>
                            <td><?php echo $s['full_name']; ?></td>
                            <td><?php echo $s['email']; ?></td>
                            <td><?php echo $s['phone']; ?></td>
                            <td><?php echo $s['faculty']; ?></td>
                            <td><?php echo $s['department']; ?></td>
                            <td><?php echo $s['year_of_study']; ?></td>
                            <td><?php echo $s['created_at']; ?></td>
                            <td>
                                <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student? This will also remove clearance and queue history.')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
