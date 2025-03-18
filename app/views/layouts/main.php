<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ticket System') ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('/public/assets/css/soft-ui-dashboard.css') ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius-sm: 8px;
            --border-radius: 12px;
            --border-radius-lg: 16px;
        }
        
        body {
            background-color: #f9fafb;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-color);
            min-height: 100vh;
        }
        
        .navbar {
            padding: 12px 0;
            background: white !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--primary-color) !important;
        }
        
        .navbar .nav-link {
            color: var(--text-color);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .navbar .nav-link:hover {
            background-color: var(--light-bg);
        }
        
        .navbar .nav-link.active {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            color: var(--text-color);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            border-radius: var(--border-radius-sm);
            padding: 8px 16px;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn i {
            font-size: 1.1em;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        .btn-light {
            background-color: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }
        
        .btn-light:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
        }
        
        /* Form controls */
        .form-control, .form-select {
            border-radius: var(--border-radius-sm);
            border-color: var(--border-color);
            padding: 10px 14px;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-color);
        }
        
        /* Status badges */
        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        /* Tables */
        .table {
            color: var(--text-color);
        }
        
        .table thead th {
            background-color: var(--light-bg);
            border-bottom-width: 1px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }
        
        /* Toast notifications */
        #toast-container .toast {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            opacity: 1;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            background: none;
            border: none;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        .theme-toggle:hover {
            background-color: var(--light-bg);
        }
        
        /* Dark mode styles will be toggled with JavaScript */
        .dark-mode {
            --primary-color: #818cf8;
            --primary-hover: #6366f1;
            --text-color: #e2e8f0;
            --text-muted: #94a3b8;
            --light-bg: #1e293b;
            --border-color: #334155;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.15);
            
            background-color: #0f172a;
        }
        
        .dark-mode .navbar,
        .dark-mode .card,
        .dark-mode .card-header,
        .dark-mode .btn-light {
            background-color: #1e293b !important;
            color: var(--text-color);
        }
        
        .dark-mode .navbar {
            border-bottom: 1px solid var(--border-color);
            box-shadow: none;
        }
        
        .dark-mode .table thead th {
            background-color: #334155;
        }
        
        /* Footer */
        .footer {
            padding: 24px 0;
            border-top: 1px solid var(--border-color);
            margin-top: 48px;
            color: var(--text-muted);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('user/submit_ticket') ?>">
                <i class="bi bi-ticket-perforated fs-4 me-2"></i>
                Ticketing System
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('user/submit_ticket') ?>">
                            <i class="bi bi-plus-circle me-1"></i> New Ticket
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('user/tickets') ?>">
                            <i class="bi bi-kanban me-1"></i> My Tickets
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('user/faq') ?>">
                            <i class="bi bi-question-circle me-1"></i> FAQ
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-2">
                    <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
                        <i class="bi bi-moon-fill"></i>
                    </button>
                    
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-none d-md-inline">Account</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('user/profile') ?>"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <?= $this->renderSection('content'); ?>
    </div>
    
    <footer class="footer mt-auto">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    &copy; <?= date('Y') ?> Ticketing System. All rights reserved.
                </div>
                <div>
                    <a href="#" class="text-decoration-none me-3 text-muted">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    
    <script>
        // Configure Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000,
            showDuration: 300,
            hideDuration: 300,
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
        
        $(document).ready(function () {
            // Display toast notifications
            <?php if (session()->getFlashdata('success')): ?>
                toastr.success("<?= session()->getFlashdata('success'); ?>");
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                toastr.error("<?= session()->getFlashdata('error'); ?>");
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('warning')): ?>
                toastr.warning("<?= session()->getFlashdata('warning'); ?>");
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('info')): ?>
                toastr.info("<?= session()->getFlashdata('info'); ?>");
            <?php endif; ?>
            
            // Theme toggler
            const themeToggle = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            
            // Check for saved theme preference or respect OS preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
            }
            
            // Toggle theme
            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
                } else {
                    localStorage.setItem('theme', 'light');
                    themeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
                }
            });
        });
    </script>
    
</body>
</html>