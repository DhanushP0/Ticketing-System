<!-- File: app/Views/layouts/admin_template.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $this->renderSection('title') ?? 'Admin Dashboard' ?></title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css">


  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --primary-light: #4895ef;
      --primary-dark: #3f37c9;
      --secondary-color: #560bad;
      --success-color: #06d6a0;
      --info-color: #0dcaf0;
      --warning-color: #ffd166;
      --danger-color: #ef476f;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-300: #dee2e6;
      --gray-400: #ced4da;
      --gray-500: #adb5bd;
      --gray-600: #6c757d;
      --gray-700: #495057;
      --gray-800: #343a40;
      --gray-900: #212529;
      --border-radius: 0.5rem;
      --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --box-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --box-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
      --transition-base: all 0.3s ease;
      --transition-fade: opacity 0.15s linear;
      --transition-collapse: height 0.35s ease;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f5f7fa;
      overflow-x: hidden;
      color: var(--gray-700);
    }

    /* Scrollbar styles */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--gray-100);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--gray-400);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--gray-500);
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background: #ffffff;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
      z-index: 9;
      transition: var(--transition-base);
      overflow-y: auto;
    }

    .sidebar-collapsed {
      margin-left: -280px;
    }

    .sidebar .brand {
      height: 70px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 1.5rem;
      color: white;
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 0.5px;
      text-decoration: none;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar .brand i {
      font-size: 1.75rem;
      margin-right: 0.75rem;
    }

    .sidebar-divider {
      margin: 0.5rem 1.5rem;
      border-top: 1px solid var(--gray-200);
      opacity: 0.5;
    }

    .sidebar .nav-item {
      position: relative;
      margin: 0.25rem 0.75rem;
    }

    .sidebar .nav-link {
      display: flex;
      align-items: center;
      color: var(--gray-700);
      padding: 0.75rem 1rem;
      border-radius: var(--border-radius);
      transition: var(--transition-base);
      font-weight: 500;
    }

    .sidebar .nav-link:hover {
      color: var(--primary-color);
      background-color: rgba(67, 97, 238, 0.05);
    }

    .sidebar .nav-link.active {
      color: var(--primary-color);
      background-color: rgba(67, 97, 238, 0.1);
      box-shadow: var(--box-shadow-sm);
    }

    .sidebar .nav-link i {
      margin-right: 0.75rem;
      width: 1.5rem;
      font-size: 1.1rem;
      text-align: center;
      color: var(--gray-600);
      transition: var(--transition-base);
    }

    .sidebar .nav-link:hover i,
    .sidebar .nav-link.active i {
      color: var(--primary-color);
    }

    .sidebar-heading {
      padding: 1rem 1.5rem 0.5rem;
      font-weight: 600;
      font-size: 0.75rem;
      color: var(--gray-500);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .sidebar-footer {
      position: sticky;
      bottom: 0;
      width: 100%;
      padding: 1rem 1.5rem;
      background: white;
      border-top: 1px solid var(--gray-200);
      font-size: 0.85rem;
      color: var(--gray-600);
    }

    .sidebar-footer .help-block {
      background-color: rgba(67, 97, 238, 0.05);
      border-radius: var(--border-radius);
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .sidebar-footer .help-block a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition-base);
    }

    .sidebar-footer .help-block a:hover {
      color: var(--primary-dark);
    }

    /* Submenu */
    .sidebar .has-submenu>.nav-link::after {
      content: '\f107';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      margin-left: auto;
      transition: var(--transition-base);
    }

    .sidebar .has-submenu.open>.nav-link::after {
      transform: rotate(180deg);
    }

    .sidebar .submenu {
      list-style: none;
      padding-left: 2.5rem;
      max-height: 0;
      overflow: hidden;
      transition: var(--transition-collapse);
    }

    .sidebar .has-submenu.open .submenu {
      max-height: 500px;
    }

    .sidebar .submenu .nav-link {
      padding: 0.5rem 1rem;
      font-weight: 400;
      font-size: 0.9rem;
    }

    /* Content */
    .content {
      flex: 1;
      width: calc(100% - 280px);
      margin-left: 280px;
      min-height: 100vh;
      transition: var(--transition-base);
      position: relative;
    }

    .content-expanded {
      width: 100%;
      margin-left: 0;
    }

    /* Navbar */
    .topbar {
      height: 70px;
      background-color: white;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 8;
    }

    .topbar .dropdown-menu {
      border: none;
      padding: 1rem 0;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
      border-radius: 0.5rem;
      min-width: 15rem;
    }

    .topbar .nav-item .nav-link {
      color: var(--gray-700);
      height: 70px;
      display: flex;
      align-items: center;
      padding: 0 1rem;
      position: relative;
    }

    .topbar .nav-item .nav-link:hover {
      color: var(--primary-color);
    }

    .topbar .nav-item .nav-link .badge {
      position: absolute;
      top: 20px;
      right: 8px;
      font-size: 0.65rem;
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .topbar .nav-item .dropdown-toggle::after {
      display: none;
    }

    .topbar .dropdown-list {
      padding: 0;
      border: none;
      overflow: hidden;
      width: 320px;
    }

    .topbar .dropdown-list .dropdown-header {
      background-color: var(--primary-color);
      padding: 1rem;
      color: white;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .topbar .dropdown-list .dropdown-item {
      white-space: normal;
      padding: 1rem;
      border-bottom: 1px solid var(--gray-200);
      transition: var(--transition-base);
    }

    .topbar .dropdown-list .dropdown-item:hover {
      background-color: var(--gray-100);
    }

    .topbar .dropdown-list .icon-circle {
      height: 42px;
      width: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .user-dropdown img {
      height: 38px;
      width: 38px;
      border: 2px solid var(--gray-200);
      padding: 2px;
    }

    .dropdown-menu .dropdown-item {
      padding: 0.6rem 1.5rem;
      display: flex;
      align-items: center;
      font-weight: 500;
      color: var(--gray-700);
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: var(--gray-100);
      color: var(--primary-color);
    }

    .dropdown-menu .dropdown-item i {
      margin-right: 0.75rem;
      width: 1.5rem;
      text-align: center;
    }

    /* Page header */
    .page-header {
      padding: 2rem;
      border-radius: var(--border-radius);
      background-color: white;
      box-shadow: var(--box-shadow);
      margin-bottom: 1.5rem;
    }

    .page-header h1 {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
    }

    .page-header .breadcrumb {
      margin-bottom: 0;
      background-color: transparent;
      padding: 0;
    }

    .page-header .breadcrumb-item a {
      color: var(--primary-color);
      text-decoration: none;
    }

    .page-header .breadcrumb-item.active {
      color: var(--gray-600);
    }

    /* Cards */
    .card {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      transition: var(--transition-base);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: white;
      border-bottom: 1px solid var(--gray-200);
      font-weight: 600;
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-header .card-actions a {
      color: var(--gray-600);
      margin-left: 0.5rem;
      transition: var(--transition-base);
    }

    .card-header .card-actions a:hover {
      color: var(--primary-color);
    }

    .card-body {
      padding: 1.5rem;
    }

    .card-icon {
      font-size: 1.75rem;
      height: 54px;
      width: 54px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: rgba(67, 97, 238, 0.1);
      color: var(--primary-color);
      margin-right: 1rem;
    }

    .card-stats {
      display: flex;
      align-items: center;
    }

    .card-stats-title {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--gray-600);
      margin-bottom: 0.5rem;
    }

    .card-stats-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 0.25rem;
    }

    .card-stats-info {
      font-size: 0.75rem;
      color: var(--gray-500);
    }

    .card-stats-info.positive {
      color: var(--success-color);
    }

    .card-stats-info.negative {
      color: var(--danger-color);
    }

    /* Progress */
    .progress {
      height: 0.6rem;
      margin-top: 0.75rem;
      border-radius: 1rem;
      background-color: var(--gray-200);
    }

    .progress-bar {
      border-radius: 1rem;
    }

    /* Buttons */
    .btn {
      font-weight: 500;
      padding: 0.5rem 1.25rem;
      border-radius: 0.5rem;
      transition: var(--transition-base);
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
      border-color: var(--primary-dark);
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    /* Tables */
    .table {
      color: var(--gray-700);
    }

    .table thead th {
      font-weight: 600;
      color: var(--gray-800);
      border-top: none;
      padding: 1rem;
    }

    .table td {
      padding: 1rem;
      vertical-align: middle;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }

    /* Badge */
    .badge {
      padding: 0.35rem 0.65rem;
      font-weight: 500;
      font-size: 0.75rem;
      border-radius: 50rem;
    }

    /* Footer */
    .footer {
      background-color: white;
      padding: 1.5rem;
      box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.02);
      border-top: 1px solid var(--gray-200);
      margin-top: 2rem;
    }

    .footer a {
      color: var(--gray-600);
      text-decoration: none;
      transition: var(--transition-base);
    }

    .footer a:hover {
      color: var(--primary-color);
    }

    /* Animations */
    .fadeIn {
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Search */
    .search-form {
      position: relative;
      width: 250px;
    }

    .search-form .form-control {
      padding-left: 2.5rem;
      border-radius: 50rem;
      background-color: var(--gray-100);
      border: none;
      height: 40px;
    }

    .search-form .form-control:focus {
      background-color: white;
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }

    .search-form i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
    }

    /* Mobile Responsiveness */
    @media (max-width: 992px) {
      .sidebar {
        margin-left: -280px;
      }

      .content {
        width: 100%;
        margin-left: 0;
      }

      .sidebar-expanded {
        margin-left: 0;
      }

      .search-form {
        width: 180px;
      }
    }

    @media (max-width: 768px) {
      .search-form {
        display: none;
      }

      .card-stats {
        flex-direction: column;
        align-items: flex-start;
      }

      .card-icon {
        margin-bottom: 1rem;
        margin-right: 0;
      }

      .topbar .nav-item .nav-link {
        padding: 0 0.5rem;
      }
    }

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
      margin-top: 1rem;
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
    .dark-mode .btn-light .dark-mode .sidebar.card-body {
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

    /* Additional customization */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
    }

    .quick-action-item {
      background-color: white;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      text-align: center;
      box-shadow: var(--box-shadow);
      transition: var(--transition-base);
      cursor: pointer;
    }

    .quick-action-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      background-color: var(--primary-color);
      color: white;
    }

    .quick-action-item:hover i {
      color: white;
    }

    .quick-action-item i {
      font-size: 2rem;
      display: block;
      margin-bottom: 1rem;
      color: var(--primary-color);
      transition: var(--transition-base);
    }

    .quick-action-item span {
      font-weight: 500;
      display: block;
    }
  </style>

  <!-- Additional CSS -->
  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!-- Page Wrapper -->
  <div class="wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <!-- Brand -->
      <a href="<?= base_url('admin/dashboard') ?>" class="brand">
        <i class="fas fa-bolt"></i>
        <span>AdminHub</span>
      </a>

      <!-- Nav Items -->
      <div class="px-3 py-4">
        <div class="search-form w-100 mb-4">
        </div>

        <ul class="nav flex-column">
          <li class="nav-item">
            <a href="<?= base_url('admin/dashboard') ?>"
              class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
              <i class="fas fa-home"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <li class="sidebar-heading">MANAGEMENT</li>

          <li class="nav-item has-submenu">
            <a href="#" class="nav-link">
              <i class="fas fa-ticket-alt"></i>
              <span>Tickets</span>
            </a>
            <ul class="submenu">
              <li class="nav-item">
                <a href="<?= base_url('admin/ticket') ?>"
                  class="nav-link <?= uri_string() == 'admin/ticket' ? 'active' : '' ?>">
                  <span>All Tickets</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('admin/ticket/open') ?>"
                  class="nav-link <?= uri_string() == 'admin/ticket/open' ? 'active' : '' ?>">
                  <span>Open Tickets</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('admin/ticket/closed') ?>"
                  class="nav-link <?= uri_string() == 'admin/ticket/closed' ? 'active' : '' ?>">
                  <span>Closed Tickets</span>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('admin/users') ?>"
              class="nav-link <?= uri_string() == 'admin/users' ? 'active' : '' ?>">
              <i class="fas fa-users"></i>
              <span>Users</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('admin/reports') ?>"
              class="nav-link <?= uri_string() == 'admin/reports' ? 'active' : '' ?>">
              <i class="fas fa-chart-bar"></i>
              <span>Reports</span>
            </a>
          </li>

          <li class="sidebar-heading">SYSTEM</li>

          <li class="nav-item">
            <a href="<?= base_url('admin/settings') ?>"
              class="nav-link <?= uri_string() == 'admin/settings' ? 'active' : '' ?>">
              <i class="fas fa-cog"></i>
              <span>Settings</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('admin/profile') ?>"
              class="nav-link <?= uri_string() == 'admin/profile' ? 'active' : '' ?>">
              <i class="fas fa-user"></i>
              <span>Profile</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('logout') ?>" class="nav-link">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
        <div class="help-block mb-2 text-center">
          <i class="fas fa-headset mb-2" style="font-size: 1.5rem; color: var(--primary-color);"></i>
          <small class="d-block mb-1">Need help?</small>
          <a href="#" class="btn btn-sm btn-outline-primary w-100">Contact SuperAdmin</a>
        </div>
        <div class="text-center">
          <span>&copy; <?= date('Y') ?> AdminHub</span>
        </div>
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content" id="content">
      <!-- Topbar -->
      <nav class="topbar navbar navbar-expand navbar-light bg-white">
        <div class="container-fluid">
          <!-- Sidebar Toggle -->
          <button id="sidebarToggle" class="btn btn-link">
            <i class="fas fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ms-auto">
            <!-- Dark Mode Toggle -->
            <li class="nav-item mx-1">
              <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
                <i class="bi bi-moon-fill"></i>
            </li>

            <!-- Nav Item - Alerts -->
            <!-- <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge bg-danger">3</span>
              </a>
              <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Notifications Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="me-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2023</div>
                    <span class="fw-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="me-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2023</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="me-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2023</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li> -->

            <!-- Nav Item - Messages -->
            <!-- <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge bg-primary">7</span>
              </a>
              <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image me-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="User Avatar">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image me-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/cssvEZacHvQ/60x60" alt="User Avatar">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image me-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/IWLOvomUmWU/60x60" alt="User Avatar">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li> -->

            <div class="topbar-divider d-none d-sm-block"
              style="width: 0; border-right: 1px solid #e3e6f0; height: 2rem; margin: auto 0.5rem;"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="d-none d-lg-inline text-gray-600 me-2">John Doe</span>
                <img class="rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/38x38" alt="User Avatar">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="container-fluid py-4 px-4">
        <?= $this->renderSection('content') ?>
      </main>

      <!-- Footer -->
      <footer class="footer text-center">
        <div class="container">
          <div class="row">
            <div class="col-md-4 mb-4 mb-md-0 text-md-start">
              <h5 class="mb-3 text-gray-700">AdminHub</h5>
              <p class="text-muted small">
                A modern and responsive admin dashboard template built with Bootstrap 5.
              </p>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <h5 class="mb-3 text-gray-700">Links</h5>
              <ul class="list-unstyled">
                <li><a href="#">Documentation</a></li>
                <li><a href="#">Support</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>
            <div class="col-md-4 text-md-end">
              <h5 class="mb-3 text-gray-700">Connect With Us</h5>
              <div class="social-icons">
                <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://github.com/DhanushP0"><i class="fab fa-github"></i></a>
              </div>
            </div>
          </div>
          <div class="mt-4 small text-muted">
            <span>&copy; <?= date('Y') ?> AdminHub. All rights reserved.</span>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Added modal-dialog-centered class -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Select "Logout" below if you are ready to end your current session.
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?= base_url('logout') ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap 5.3 (Latest) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- Custom scripts for all pages-->
  <script>
    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
      document.getElementById('content').classList.toggle('content-expanded');
    });

    // Submenu toggle
    document.addEventListener('DOMContentLoaded', function () {
      // Toggle submenu
      const subMenus = document.querySelectorAll('.has-submenu');
      subMenus.forEach(item => {
        item.querySelector('.nav-link').addEventListener('click', function (e) {
          e.preventDefault();
          item.classList.toggle('open');
        });
      });

      // Active submenu
      const currentPath = window.location.pathname;
      const navLinks = document.querySelectorAll('.sidebar .nav-link');
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
          link.classList.add('active');
          // If inside submenu, open the parent
          const parent = link.closest('.has-submenu');
          if (parent) {
            parent.classList.add('open');
          }
        }
      });

      // Dark mode toggle
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
      themeToggle.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');

        if (document.body.classList.contains('dark-mode')) {
          localStorage.setItem('theme', 'dark');
          themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
        } else {
          localStorage.setItem('theme', 'light');
          themeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
        }
      });

      // Initialize tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // Mobile responsive adjustments
      const mobileToggle = function () {
        if (window.innerWidth < 992) {
          document.getElementById('sidebar').classList.add('sidebar-collapsed');
          document.getElementById('content').classList.add('content-expanded');
        } else {
          document.getElementById('sidebar').classList.remove('sidebar-collapsed');
          document.getElementById('content').classList.remove('content-expanded');
        }
      };

      // Execute on load
      mobileToggle();

      // Execute on resize
      window.addEventListener('resize', mobileToggle);
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/4.0.0/jquery.fancybox.min.js"></script>

  <!-- Page specific JavaScript -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>