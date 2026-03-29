<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
requireStudentLogin();

$student_id = $_SESSION['student_id'];
$student = getStudentById($student_id);
$clearance = getClearanceStatus($student_id);
$activeQueues = getStudentQueues($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - USJ Clearance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">USJ Clearance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="queue.php">Queues</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="alert alert-info">
            <h5>Welcome, <?php echo htmlspecialchars($student['full_name']); ?> (<?php echo $student['student_id']; ?>)</h5>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5>📋 Clearance Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php 
                            $total = count($clearance);
                            $cleared = 0;
                            foreach($clearance as $c): 
                                if($c['status'] == 'cleared') $cleared++;
                            ?>
                            <div class="col-md-2 col-4 mb-3 text-center">
                                <div class="border p-2 rounded <?php echo $c['status']=='cleared'?'bg-success text-white':'bg-light'; ?>">
                                    <strong><?php echo $c['office_code']; ?></strong><br>
                                    <small><?php echo ucfirst($c['status']); ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: <?php echo ($cleared/$total)*100; ?>%"></div>
                        </div>
                        <p class="mt-2"><?php echo $cleared; ?> of <?php echo $total; ?> offices cleared.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-white">
                        <h5>⏳ Your Active Queues</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($activeQueues)): ?>
                            <p class="text-muted">You are not in any queue. <a href="queue.php">Join a queue</a>.</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Office</th>
                                        <th>Queue #</th>
                                        <th>Position</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($activeQueues as $q): 
                                        $pos = getQueuePosition($student_id, $q['office_id']);
                                    ?>
                                    <tr>
                                        <td><?php echo $q['office_name']; ?></td>
                                        <td><?php echo $q['queue_number']; ?></td>
                                        <td><span class="badge bg-warning">#<?php echo $pos; ?></span></td>
                                        <td><?php echo date('H:i', strtotime($q['joined_at'])); ?></td>
                                        <td><a href="queue.php?cancel=<?php echo $q['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this queue?')">Cancel</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5>🔔 Notifications</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $notifs = mysqli_query($conn, "SELECT * FROM notifications WHERE student_id = $student_id ORDER BY sent_at DESC LIMIT 5");
                        if (mysqli_num_rows($notifs) > 0):
                            while($n = mysqli_fetch_assoc($notifs)):
                        ?>
                        <div class="alert alert-light small border-bottom">
                            <?php echo $n['message']; ?><br>
                            <small class="text-muted"><?php echo $n['sent_at']; ?></small>
                        </div>
                        <?php endwhile;
                        else: echo "<p class='text-muted'>No notifications.</p>";
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
