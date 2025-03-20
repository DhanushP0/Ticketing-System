<?php
$session = session();

// 🚫 Redirect based on user role
if ($session->get('admin_id')) {
    header("Location: " . base_url('admin/dashboard'));
    exit();
} elseif ($session->get('user_id')) {
    header("Location: " . base_url('user/ticket_status/' . $session->get('ticket_id')));
    exit();
} elseif ($session->get('superadmin_id')) {
    header("Location: " . base_url('superadmin/dashboard'));
    exit();
}

// 🔥 Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --admin-color: #f59e0b;
            --superadmin-color: #ef4444;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
        }
        
        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin: 1rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .card-body {
            padding: 2rem 1.5rem;
        }
        
        .card-title {
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .card-text {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-warning {
            background-color: var(--admin-color);
            border-color: var(--admin-color);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--superadmin-color);
            border-color: var(--superadmin-color);
        }
        
        .icon-wrapper {
            height: 60px;
            width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            font-size: 1.5rem;
        }
        
        .customer-icon {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }
        
        .admin-icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--admin-color);
        }
        
        .superadmin-icon {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--superadmin-color);
        }
        
        .container {
            padding: 2rem 1rem;
        }
        
        .role-cards {
            max-width: 1100px;
            margin: 0 auto;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo h1 {
            font-weight: 800;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .logo p {
            color: #6b7280;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="container d-flex flex-column justify-content-center min-vh-100">
    <div class="logo">
        <h1>Ticket<span style="color: var(--primary-color);">Hub</span></h1>
        <p>Your support solution platform</p>
    </div>
    
    <div class="row role-cards">
        <!-- Customer Login/Signup -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <div class="icon-wrapper customer-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="card-title">Customer</h3>
                    <p class="card-text">Submit tickets and track their progress through our support system.</p>
                    <div class="action-buttons mt-auto">
                    <a href="<?= base_url('user/submit_ticket') ?>" class="btn btn-outline-primary">Submit a Ticket</a>
                    <a href="<?= base_url('user/login') ?>" class="btn btn-primary">Log In</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Login -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <div class="icon-wrapper admin-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="card-title">Admin</h3>
                    <p class="card-text">Access the admin dashboard to manage and resolve support tickets.</p>
                    <div class="action-buttons mt-auto">
                        <a href="<?= base_url('admin/login') ?>" class="btn btn-warning">Admin Access</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Superadmin Login -->
        <div class="col-lg-4 col-md-6 mb-4 mx-auto">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <div class="icon-wrapper superadmin-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="card-title">Superadmin</h3>
                    <p class="card-text">Complete control over the ticket system with advanced management tools.</p>
                    <div class="action-buttons mt-auto">
                        <a href="<?= base_url('superadmin/login') ?>" class="btn btn-danger">Superadmin Portal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
            location.replace("<?= base_url('admin/dashboard') ?>"); // Default redirection
        };
    })();
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>