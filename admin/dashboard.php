<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdminLogin();

$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))['c'];
$total_queues = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM queues WHERE status='waiting'"))['c'];
$total_cleared = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM clearance WHERE status='cleared'"))['c'];
$total_offices = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM offices WHERE active=1"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel - USJ Clearance</span>
            <div class="navbar-nav ms-auto flex-row">
                <a class="nav-link text-white mx-2" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white mx-2" href="queues.php">Queues</a>
                <a class="nav-link text-white mx-2" href="students.php">Students</a>
                <a class="nav-link text-white mx-2" href="offices.php">Offices</a>
                <a class="nav-link text-white mx-2" href="reports.php">Reports</a>
                <a class="nav-link text-white mx-2" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Students</h5>
                        <h2><?php echo $total_students; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Active Queues</h5>
                        <h2><?php echo $total_queues; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Cleared</h5>
                        <h2><?php echo $total_cleared; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Active Offices</h5>
                        <h2><?php echo $total_offices; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick queue summary -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Current Queues by Office</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $offices = getAllOffices();
                            foreach($offices as $o):
                                $waiting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM queues WHERE office_id={$o['id']} AND status='waiting'"))['c'];
                            ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <?php echo $o['office_code']; ?>
                                    </div>
                                    <div class="card-body">
                                        <h3><?php echo $waiting; ?> waiting</h3>
                                        <a href="queues.php?office=<?php echo $o['id']; ?>" class="btn btn-sm btn-primary">Manage</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
