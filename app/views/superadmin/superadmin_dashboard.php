<?= $this->extend('layouts/superadmin_template') ?>
<?= $this->section('content') ?>

<!-- Main Dashboard Content -->
<div class="container-fluid py-4">
    <!-- Welcome Banner -->
    <div class="card border-0 rounded-xl mb-4 animate__animated animate__fadeInDown shadow-lg">
        <div class="card-body p-5 position-relative overflow-hidden bg-gradient-primary text-white">
            <div class="position-absolute top-0 end-0 mt-3 me-4 animate__animated animate__pulse animate__infinite">
                <i class="fas fa-bell fa-2x opacity-50"></i>
            </div>
            <h2 class="fw-bold mb-2">Welcome back, <?= session()->get('superadmin_name'); ?> 👋</h2>
            <p class="opacity-75 fs-5 mb-0">
                Here's your help desk analytics for today.
                <span class="d-block mt-2 fs-6">Last updated: <?= date('F d, Y - h:i A') ?></span>
            </p>
        </div>
    </div>

    <!-- Loading Overlay with Modern Animation -->
    <div id="loadingOverlay"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center"
        style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); z-index: 9999; pointer-events: none;">
        <div class="text-center">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <p class="mt-3 text-primary fw-bold">Loading dashboard data...</p>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row g-4 mb-4">
        <?php
        $ticketStats = [
            [
                'title' => 'Total Tickets',
                'count' => $totalTickets,
                'icon' => 'fas fa-ticket-alt',
                'color' => 'primary',
                'trend' => '+12% from last week',
                'animation' => 'animate__fadeInUp'
            ],
            [
                'title' => 'Open Tickets',
                'count' => $openTickets,
                'icon' => 'fas fa-folder-open',
                'color' => 'warning',
                'trend' => '+5% from last week',
                'animation' => 'animate__fadeInUp animate__delay-1s'
            ],
            [
                'title' => 'Resolved Tickets',
                'count' => $resolvedTickets,
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
                'trend' => '+18% from last week',
                'animation' => 'animate__fadeInUp animate__delay-2s'
            ],
            [
                'title' => 'High Priority',
                'count' => $highUrgency,
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'trend' => '-7% from last week',
                'animation' => 'animate__fadeInUp animate__delay-3s'
            ]
        ];
        ?>

        <?php foreach ($ticketStats as $stat): ?>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 h-100 shadow-sm rounded-lg animate__animated <?= $stat['animation'] ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small"><?= $stat['title'] ?></h6>
                                <h3 class="fw-bold mb-0"><?= $stat['count'] ?></h3>
                                <span class="small text-<?= $stat['color'] ?>"><?= $stat['trend'] ?></span>
                            </div>
                            <div
                                class="rounded-circle bg-<?= $stat['color'] ?>-subtle p-3 d-flex align-items-center justify-content-center">
                                <i class="<?= $stat['icon'] ?> text-<?= $stat['color'] ?> fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Charts Section - First Row -->
    <div class="row g-4 mb-4">
        <!-- Ticket Activity Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div
                    class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold m-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>Ticket Activity Trends
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height:320px;">
                        <canvas id="ticketActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets by Urgency Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold m-0">
                        <i class="fas fa-fire-alt text-danger me-2"></i>Tickets by Urgency
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height:320px;">
                        <canvas id="urgencyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Second Row (New Charts) -->
    <div class="row g-4 mb-4">
        <!-- Category Distribution Chart (New) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold m-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>Category Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height:300px;">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Time Analysis Chart (New) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold m-0">
                        <i class="fas fa-clock text-info me-2"></i>Response Time Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height:300px;">
                        <canvas id="responseTimeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Management Panel -->
    <div class="card border-0 shadow-sm rounded-lg mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold m-0">
                <i class="fas fa-user-shield text-primary me-2"></i>Admin Management
            </h5>
            <a data-bs-toggle="modal" data-bs-target="#addAdminModal" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Add Admin
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar avatar-sm bg-<?= substr($admin['name'], 0, 1) === 'A' ? 'primary' : 'secondary' ?>-subtle rounded-circle me-3">
                                                <span
                                                    class="avatar-text text-<?= substr($admin['name'], 0, 1) === 'A' ? 'primary' : 'secondary' ?>">
                                                    <?= substr($admin['name'], 0, 1) ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= esc($admin['name']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($admin['email']) ?></td>
                                    <td>
                                        <span
                                            class="badge bg-<?= ($admin['role'] === 'superadmin') ? 'primary-subtle text-primary' : 'secondary-subtle text-secondary' ?> px-3 py-2 rounded-pill">
                                            <?= ucfirst($admin['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($admin['created_at'])) ?></td>
                                    <td class="text-end pe-4">
                                        <a class="btn btn-sm btn-outline-primary me-1 edit-admin" data-id="<?= $admin['id'] ?>"
                                            data-name="<?= esc($admin['name']) ?>" data-email="<?= esc($admin['email']) ?>"
                                            data-category="<?= esc($admin['category']) ?>"
                                            data-role="<?= esc($admin['role']) ?>" data-bs-toggle="modal"
                                            data-bs-target="#editAdminModal" data-bs-toggle="tooltip" title="Edit Admin">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    <a href="<<?= base_url('admin/delete/' . $admin['id']) ?>"
                                        class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Admin"
                                        onclick="return confirm('Are you sure you want to delete this admin?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No admins found in the system</p>
                                        <a href="<?= base_url('superadmin/add\admin') ?>"
                                            class="btn btn-sm btn-primary mt-3">
                                            <i class="fas fa-plus me-1"></i> Add Your First Admin
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('superadmin/addAdmin'); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Billing">Billing</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Account Issues">Account Issues</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Admin</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('superadmin/editAdmin'); ?>" method="post">
                    <input type="hidden" name="id" id="editAdminId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="editAdminName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="editAdminEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" id="editAdminCategory" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Billing">Billing</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Account Issues">Account Issues</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" id="editAdminRole" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="card border-0 shadow-sm rounded-lg mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold m-0">
                <i class="fas fa-ticket-alt text-primary me-2"></i>Recent Tickets
            </h5>
            <a href="<?= base_url('superadmin/ticket') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-eye fa-sm me-1"></i> View All
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Ticket ID</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Urgency</th>
                            <th>Created</th>
                            <!-- <th class="text-end pe-4">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentTickets ?? [])): ?>
                            <?php foreach ($recentTickets as $ticket): ?>
                                <?php
                                // Status color mapping
                                $statusColors = [
                                    'open' => 'primary',
                                    'in progress' => 'warning',
                                    'resolved' => 'success',
                                    'closed' => 'secondary'
                                ];
                                $status = strtolower($ticket['status']);
                                $statusColor = $statusColors[$status] ?? 'primary';

                                // Urgency color mapping
                                $urgencyColors = [
                                    'low' => 'success',
                                    'medium' => 'warning',
                                    'high' => 'danger'
                                ];
                                $urgency = strtolower($ticket['urgency']);
                                $urgencyColor = $urgencyColors[$urgency] ?? 'secondary';
                                ?>
                                <tr>
                                    <td class="ps-4 fw-medium">#<?= esc($ticket['ticket_id']); ?></td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;">
                                            <?= esc($ticket['title']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-<?= $statusColor ?>-subtle text-<?= $statusColor ?> px-3 py-2 rounded-pill">
                                            <?= ucfirst($ticket['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-<?= $urgencyColor ?>-subtle text-<?= $urgencyColor ?> px-3 py-2 rounded-pill">
                                            <?= ucfirst($ticket['urgency']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
                                    <!-- <td class="text-end pe-4">
                                        <a href="<?= site_url('superadmin/tickets/' . esc($ticket['ticket_id'])) ?>"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Ticket Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No tickets found in the system</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Modern Card Hover Effects */
    .card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Background Gradient for Welcome Card */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    /* Avatar Styling */
    .avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-text {
        font-weight: bold;
        font-size: 16px;
    }

    /* Modern Loading Spinner */
    .spinner {
        width: 60px;
        height: 60px;
        position: relative;
        margin: 0 auto;
    }

    .double-bounce1,
    .double-bounce2 {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #4e73df;
        opacity: 0.6;
        position: absolute;
        top: 0;
        left: 0;
        animation: sk-bounce 2.0s infinite ease-in-out;
    }

    .double-bounce2 {
        animation-delay: -1.0s;
    }

    @keyframes sk-bounce {

        0%,
        100% {
            transform: scale(0.0);
        }

        50% {
            transform: scale(1.0);
        }
    }
</style>

<!-- Modern Chart Initialization with Enhanced Animations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Modern Chart.js Configuration
        Chart.defaults.font.family = "'Poppins', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.boxWidth = 6;
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.cornerRadius = 6;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        Chart.defaults.plugins.tooltip.titleFont = { weight: 'bold' };

        // Fetch data from API with modern animation
        fetch('<?= base_url('superadmin/getTicketStats') ?>')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Dashboard data loaded:', data);
                initializeCharts(data);

                // Animate hiding the loading overlay
                const overlay = document.getElementById('loadingOverlay');
                overlay.classList.add('animate__animated', 'animate__fadeOut');
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 500);
            })
            .catch(error => {
                console.error('Error fetching dashboard data:', error);

                // Show error notification
                document.getElementById('loadingOverlay').style.display = 'none';

                // Create error toast notification
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '5000';

                toastContainer.innerHTML = `
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-danger text-white">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong class="me-auto">Error</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Failed to load dashboard data. Please refresh the page or contact support.
                        </div>
                    </div>
                `;

                document.body.appendChild(toastContainer);

                // Auto-remove toast after 5 seconds
                setTimeout(() => {
                    toastContainer.remove();
                }, 5000);
            });
    });

    function initializeCharts(data) {
        // Create chart configuration function with animations
        function createChartConfig(type, labels, datasets, options = {}) {
            // Enhanced animation options
            const defaultAnimations = {
                tension: {
                    duration: 1000,
                    easing: 'easeOutQuart',
                    from: 0.9,
                    to: 0.5,
                    loop: false
                }
            };

            const enhancedOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: defaultAnimations,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    },
                    ...options.plugins
                },
                ...options
            };

            return {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: enhancedOptions
            };
        }

        // 1. Ticket Activity Chart (Enhanced Line Chart)
        const ctx1 = document.getElementById('ticketActivityChart').getContext('2d');
        const ticketActivityChart = new Chart(ctx1, createChartConfig(
            'line',
            ['2025-03-14', '2025-03-15', '2025-03-16', '2025-03-17', '2025-03-18', '2025-03-19', '2025-03-20'],
            [{
                label: 'New Tickets',
                data: data.new_tickets, // ✅ Correct
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointBorderWidth: 2,
            }, {
                label: 'Resolved Tickets',
                data: data.resolved_tickets_per_day, // ✅ Correct
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#1cc88a',
                pointBorderWidth: 2,
            }],
            {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' tickets';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        ));

        // 2. Tickets by Urgency Chart (Enhanced Doughnut)
        const ctx2 = document.getElementById('urgencyChart').getContext('2d');
        const urgencyChart = new Chart(ctx2, createChartConfig(
            'doughnut',
            ['Low', 'Medium', 'High'],
            [{
                data: [data.low_urgency, data.medium_urgency, data.high_urgency],
                backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b'],
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 10
            }],
            {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = Math.round((value / total) * 100);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        ));

        // 3. Category Distribution Chart (New, Enhanced Bar Chart)
        const categories = data.tickets_by_category.map(item => item.category);
        const categoryCounts = data.tickets_by_category.map(item => item.count);

        const ctx3 = document.getElementById('categoryDistributionChart').getContext('2d');
        const categoryChart = new Chart(ctx3, createChartConfig(
            'bar',
            categories.length > 0 ? categories : ['Technical', 'Billing', 'Account', 'Feature', 'Other'],
            [{
                label: 'Tickets',
                data: categoryCounts.length > 0 ? categoryCounts : [15, 12, 8, 6, 3],
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(90, 92, 105, 0.8)'
                ],
                borderRadius: 6,
                maxBarThickness: 50
            }],
            {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.raw} tickets`;
                            }
                        }
                    }
                }
            }
        ));

        // 4. Response Time Analysis Chart (New, Enhanced Line Chart)
        const ctx4 = document.getElementById('responseTimeChart').getContext('2d');
        const responseTimeChart = new Chart(ctx4, createChartConfig(
            'line',
            ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            [{
                label: 'Avg. Response Time (hours)',
                data: [3.2, 2.8, 2.1, data.avg_response_time || 1.5],
                borderColor: '#36b9cc',
                backgroundColor: 'rgba(54, 185, 204, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#36b9cc',
                pointBorderWidth: 2
            }, {
                label: 'Target Response Time',
                data: [2, 2, 2, 2],
                borderColor: '#e74a3b',
                borderDash: [5, 5],
                borderWidth: 2,
                pointRadius: 0,
                fill: false,
                tension: 0
            }],
            {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.raw} hours`;
                            }
                        }
                    }
                }
            }
        ));

        // Add chart animations
        [ticketActivityChart, urgencyChart, categoryChart, responseTimeChart].forEach(chart => {
            // Apply modern animation when updating
            const originalUpdate = chart.update;
            chart.update = function () {
                const result = originalUpdate.apply(this, arguments);
                this.options.animation = {
                    duration: 800,
                    easing: 'easeOutQuart'
                };
                return result;
            };
        });
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<?= $this->endSection() ?>