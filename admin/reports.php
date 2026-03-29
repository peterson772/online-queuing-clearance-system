<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdminLogin();

$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-d', strtotime('-30 days'));
$to = isset($_GET['to']) ? $_GET['to'] : date('Y-m-d');

$queues_data = mysqli_query($conn, "SELECT DATE(joined_at) as date, COUNT(*) as total FROM queues WHERE DATE(joined_at) BETWEEN '$from' AND '$to' GROUP BY DATE(joined_at) ORDER BY date");
$clearance_data = mysqli_query($conn, "SELECT DATE(cleared_date) as date, COUNT(*) as total FROM clearance WHERE cleared_date IS NOT NULL AND DATE(cleared_date) BETWEEN '$from' AND '$to' GROUP BY DATE(cleared_date) ORDER BY date");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel</span>
            <div class="navbar-nav ms-auto flex-row">
                <a class="nav-link text-white mx-2" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white mx-2" href="queues.php">Queues</a>
                <a class="nav-link text-white mx-2" href="students.php">Students</a>
                <a class="nav-link text-white mx-2" href="offices.php">Offices</a>
                <a class="nav-link text-white mx-2 active" href="reports.php">Reports</a>
                <a class="nav-link text-white mx-2" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Select Date Range</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-auto">
                        <label>From</label>
                        <input type="date" name="from" value="<?php echo $from; ?>" class="form-control">
                    </div>
                    <div class="col-auto">
                        <label>To</label>
                        <input type="date" name="to" value="<?php echo $to; ?>" class="form-control">
                    </div>
                    <div class="col-auto">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Queues Joined Per Day</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="queueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Clearances Completed Per Day</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="clearanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const ctx1 = document.getElementById('queueChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: [<?php 
                    $labels = [];
                    $values = [];
                    mysqli_data_seek($queues_data, 0);
                    while($row = mysqli_fetch_assoc($queues_data)) {
                        $labels[] = "'".$row['date']."'";
                        $values[] = $row['total'];
                    }
                    echo implode(',', $labels);
                ?>],
                datasets: [{
                    label: 'Queues Joined',
                    data: [<?php echo implode(',', $values); ?>],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true
            }
        });
        
        const ctx2 = document.getElementById('clearanceChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [<?php 
                    $labels2 = [];
                    $values2 = [];
                    mysqli_data_seek($clearance_data, 0);
                    while($row = mysqli_fetch_assoc($clearance_data)) {
                        $labels2[] = "'".$row['date']."'";
                        $values2[] = $row['total'];
                    }
                    echo implode(',', $labels2);
                ?>],
                datasets: [{
                    label: 'Clearances',
                    data: [<?php echo implode(',', $values2); ?>],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
