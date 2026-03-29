<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
requireStudentLogin();

$student_id = $_SESSION['student_id'];

if (isset($_POST['join_queue'])) {
    $office_id = (int)$_POST['office_id'];
    $check = mysqli_query($conn, "SELECT id FROM queues WHERE student_id = $student_id AND office_id = $office_id AND status = 'waiting'");
    if (mysqli_num_rows($check) == 0) {
        $queue_number = generateQueueNumber($office_id);
        $sql = "INSERT INTO queues (student_id, office_id, queue_number, status) VALUES ($student_id, $office_id, '$queue_number', 'waiting')";
        if (mysqli_query($conn, $sql)) {
            addNotification($student_id, 'in_app', "You have joined the queue for office ID $office_id. Queue number: $queue_number");
            $_SESSION['success'] = "Successfully joined queue.";
        } else {
            $_SESSION['error'] = "Failed to join queue.";
        }
    } else {
        $_SESSION['error'] = "You are already in this queue.";
    }
    redirect('queue.php');
}

if (isset($_GET['cancel'])) {
    $queue_id = (int)$_GET['cancel'];
    mysqli_query($conn, "UPDATE queues SET status = 'cancelled' WHERE id = $queue_id AND student_id = $student_id");
    addNotification($student_id, 'in_app', "You have cancelled a queue.");
    $_SESSION['success'] = "Queue cancelled.";
    redirect('queue.php');
}

$offices = getAllOffices();
$activeQueues = getStudentQueues($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queues - USJ Clearance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">USJ Clearance</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="queue.php">Queues</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <?php 
        if (isset($_SESSION['success'])) {
            echo displayMessage('success', $_SESSION['success']);
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo displayMessage('danger', $_SESSION['error']);
            unset($_SESSION['error']);
        }
        ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5>Join a Queue</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Select Office</label>
                                <select name="office_id" class="form-select" required>
                                    <option value="">-- Choose --</option>
                                    <?php foreach($offices as $o): ?>
                                    <option value="<?php echo $o['id']; ?>"><?php echo $o['office_name']; ?> (<?php echo $o['location']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="join_queue" class="btn btn-primary">Join Queue</button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-white">
                        <h5>Queue Status</h5>
                    </div>
                    <div class="card-body">
                        <p>Estimated wait time per student: ~5 minutes</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5>Your Active Queues</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($activeQueues)): ?>
                            <p class="text-muted">You are not in any queues.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach($activeQueues as $q): 
                                    $pos = getQueuePosition($student_id, $q['office_id']);
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $q['office_name']; ?></strong><br>
                                        <small>Queue #: <?php echo $q['queue_number']; ?></small><br>
                                        <span class="badge bg-warning">Position: <?php echo $pos; ?></span>
                                    </div>
                                    <a href="?cancel=<?php echo $q['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this queue?')">Cancel</a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
