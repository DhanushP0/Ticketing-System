<?php
$session = session();

// Redirect to dashboard if already logged in
if ($session->get('superadmin_id')) {
    header("Location: " . base_url('superadmin/dashboard'));
    exit();
}

// Prevent browser from caching the login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --error-color: #ef4444;
            --border-radius: 10px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%);
            height: 100vh;
        }
        
        .login-card {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            width: 100%;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1.5rem;
            position: relative;
        }
        
        .card-header .logo-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .card-header h3 {
            font-weight: 600;
            margin-bottom: 0;
            letter-spacing: -0.5px;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-floating {
            margin-bottom: 1.25rem;
        }
        
        .form-floating label {
            color: #6b7280;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            height: calc(3.5rem + 2px);
            border: 1px solid #e5e7eb;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            margin-top: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-login:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }
        
        .input-group-text {
            background-color: transparent;
            padding-right: 0;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
        }
        
        .password-input {
            border-left: none;
            padding-left: 0;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #6b7280;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="login-card card">
            <div class="card-header">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h3>Superadmin Portal</h3>
            </div>
            <div class="card-body">
                <?php if(session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('superadmin/authenticate') ?>" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                        <label for="email"><i class="bi bi-envelope me-1"></i> Email address</label>
                    </div>
                    
                    <div class="input-group mb-3">
                        <div class="form-floating flex-grow-1">
                            <input type="password" name="password" class="form-control border-end-0" id="password" placeholder="Password" required>
                            <label for="password"><i class="bi bi-key me-1"></i> Password</label>
                        </div>
                        <span class="input-group-text bg-white border-start-0">
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                        </span>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
        (function() {
        // Remove previous history state
        window.history.pushState(null, "", window.location.href);
        
        window.onpopstate = function () {
            // If the user presses Back, force redirect to dashboard
            window.history.pushState(null, "", window.location.href);
            location.replace("<?= base_url('superadmin/dashboard') ?>");
        };
    })();
    </script>
</body>
</html>