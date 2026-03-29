<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdminLogin();

if (isset($_GET['process'])) {
    $queue_id = (int)$_GET['process'];
    $office_id = (int)$_GET['office'];
    
    $queue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM queues WHERE id=$queue_id"));
    if ($queue) {
        mysqli_query($conn, "UPDATE queues SET status='completed', completed_at=NOW() WHERE id=$queue_id");
        $student_id = $queue['student_id'];
        mysqli_query($conn, "UPDATE clearance SET status='cleared', cleared_date=NOW(), cleared_by='{$_SESSION['admin_name']}' WHERE student_id=$student_id AND office_id=$office_id");
        addNotification($student_id, 'in_app', "You have been cleared at office ID $office_id.");
        $_SESSION['success'] = "Student processed successfully.";
    }
    redirect("queues.php?office=$office_id");
}

$office_filter = isset($_GET['office']) ? (int)$_GET['office'] : 0;
$offices = getAllOffices();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Queues - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel</span>
            <div class="navbar-nav ms-auto flex-row">
                <a class="nav-link text-white mx-2" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white mx-2 active" href="queues.php">Queues</a>
                <a class="nav-link text-white mx-2" href="students.php">Students</a>
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
        
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <?php foreach($offices as $o): ?>
                    <a href="?office=<?php echo $o['id']; ?>" class="list-group-item list-group-item-action <?php echo ($office_filter == $o['id']) ? 'active' : ''; ?>">
                        <?php echo $o['office_name']; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-9">
                <?php if($office_filter): 
                    $queues = mysqli_query($conn, "SELECT q.*, s.full_name, s.student_id FROM queues q JOIN students s ON q.student_id=s.id WHERE q.office_id=$office_filter AND q.status='waiting' ORDER BY q.joined_at");
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5>Queue for <?php 
                            $office_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT office_name FROM offices WHERE id=$office_filter"))['office_name'];
                            echo $office_name;
                        ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($queues) > 0): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Queue #</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Joined At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $pos=1; while($q = mysqli_fetch_assoc($queues)): ?>
                                <tr>
                                    <td><?php echo $pos++; ?></td>
                                    <td><?php echo $q['queue_number']; ?></td>
                                    <td><?php echo $q['student_id']; ?></td>
                                    <td><?php echo $q['full_name']; ?></td>
                                    <td><?php echo $q['joined_at']; ?></td>
                                    <td>
                                        <a href="?process=<?php echo $q['id']; ?>&office=<?php echo $office_filter; ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark this student as cleared?')">Process</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                            <p class="text-muted">No students in queue.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-info">Please select an office from the left.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
