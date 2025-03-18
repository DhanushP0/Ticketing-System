<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background-color: white;
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .welcome-heading {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-size: 1.75rem;
        }
        
        .welcome-subtext {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        
        .form-floating {
            margin-bottom: 1.25rem;
        }
        
        .form-floating > .form-control {
            padding-left: 2.75rem;
            height: calc(3.5rem + 2px);
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        
        .form-floating > label {
            padding-left: 2.75rem;
        }
        
        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1rem;
            color: var(--text-secondary);
            z-index: 2;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .card-decoration {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
            opacity: 0.1;
            z-index: 0;
        }
        
        .decoration-1 {
            top: -60px;
            right: -60px;
        }
        
        .decoration-2 {
            bottom: -60px;
            left: -60px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="card shadow-lg">
            <div class="card-body position-relative">
                <div class="card-decoration decoration-1"></div>
                <div class="card-decoration decoration-2"></div>
                
                <div class="text-center mb-4">
                    <h1 class="welcome-heading">Welcome Back</h1>
                    <p class="welcome-subtext">Sign in to your account</p>
                </div>
                
                <form action="<?= base_url('user/loginPost') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="form-floating position-relative">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                    </div>
                    
                    <div class="form-floating position-relative">
                        <i class="fas fa-ticket-alt input-icon"></i>
                        <input type="text" name="ticket_id" class="form-control" id="ticket" placeholder="Ticket ID" required>
                        <label for="ticket">Ticket ID</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Login with Ticket ID
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>