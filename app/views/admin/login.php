<?php
$session = session();

// 🚫 Redirect if already logged in as Admin
if ($session->get('admin_id')) {
    header("Location: " . base_url('admin/dashboard'));
    exit();
}

// 🔥 Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --text-color: #1f2937;
        }
        
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            height: 100vh;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            background-color: var(--card-bg);
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .card-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            color: #111827;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }
        
        .input-group-text {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .password-field {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .logo-container {
            margin-bottom: 1.5rem;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center logo-container">
                        <div class="logo mx-auto">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <h3 class="card-title text-center">Admin Access</h3>
                    
                    <?php if(session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('admin/authenticate') ?>" method="post">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control" placeholder="name@company.com" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">Password</label>
                                <a href="#" class="text-sm text-decoration-none text-primary">Forgot password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" id="password" name="password" class="form-control password-field" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                        
                        <p class="text-center text-muted small mt-4 mb-0">
                            Protected admin area. Unauthorized access is prohibited.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        (function() {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
            location.replace("<?= base_url('admin/dashboard') ?>");
        };
    })();

    </script>
</body>
</html>