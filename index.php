<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USJM Clearance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <section class="hero-section">
        <div class="container">
            <div class="row min-vh-100 align-items-center text-center">
                <div class="col-lg-10 mx-auto">
                    <!-- Centered Logo -->
                    <div class="logo-wrapper mb-4">
                        <img src="assets/images/usjm_logo.png" alt="USJM Logo" class="hero-logo">
                    </div>

                    <!-- University Name -->
                    <h2 class="university-name mb-3">UNIVERSITY OF SAINT JOSEPH MBARARA</h2>

                    <!-- System Title -->
                    <h1 class="display-4 fw-bold mb-3">Online Queuing & Clearance System</h1>

                    <!-- Description -->
                    <p class="lead mb-5">Streamline your examination clearance with our digital queuing system. No more long queues!</p>

                    <!-- Feature Cards -->
                    <div class="row g-4">
                        <!-- Student Card -->
                        <div class="col-md-6">
                            <div class="feature-card h-100">
                                <div class="card-body p-4">
                                    <div class="icon-box mb-3">
                                        <i class="fas fa-user-graduate fa-3x"></i>
                                    </div>
                                    <h3 class="h4 mb-3">Student</h3>
                                    <p class="mb-4">Login to check clearance, join queues, and track your progress.</p>
                                    <div class="d-grid gap-2">
                                        <!-- CHANGED: btn-primary to btn-success -->
                                        <a href="login.php" class="btn btn-success btn-lg">Student Login</a>
                                        <a href="register.php" class="btn btn-outline-success btn-lg">Register</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Staff Card -->
                        <div class="col-md-6">
                            <div class="feature-card h-100">
                                <div class="card-body p-4">
                                    <div class="icon-box mb-3">
                                        <i class="fas fa-chalkboard-teacher fa-3x"></i>
                                    </div>
                                    <h3 class="h4 mb-3">Staff</h3>
                                    <p class="mb-4">Manage queues, process students, and monitor clearance.</p>
                                    <div class="d-grid">
                                        <!-- Keep staff button blue or change to green? I kept green -->
                                        <a href="admin/" class="btn btn-success btn-lg">Staff Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
