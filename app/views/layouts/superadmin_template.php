<!-- File: app/Views/layouts/superadmin_template.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $this->renderSection('title') ?? 'SuperAdmin Command Center' ?></title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css">


  <!-- Font Awesome Pro -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
  <style>
    :root {
      --primary-color: #7209b7;
      --primary-light: #b5179e;
      --primary-dark: #560bad;
      --secondary-color: #4361ee;
      --success-color: #0ead69;
      --info-color: #3a86ff;
      --warning-color: #ffbe0b;
      --danger-color: #f72585;
      --light-color: #f8f9fa;
      --dark-color: #121212;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-300: #dee2e6;
      --gray-400: #ced4da;
      --gray-500: #adb5bd;
      --gray-600: #6c757d;
      --gray-700: #495057;
      --gray-800: #343a40;
      --gray-900: #212529;
      --border-radius: 0.75rem;
      --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
      --box-shadow-sm: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
      --box-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.1);
      --transition-base: all 0.25s cubic-bezier(0.645, 0.045, 0.355, 1);
      --transition-fade: opacity 0.15s linear;
      --transition-collapse: height 0.35s ease;
      --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      --gradient-secondary: linear-gradient(135deg, var(--secondary-color) 0%, #3a0ca3 100%);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
      overflow-x: hidden;
      color: var(--gray-700);
      transition: var(--transition-base);
    }

    /* Scrollbar styles */
    ::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    ::-webkit-scrollbar-track {
      background: var(--gray-100);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-dark);
    }

    /* Sidebar */
    .sidebar {
      width: 300px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--dark-color);
      z-index: 9;
      transition: var(--transition-base);
      overflow-y: auto;
      color: #fff;
    }

    .sidebar-collapsed {
      margin-left: -300px;
    }

    .sidebar .brand {
      height: 80px;
      background: var(--gradient-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 1.5rem;
      color: white;
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 1px;
      text-decoration: none;
      text-transform: uppercase;
    }

    .sidebar .brand i {
      font-size: 2rem;
      margin-right: 0.75rem;
      position: relative;
    }

    .sidebar .brand i:after {
      content: '';
      position: absolute;
      width: 12px;
      height: 12px;
      background: var(--success-color);
      border: 2px solid white;
      border-radius: 50%;
      top: -5px;
      right: -5px;
    }

    .sidebar-divider {
      margin: 0.75rem 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      opacity: 0.3;
    }

    .sidebar .nav-item {
      position: relative;
      margin: 0.5rem 1rem;
    }

    .sidebar .nav-link {
      display: flex;
      align-items: center;
      color: rgba(255, 255, 255, 0.7);
      padding: 0.85rem 1.5rem;
      border-radius: var(--border-radius);
      transition: var(--transition-base);
      font-weight: 500;
    }

    .sidebar .nav-link:hover {
      color: rgba(255, 255, 255, 1);
      background-color: rgba(255, 255, 255, 0.05);
    }

    .sidebar .nav-link.active {
      color: rgba(255, 255, 255, 1);
      background-color: var(--primary-color);
      box-shadow: 0 5px 15px rgba(114, 9, 183, 0.4);
    }

    .sidebar .nav-link i {
      margin-right: 0.75rem;
      width: 1.5rem;
      font-size: 1.1rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.5);
      transition: var(--transition-base);
    }

    .sidebar .nav-link:hover i,
    .sidebar .nav-link.active i {
      color: rgba(255, 255, 255, 1);
    }

    .sidebar-heading {
      padding: 1.5rem 1.5rem 0.5rem;
      font-weight: 600;
      font-size: 0.7rem;
      color: rgba(255, 255, 255, 0.5);
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }

    .sidebar-footer {
      position: sticky;
      bottom: 0;
      width: 100%;
      padding: 1.5rem;
      background: linear-gradient(to top, var(--dark-color), rgba(18, 18, 18, 0.8));
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
    }

    .sidebar-footer .help-block {
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: var(--border-radius);
      padding: 1.25rem;
      margin-bottom: 1rem;
    }

    .sidebar-footer .help-block a {
      color: var(--primary-light);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition-base);
    }

    .sidebar-footer .help-block a:hover {
      color: white;
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
      padding-left: 3rem;
      max-height: 0;
      overflow: hidden;
      transition: var(--transition-collapse);
    }

    .sidebar .has-submenu.open .submenu {
      max-height: 500px;
    }

    .sidebar .submenu .nav-link {
      padding: 0.6rem 1rem;
      font-weight: 400;
      font-size: 0.9rem;
      position: relative;
    }

    .sidebar .submenu .nav-link:before {
      content: '';
      position: absolute;
      left: -15px;
      top: 50%;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--primary-light);
      transform: translateY(-50%);
      opacity: 0;
      transition: var(--transition-base);
    }

    .sidebar .submenu .nav-link:hover:before,
    .sidebar .submenu .nav-link.active:before {
      opacity: 1;
    }

    /* Content */
    .content {
      flex: 1;
      width: calc(100% - 300px);
      margin-left: 300px;
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
      height: 80px;
      background: #fff;
      box-shadow: var(--box-shadow-sm);
      position: sticky;
      top: 0;
      z-index: 8;
    }

    .topbar .dropdown-menu {
      border: none;
      padding: 1rem 0;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
      border-radius: var(--border-radius);
      min-width: 15rem;
      transform: translateY(10px);
      animation: fadeInDown 0.3s ease;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(10px);
      }
    }

    .topbar .nav-item .nav-link {
      color: var(--gray-700);
      height: 80px;
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
      top: 25px;
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
      width: 360px;
    }

    .topbar .dropdown-list .dropdown-header {
      background: var(--gradient-primary);
      padding: 1.25rem;
      color: white;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .topbar .dropdown-list .dropdown-item {
      white-space: normal;
      padding: 1.25rem;
      border-bottom: 1px solid var(--gray-200);
      transition: var(--transition-base);
    }

    .topbar .dropdown-list .dropdown-item:hover {
      background-color: rgba(114, 9, 183, 0.05);
    }

    .topbar .dropdown-list .icon-circle {
      height: 48px;
      width: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .user-dropdown img {
      height: 45px;
      width: 45px;
      border: 3px solid var(--primary-light);
      padding: 2px;
      box-shadow: 0 5px 15px rgba(114, 9, 183, 0.2);
    }

    .dropdown-menu .dropdown-item {
      padding: 0.75rem 1.5rem;
      display: flex;
      align-items: center;
      font-weight: 500;
      color: var(--gray-700);
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: rgba(114, 9, 183, 0.05);
      color: var(--primary-color);
    }

    .dropdown-menu .dropdown-item i {
      margin-right: 0.75rem;
      width: 1.5rem;
      text-align: center;
    }

    /* Page header */
    .page-header {
      padding: 2.5rem;
      border-radius: var(--border-radius);
      background: var(--gradient-primary);
      box-shadow: 0 10px 30px rgba(114, 9, 183, 0.15);
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
      color: white;
    }

    .page-header:before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDQwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik00MDAgMEg2MFY2MEgwVjQwMEg0MDBWMFoiIGZpbGw9IndoaXRlIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+');
      background-size: cover;
      opacity: 0.1;
    }

    .page-header h1 {
      font-size: 2.25rem;
      font-weight: 700;
      color: white;
      margin-bottom: 0.5rem;
    }

    .page-header .breadcrumb {
      margin-bottom: 0;
      background-color: transparent;
      padding: 0;
    }

    .page-header .breadcrumb-item a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
    }

    .page-header .breadcrumb-item.active {
      color: rgba(255, 255, 255, 1);
    }

    .page-header .breadcrumb-item+.breadcrumb-item::before {
      color: rgba(255, 255, 255, 0.5);
    }

    /* Cards */
    .card {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      transition: var(--transition-base);
      overflow: hidden;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: white;
      border-bottom: 1px solid var(--gray-200);
      font-weight: 600;
      padding: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-header .card-title {
      margin-bottom: 0;
      font-weight: 600;
      font-size: 1.25rem;
      color: var(--gray-800);
    }

    .card-header .card-subtitle {
      margin-top: 0.25rem;
      font-size: 0.85rem;
      color: var(--gray-600);
    }

    .card-header .card-actions a {
      color: var(--gray-600);
      margin-left: 0.75rem;
      transition: var(--transition-base);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }

    .card-header .card-actions a:hover {
      color: var(--primary-color);
      background-color: rgba(114, 9, 183, 0.05);
    }

    .card-body {
      padding: 1.75rem;
    }

    .card-icon {
      font-size: 2rem;
      height: 64px;
      width: 64px;
      border-radius: 20%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 20px rgba(114, 9, 183, 0.15);
      margin-right: 1.25rem;
      position: relative;
      z-index: 1;
      overflow: hidden;
    }

    .card-icon:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-primary);
      z-index: -1;
    }

    .card-icon i {
      color: white;
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
      font-size: 2rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 0.25rem;
      line-height: 1;
    }

    .card-stats-info {
      font-size: 0.75rem;
      display: flex;
      align-items: center;
    }

    .card-stats-info.positive {
      color: var(--success-color);
    }

    .card-stats-info.negative {
      color: var(--danger-color);
    }

    .card-stats-info i {
      font-size: 0.9rem;
      margin-right: 0.25rem;
    }

    /* Progress */
    .progress {
      height: 0.6rem;
      margin-top: 0.75rem;
      border-radius: 1rem;
      background-color: var(--gray-200);
      overflow: visible;
    }

    .progress-bar {
      border-radius: 1rem;
      position: relative;
      background: var(--gradient-primary);
    }

    .progress-bar:after {
      content: '';
      position: absolute;
      right: 0;
      top: 50%;
      transform: translate(50%, -50%);
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: white;
      border: 3px solid var(--primary-color);
      box-shadow: 0 0 10px rgba(114, 9, 183, 0.5);
    }

    /* Dashboard Metrics */
    .metric-card {
      position: relative;
      overflow: hidden;
      border-radius: var(--border-radius);
      padding: 2rem;
      background: white;
      box-shadow: var(--box-shadow);
      transition: var(--transition-base);
    }

    .metric-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--box-shadow-lg);
    }

    .metric-card .metric-icon {
      position: absolute;
      top: -20px;
      right: -20px;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: rgba(114, 9, 183, 0.1);
    }

    .metric-card .metric-title {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--gray-600);
      margin-bottom: 0.75rem;
    }

    .metric-card .metric-value {
      font-size: 2.25rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
      line-height: 1;
    }

    .metric-card .metric-data {
      display: flex;
      align-items: center;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .metric-card .metric-data.up {
      color: var(--success-color);
    }

    .metric-card .metric-data.down {
      color: var(--danger-color);
    }

    .metric-card .metric-data i {
      margin-right: 0.25rem;
    }

    /* Advanced charts section */
    .chart-container {
      position: relative;
      min-height: 300px;
    }

    /* Recent activity */
    .activity-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .activity-item {
      display: flex;
      padding: 1rem 0;
      border-bottom: 1px solid var(--gray-200);
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      flex-shrink: 0;
    }

    .activity-content {
      flex: 1;
    }

    .activity-title {
      font-weight: 500;
      color: var(--gray-800);
      margin-bottom: 0.25rem;
    }

    .activity-time {
      font-size: 0.75rem;
      color: var(--gray-500);
    }

    /* Quick Actions Section */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 1rem;
    }

    .quick-action-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: white;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      text-align: center;
      transition: var(--transition-base);
      box-shadow: var(--box-shadow-sm);
    }

    .quick-action-item:hover {
      transform: translateY(-5px);
      box-shadow: var(--box-shadow);
      background: var(--primary-color);
      color: white;
    }

    .quick-action-item:hover i {
      color: white;
    }

    .quick-action-item i {
      font-size: 2rem;
      margin-bottom: 0.75rem;
      color: var(--primary-color);
      transition: var(--transition-base);
    }

    .quick-action-item span {
      font-size: 0.85rem;
      font-weight: 500;
    }

    /* System Status Widget */
    .status-widget {
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--box-shadow);
    }

    .status-header {
      padding: 1.5rem;
      background: var(--dark-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .status-header h5 {
      margin: 0;
      font-weight: 600;
    }

    .status-body {
      background: white;
      padding: 1.5rem;
    }

    .status-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--gray-200);
    }

    .status-item:last-child {
      border-bottom: none;
    }

    .status-indicator {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 0.5rem;
    }

    .status-indicator.active {
      background-color: var(--success-color);
      box-shadow: 0 0 10px rgba(14, 173, 105, 0.5);
    }

    .status-indicator.warning {
      background-color: var(--warning-color);
      box-shadow: 0 0 10px rgba(255, 190, 11, 0.5);
    }

    .status-indicator.danger {
      background-color: var(--danger-color);
      box-shadow: 0 0 10px rgba(247, 37, 133, 0.5);
    }

    .status-name {
      font-weight: 500;
      display: flex;
      align-items: center;
    }

    .status-value {
      font-weight: 600;
      font-size: 0.9rem;
    }

    /* Command Console */
    .command-console {
      background-color: var(--dark-color);
      color: #32CD32;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      font-family: 'Courier New', monospace;
      position: relative;
      min-height: 300px;
      overflow: hidden;
    }

    .command-console:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 30px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .console-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      position: relative;
      z-index: 1;
    }

    .console-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 6px;
    }

    .console-dot.red {
      background-color: #ff5f56;
    }

    .console-dot.yellow {
      background-color: #ffbd2e;
    }

    .console-dot.green {
      background-color: #27c93f;
    }

    .console-title {
      position: absolute;
      left: 0;
      right: 0;
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.85rem;
    }

    .console-content {
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .console-prompt {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .console-prompt:before {
      content: '>';
      margin-right: 0.5rem;
    }

    .console-cursor {
      display: inline-block;
      width: 8px;
      height: 15px;
      background-color: #32CD32;
      animation: blink 1s infinite;
      vertical-align: middle;
      margin-left: 2px;
    }

    @keyframes blink {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0;
      }
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        margin-left: -300px;
      }

      .sidebar.sidebar-expanded {
        margin-left: 0;
      }

      .content {
        width: 100%;
        margin-left: 0;
      }

      .search-form {
        width: 200px;
      }

      .search-form .form-control:focus {
        width: 250px;
      }
    }

    .page-header {
      padding: 1.5rem;
    }

    .page-header h1 {
      font-size: 1.5rem;
    }

    .metric-card {
      padding: 1.25rem;
    }

    .metric-card .metric-value {
      font-size: 1.75rem;
    }

    .topbar .dropdown-list {
      width: 300px;
    }

    .quick-actions {
      grid-template-columns: repeat(2, 1fr);
    }
    }

    @media (max-width: 576px) {
      .topbar .nav-item .nav-link {
        padding: 0 0.5rem;
      }

      .user-dropdown img {
        height: 35px;
        width: 35px;
      }

      .search-form {
        display: none;
      }

      .quick-actions {
        grid-template-columns: 1fr;
      }

      .card-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
      }

      .card-header .card-actions {
        margin-top: 1rem;
        width: 100%;
        display: flex;
        justify-content: flex-end;
      }

      .card-body {
        padding: 1.25rem;
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
    }

    .theme-toggle:hover {
      background-color: var(--light-bg);
    }

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
  </style>

  <!-- Main Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

  <?= $this->renderSection('header') ?>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <a href="/superadmin/dashboard" class="brand">
      <i class="fas fa-bolt"></i>
      <span>SuperAdmin</span>
    </a>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Main</div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= url_is('*/dashboard') ? 'active' : '' ?>" href="/superadmin/dashboard">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item has-submenu <?= url_is('*/users*') ? 'open' : '' ?>">
        <a class="nav-link" href="#">
          <i class="fas fa-users"></i>
          <span>User Management</span>
        </a>
        <ul class="submenu">
          <li>
            <a class="nav-link <?= url_is('*/users/list') ? 'active' : '' ?>" href="/superadmin/users/list">All
              Users</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/users/roles') ? 'active' : '' ?>" href="/superadmin/users/roles">Roles &
              Permissions</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/users/activity') ? 'active' : '' ?>" href="/superadmin/users/activity">User
              Activity</a>
          </li>
        </ul>
      </li>

      <li class="nav-item has-submenu <?= url_is('*/system*') ? 'open' : '' ?>">
        <a class="nav-link" href="#">
          <i class="fas fa-server"></i>
          <span>Ticket Management</span>
        </a>
        <ul class="submenu">
          <li>
            <a class="nav-link <?= url_is('superadmin/ticket') ? 'active' : '' ?>" href="/superadmin/ticket">All
              Tickets</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/system/performance') ? 'active' : '' ?>"
              href="/superadmin/system/performance">Open Tickets</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/system/backups') ? 'active' : '' ?>"
              href="/superadmin/system/backups">Closed Tickets</a>
          </li>
        </ul>
      </li>
      <li class="nav-item has-submenu <?= url_is('*/system*') ? 'open' : '' ?>">
        <a class="nav-link" href="#">
          <i class="fas fa-server"></i>
          <span>Admin Management</span>
        </a>
        <ul class="submenu">
          <li>
            <a class="nav-link <?= url_is('superadmin/manageAdmins') ? 'active' : '' ?>" href="manageAdmins">All
              Admin</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/system/performance') ? 'active' : '' ?>"
              href="/superadmin/system/performance">Open Tickets</a>
          </li>
          <li>
            <a class="nav-link <?= url_is('*/system/backups') ? 'active' : '' ?>"
              href="/superadmin/system/backups">Closed Tickets</a>
          </li>
        </ul>
      </li>
    </ul>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Configuration</div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= url_is('*/settings') ? 'active' : '' ?>" href="/superadmin/settings">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= url_is('*/security') ? 'active' : '' ?>" href="/superadmin/security">
          <i class="fas fa-shield-alt"></i>
          <span>Security</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= url_is('*/integrations') ? 'active' : '' ?>" href="/superadmin/integrations">
          <i class="fas fa-plug"></i>
          <span>Integrations</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <div class="help-block">
        <p>Need assistance?</p>
        <a href="/superadmin/help">View Documentation</a>
      </div>
      <small>SuperAdmin v2.0.3</small>
    </div>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <!-- Topbar -->
    <nav class="navbar navbar-expand topbar">
      <div class="container-fluid">
        <!-- Sidebar Toggle -->
        <button id="sidebarToggle" class="btn">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Search Form -->
        <!-- <form class="d-none d-md-flex ms-4 search-form">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for..." aria-label="Search">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form> -->

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ms-auto">
          <!-- Notifications -->
          <!-- <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell fa-fw"></i>
              <span class="badge bg-danger">3</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-end" aria-labelledby="notificationsDropdown">
              <div class="dropdown-header">
                Notifications
              </div>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="me-3">
                  <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                  </div>
                </div>
                <div>
                  <div class="small text-gray-500">December 12, 2024</div>
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
                  <div class="small text-gray-500">December 7, 2024</div>
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
                  <div class="small text-gray-500">December 2, 2024</div>
                  Spending Alert: We've noticed unusually high spending for your account.
                </div>
              </a>
              <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
            </div>
          </li> -->

          <!-- Messages -->
          <!-- <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-envelope fa-fw"></i>
              <span class="badge bg-primary">7</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-end" aria-labelledby="messagesDropdown">
              <div class="dropdown-header">
                Messages
              </div>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image me-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                  <div class="status-indicator bg-success"></div>
                </div>
                <div class="fw-bold">
                  <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                  <div class="small text-gray-500">Emily Fowler · 58m</div>
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image me-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                  <div class="status-indicator"></div>
                </div>
                <div>
                  <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                  <div class="small text-gray-500">Jae Chun · 1d</div>
                </div>
              </a>
              <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
          </li> -->
          <div class="d-flex align-items-center gap-2">
            <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
              <i class="bi bi-moon-fill"></i>
            </button>
            <!-- User Information -->
            <li class="nav-item dropdown user-dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span class="d-none d-lg-inline me-2 text-gray-600 small">Admin User</span>
                <img class="rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60" alt="User">
              </a>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="/superadmin/profile">
                  <i class="fas fa-user fa-sm fa-fw"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="/superadmin/settings">
                  <i class="fas fa-cogs fa-sm fa-fw"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="/superadmin/activity">
                  <i class="fas fa-list fa-sm fa-fw"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw"></i>
                  Logout
                </a>
              </div>
            </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
      <?= $this->renderSection('content') ?>
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

  <!-- Page specific scripts -->
  <?= $this->renderSection('scripts') ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  <script>
    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Sidebar toggle functionality
    document.getElementById('sidebarToggle').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
      document.getElementById('content').classList.toggle('content-expanded');
    });

    // Submenu toggle
    document.querySelectorAll('.has-submenu > .nav-link').forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
      });
    });

    // Auto-collapse sidebar on small screens
    function checkWidth() {
      if (window.innerWidth < 992) {
        document.getElementById('sidebar').classList.add('sidebar-collapsed');
        document.getElementById('content').classList.add('content-expanded');
      } else {
        document.getElementById('sidebar').classList.remove('sidebar-collapsed');
        document.getElementById('content').classList.remove('content-expanded');
      }
    }

    // Initial check and add resize event listener
    checkWidth();
    window.addEventListener('resize', checkWidth);
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
  </script>
</body>

</html>