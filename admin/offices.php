<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdminLogin();

if (isset($_POST['add_office'])) {
    $name = sanitize($_POST['office_name']);
    $code = sanitize($_POST['office_code']);
    $desc = sanitize($_POST['description']);
    $loc = sanitize($_POST['location']);
    $max = (int)$_POST['max_queue'];
    mysqli_query($conn, "INSERT INTO offices (office_name, office_code, description, location, max_queue) VALUES ('$name', '$code', '$desc', '$loc', $max)");
    $_SESSION['success'] = "Office added.";
    redirect('offices.php');
}

if (isset($_POST['edit_office'])) {
    $id = (int)$_POST['id'];
    $name = sanitize($_POST['office_name']);
    $code = sanitize($_POST['office_code']);
    $desc = sanitize($_POST['description']);
    $loc = sanitize($_POST['location']);
    $max = (int)$_POST['max_queue'];
    $active = isset($_POST['active']) ? 1 : 0;
    mysqli_query($conn, "UPDATE offices SET office_name='$name', office_code='$code', description='$desc', location='$loc', max_queue=$max, active=$active WHERE id=$id");
    $_SESSION['success'] = "Office updated.";
    redirect('offices.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM offices WHERE id=$id");
    $_SESSION['success'] = "Office deleted.";
    redirect('offices.php');
}

$offices = mysqli_query($conn, "SELECT * FROM offices ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Offices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel</span>
            <div class="navbar-nav ms-auto flex-row">
                <a class="nav-link text-white mx-2" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white mx-2" href="queues.php">Queues</a>
                <a class="nav-link text-white mx-2" href="students.php">Students</a>
                <a class="nav-link text-white mx-2 active" href="offices.php">Offices</a>
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Add New Office</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Office Name</label>
                                <input type="text" name="office_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Office Code (e.g., FIN)</label>
                                <input type="text" name="office_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Max Queue Length</label>
                                <input type="number" name="max_queue" class="form-control" value="50">
                            </div>
                            <button type="submit" name="add_office" class="btn btn-primary">Add Office</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Existing Offices</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Location</th>
                                    <th>Max Queue</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($o = mysqli_fetch_assoc($offices)): ?>
                                <tr>
                                    <td><?php echo $o['id']; ?></td>
                                    <td><?php echo $o['office_name']; ?></td>
                                    <td><?php echo $o['office_code']; ?></td>
                                    <td><?php echo $o['location']; ?></td>
                                    <td><?php echo $o['max_queue']; ?></td>
                                    <td><?php echo $o['active'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#edit<?php echo $o['id']; ?>">Edit</a>
                                        <a href="?delete=<?php echo $o['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this office?')">Delete</a>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit<?php echo $o['id']; ?>">
                                    <td colspan="7">
                                        <form method="POST" class="p-3 border">
                                            <input type="hidden" name="id" value="<?php echo $o['id']; ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" name="office_name" value="<?php echo $o['office_name']; ?>" class="form-control" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="office_code" value="<?php echo $o['office_code']; ?>" class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="location" value="<?php echo $o['location']; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" name="max_queue" value="<?php echo $o['max_queue']; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="active" <?php echo $o['active']?'checked':''; ?>>
                                                        <label>Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" name="edit_office" class="btn btn-sm btn-success">Save</button>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <textarea name="description" class="form-control" placeholder="Description"><?php echo $o['description']; ?></textarea>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
