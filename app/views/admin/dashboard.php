<?= $this->extend('layouts/admin_template') ?>

<?= $this->section('content') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="container-fluid py-4">
  <!-- Welcome Card with Glass Morphism Effect -->
  <div class="card border-0 rounded-lg mb-4 animate__animated animate__fadeIn glass-card">
    <div class="card-body text-center p-5 bg-gradient-shine">
      <h3 class="fw-bold welcome-text">Welcome, <?= session()->get('admin_name'); ?>! 🎉</h3>
      <p class="welcome-subtext mb-0">
        Your assigned tickets are ready to be resolved. You have 
        <span class="badge welcome-badge fw-bold"><?= count($tickets ?? []); ?> tickets</span>.
      </p>
    </div>
  </div>
 <!-- Loader -->
<div id="loadingSpinner" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>



  <!-- Ticket Summary Cards with Enhanced Hover Effects -->
  <div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="stat-card card-total">
        <div class="stat-card-body">
          <div class="stat-card-icon">
            <i class="fas fa-ticket-alt"></i>
          </div>
          <div class="stat-card-info">
            <h6>Total Tickets</h6>
            <h3 class="fw-bold"><?= $totalTickets ?></h3>
          </div>
        </div>
        <div class="stat-card-wave">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="rgba(255,255,255,0.1)" d="M0,192L30,186.7C60,181,120,171,180,186.7C240,203,300,245,360,229.3C420,213,480,139,540,101.3C600,64,660,64,720,85.3C780,107,840,149,900,154.7C960,160,1020,128,1080,144C1140,160,1200,224,1260,213.3C1320,203,1380,117,1410,74.7L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
          </svg>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card card-open">
        <div class="stat-card-body">
          <div class="stat-card-icon">
            <i class="fas fa-folder-open"></i>
          </div>
          <div class="stat-card-info">
            <h6>Open Tickets</h6>
            <h3 class="fw-bold"><?= $openTickets ?></h3>
          </div>
        </div>
        <div class="stat-card-wave">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="rgba(255,255,255,0.1)" d="M0,288L30,288C60,288,120,288,180,272C240,256,300,224,360,218.7C420,213,480,235,540,256C600,277,660,299,720,277.3C780,256,840,192,900,181.3C960,171,1020,213,1080,234.7C1140,256,1200,256,1260,245.3C1320,235,1380,213,1410,202.7L1440,192L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
          </svg>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card card-resolved">
        <div class="stat-card-body">
          <div class="stat-card-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="stat-card-info">
            <h6>Resolved Tickets</h6>
            <h3 class="fw-bold"><?= $resolvedTickets ?></h3>
          </div>
        </div>
        <div class="stat-card-wave">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="rgba(255,255,255,0.1)" d="M0,96L30,128C60,160,120,224,180,245.3C240,267,300,245,360,213.3C420,181,480,139,540,149.3C600,160,660,224,720,250.7C780,277,840,267,900,224C960,181,1020,107,1080,96C1140,85,1200,139,1260,170.7C1320,203,1380,213,1410,218.7L1440,224L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
          </svg>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card card-priority">
        <div class="stat-card-body">
          <div class="stat-card-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="stat-card-info">
            <h6>High Priority</h6>
            <h3 class="fw-bold"><?= $highPriorityTickets ?></h3>
          </div>
        </div>
        <div class="stat-card-wave">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="rgba(255,255,255,0.1)" d="M0,32L30,37.3C60,43,120,53,180,69.3C240,85,300,107,360,133.3C420,160,480,192,540,202.7C600,213,660,203,720,186.7C780,171,840,149,900,160C960,171,1020,213,1080,218.7C1140,224,1200,192,1260,176C1320,160,1380,160,1410,160L1440,160L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section with Improved Styling and Shadows -->
  <div class="row g-4 mb-4">
    <!-- New Fancy Area Chart replacing Ticket Trends -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm fancy-card h-100">
        <div class="card-header border-0 py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0"><i class="fas fa-chart-area me-2"></i>Ticket Activity Overview</h5>
          </div>
        </div>
        <div class="card-body">
          <div class="chart-container position-relative" style="height:320px;">
            <canvas id="ticketActivityChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Tickets by Urgency - Vertical Layout -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm fancy-card h-100">
        <div class="card-header border-0 py-3">
          <h5 class="card-title m-0"><i class="fas fa-fire-alt me-2"></i>Tickets by Urgency</h5>
        </div>
        <div class="card-body">
          <div class="chart-container position-relative" style="height:320px;">
            <canvas id="urgencyChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Second Row of Charts -->
  <div class="row g-4 mb-4">
    <!-- Ticket Status Distribution -->
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm fancy-card h-100">
        <div class="card-header border-0 py-3">
          <h5 class="card-title m-0"><i class="fas fa-pie-chart me-2"></i>Ticket Status Distribution</h5>
        </div>
        <div class="card-body">
          <div class="chart-container position-relative" style="height:280px;">
            <canvas id="ticketStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Average Response Time -->
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm fancy-card h-100">
        <div class="card-header border-0 py-3">
          <h5 class="card-title m-0"><i class="fas fa-clock me-2"></i>Average Response Time (Hours)</h5>
        </div>
        <div class="card-body">
          <div class="chart-container position-relative" style="height:280px;">
            <canvas id="responseTimeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Recent Tickets with Improved Table -->
  <div class="card border-0 shadow-sm fancy-card mb-4">
    <div class="card-header border-0 py-3 d-flex flex-row align-items-center justify-content-between">
      <h5 class="card-title m-0"><i class="fas fa-list-alt me-2"></i>Recent Tickets</h5>
      <a href="<?= base_url('admin/ticket') ?>" class="btn btn-sm btn-primary fancy-btn">
        <i class="fas fa-eye fa-sm me-1"></i> View All
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle fancy-table">
          <thead>
            <tr>
              <th>Ticket ID</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Urgency</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($tickets ?? [])): ?>
              <?php foreach($tickets as $ticket): ?>
                <tr>
                  <td><?= esc($ticket['ticket_id']); ?></td>
                  <td><?= $ticket['title'] ?></td>
                  <td>
                    <?php
                      $statusBadge = match($ticket['status']) {
                        'Open' => 'primary',
                        'In Progress' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'secondary',
                        default => 'dark'
                      };
                    ?>
                    <span class="badge bg-<?= $statusBadge ?> rounded-pill px-3"><?= ucfirst($ticket['status']) ?></span>
                  </td>
                  <td>
                    <?php
                      $urgencyBadge = match($ticket['urgency']) {
                        'Low' => 'info',
                        'Medium' => 'warning',
                        'High' => 'danger',
                        default => 'dark'
                      };
                    ?>
                    <span class="badge bg-<?= $urgencyBadge ?> rounded-pill px-3"><?= ucfirst($ticket['urgency']) ?></span>
                  </td>
                  <td><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
                  <td>
                    <a href="<?= site_url('admin/messages/' . esc($ticket['ticket_id'])) ?>" class="btn btn-sm btn-info text-white fancy-action-btn">                     
                      <i class="fas fa-eye"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">No tickets found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Enhanced CSS for modern styling with theme compatibility -->
<style>
  :root {
    --primary-color: #4e73df;
    --primary-light: #6f8be8;
    --primary-dark: #3862c7;
    --success-color: #1cc88a;
    --success-dark: #13a673;
    --warning-color: #f6c23e;
    --warning-dark: #dda20a;
    --danger-color: #e74a3b;
    --danger-dark: #c13325;
    --info-color: #36b9cc;
    --secondary-color: #858796;
    --light-color: #f8f9fc;
    --dark-color: #5a5c69;
    --bg-card: #ffffff;
    --text-primary: #3a3b45;
    --text-secondary: #858796;
    --border-color: rgba(0, 0, 0, 0.125);
    --shadow-color: rgba(0, 0, 0, 0.075);
  }

  /* Dark theme variables - will be applied via .dark-theme class */
  .dark-theme {
    --primary-color: #4e73df;
    --primary-light: #6f8be8;
    --primary-dark: #3862c7;
    --success-color: #1cc88a;
    --success-dark: #13a673;
    --warning-color: #f6c23e;
    --warning-dark: #dda20a;
    --danger-color: #e74a3b;
    --danger-dark: #c13325;
    --info-color: #36b9cc;
    --secondary-color: #858796;
    --light-color: #3a3b45;
    --dark-color: #f8f9fc;
    --bg-card: #2c2f3f;
    --text-primary: #f8f9fc;
    --text-secondary: #d1d3e2;
    --border-color: rgba(255, 255, 255, 0.125);
    --shadow-color: rgba(0, 0, 0, 0.15);
  }

  /* Base styling */
  .fancy-card {
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 0.5rem 1.5rem var(--shadow-color);
    background-color: var(--bg-card);
  }
  
  .fancy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem var(--shadow-color);
  }
  
  .card-header {
    background-color: var(--bg-card);
    color: var(--text-primary);
  }
  
  .fancy-table {
    color: var(--text-primary);
  }
  
  .fancy-table thead th {
    font-weight: 600;
    color: var(--text-secondary);
    border-top: none;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.75rem;
  }
  
  .fancy-table tbody tr {
    border-radius: 0.5rem;
    transition: all 0.2s ease;
  }
  
  .fancy-table tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
  }
  
  /* Welcome card with glass morphism */
  .glass-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
  }
  
  .welcome-text {
    background: linear-gradient(45deg, var(--primary-color), var(--info-color));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 800;
  }
  
  .welcome-subtext {
    color: var(--text-secondary);
  }
  
  .welcome-badge {
    background: linear-gradient(45deg, var(--warning-color), var(--danger-color));
    color: white;
    padding: 0.5rem 0.8rem;
    border-radius: 2rem;
  }
  
  .bg-gradient-shine {
    background: linear-gradient(120deg, #ffffff, #f8f9fc, #ffffff);
    background-size: 200% 200%;
    animation: gradient-animation 15s ease infinite;
  }

  /* Stat Cards */
  .stat-card {
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
    border: none;
    box-shadow: 0 0.5rem 1.5rem var(--shadow-color);
    height: 100%;
    background-color: var(--bg-card);
    transition: all 0.3s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem var(--shadow-color);
  }
  
  .stat-card-body {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    position: relative;
    z-index: 2;
  }
  
  .stat-card-icon {
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 12px;
    margin-right: 1rem;
    font-size: 1.5rem;
  }
  
  .stat-card-info h6 {
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
  }
  
  .stat-card-info h3 {
    margin-bottom: 0;
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--text-primary);
  }
  
  .stat-card-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50px;
    overflow: hidden;
    z-index: 1;
  }
  
  /* Color schemes for stat cards */
  .card-total {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
  }
  
  .card-total .stat-card-icon {
    background-color: rgba(255,255,255,0.2);
    color: white;
  }
  
  .card-total .stat-card-info h6,
  .card-total .stat-card-info h3 {
    color: white;
  }
  
  .card-open {
    background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
  }
  
  .card-open .stat-card-icon {
    background-color: rgba(255,255,255,0.2);
    color: white;
  }
  
  .card-open .stat-card-info h6,
  .card-open .stat-card-info h3 {
    color: white;
  }
  
  .card-resolved {
    background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
  }
  
  .card-resolved .stat-card-icon {
    background-color: rgba(255,255,255,0.2);
    color: white;
  }
  
  .card-resolved .stat-card-info h6,
  .card-resolved .stat-card-info h3 {
    color: white;
  }
  
  .card-priority {
    background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
  }
  
  .card-priority .stat-card-icon {
    background-color: rgba(255,255,255,0.2);
    color: white;
  }
  
  .card-priority .stat-card-info h6,
  .card-priority .stat-card-info h3 {
    color: white;
  }
  
  /* Buttons */
  .fancy-btn {
    border-radius: 50rem;
    padding: 0.4rem 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s;
  }
  
  .fancy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }
  
  .fancy-action-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.3s ease;
  }
  
  .fancy-action-btn:hover {
    transform: scale(1.1);
  }
  
  /* Animations */
  @keyframes gradient-animation {
    0% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
    100% {
      background-position: 0% 50%;
    }
  }
  
  .animate__animated.animate__fadeIn {
    animation-duration: 1s;
  }
  
  /* Dark theme compatibility adjustments */
  .dark-theme .glass-card {
    background: rgba(44, 47, 63, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .dark-theme .bg-gradient-shine {
    background: linear-gradient(120deg, #2c2f3f, #252836, #2c2f3f);
  }
  
  .dark-theme .welcome-text {
    background: linear-gradient(45deg, var(--info-color), var(--primary-light));
    -webkit-background-clip: text;
    background-clip: text;
  }
  
  .dark-theme .fancy-table tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.1);
  }
  
  /* Theme toggle transition */
  body {
    transition: background-color 0.3s ease;
  }
  
  /* Makes charts responsive */
  .chart-container {
    position: relative;
    margin: auto;
  }
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(0, 0, 0, 0.1);
        border-top: 5px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Enhanced Charts with Animations and Better Visuals -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
 document.addEventListener("DOMContentLoaded", function () {
  // Sets Chart.js global defaults
  Chart.defaults.font.family = "'Poppins', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
  Chart.defaults.color = '#6e707e';
  Chart.defaults.responsive = true;
  Chart.defaults.maintainAspectRatio = false;
  
  // Detects if dark theme is active
  const isDarkTheme = document.body.classList.contains('dark-theme');
  const textColor = isDarkTheme ? '#e0e0e0' : '#6e707e';
  const gridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
  
  Chart.defaults.color = textColor;
  
  // NEW AREA CHART - Ticket Activity Overview (replacing the line chart)
  $(document).ready(function () {
    $.ajax({
        url: "<?= base_url('admin/getTicketActivity'); ?>",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.labels.length === 0) {
                console.warn("No ticket activity data available.");
                return;
            }

            var ctx5 = document.getElementById('ticketActivityChart').getContext('2d');

            // Create gradient fills for the area chart
            var gradientOpen = ctx5.createLinearGradient(0, 0, 0, 350);
            gradientOpen.addColorStop(0, 'rgba(78, 115, 223, 0.4)');
            gradientOpen.addColorStop(1, 'rgba(78, 115, 223, 0.0)');

            var gradientResolved = ctx5.createLinearGradient(0, 0, 0, 350);
            gradientResolved.addColorStop(0, 'rgba(28, 200, 138, 0.4)');
            gradientResolved.addColorStop(1, 'rgba(28, 200, 138, 0.0)');

            new Chart(ctx5, {
                type: 'line',
                data: {
                    labels: response.labels,
                    datasets: [
                        {
                            label: 'New Tickets',
                            data: response.newTickets,
                            backgroundColor: gradientOpen,
                            borderColor: 'rgba(78, 115, 223, 1)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: 'rgba(78, 115, 223, 1)',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Resolved Tickets',
                            data: response.resolvedTickets,
                            backgroundColor: gradientResolved,
                            borderColor: 'rgba(28, 200, 138, 1)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: 'rgba(28, 200, 138, 1)',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.formattedValue;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    elements: {
                        line: {
                            borderWidth: 3
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching ticket activity data:", error);
        }
    });
});

// Status Chart (Enhanced Doughnut Chart)
var ctx1 = document.getElementById('ticketStatusChart').getContext('2d');
  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
      datasets: [{
        data: [<?= $openTickets ?>, <?= $inProgressTickets ?? 0 ?>, <?= $resolvedTickets ?>, <?= $closedTickets ?? 0 ?>],
        backgroundColor: [
          'rgba(78, 115, 223, 0.9)',
          'rgba(246, 194, 62, 0.9)',
          'rgba(28, 200, 138, 0.9)',
          'rgba(133, 135, 150, 0.9)'
        ],
        borderColor: isDarkTheme ? 'rgba(44, 47, 63, 0.8)' : '#ffffff',
        borderWidth: 2,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          backgroundColor: isDarkTheme ? 'rgba(44, 47, 63, 0.9)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: isDarkTheme ? '#fff' : '#333',
          bodyColor: isDarkTheme ? '#e0e0e0' : '#666',
          borderColor: isDarkTheme ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)',
          borderWidth: 1,
          padding: 12,
          cornerRadius: 8,
          callbacks: {
            label: function(context) {
              return context.label + ': ' + context.formattedValue;
            }
          }
        }
      },
      animation: {
        animateScale: true,
        animateRotate: true,
        duration: 2000,
        easing: 'easeOutQuart'
      }
    }
  });
  
  // Urgency Chart (Enhanced Donut Chart)
  var ctx2 = document.getElementById('urgencyChart').getContext('2d');
  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Low', 'Medium', 'High'],
      datasets: [{
        data: <?= json_encode($urgencyCounts) ?>,
        backgroundColor: [
          'rgba(54, 185, 204, 0.9)',
          'rgba(246, 194, 62, 0.9)',
          'rgba(231, 74, 59, 0.9)'
        ],
        borderColor: isDarkTheme ? 'rgba(44, 47, 63, 0.8)' : '#ffffff',
        borderWidth: 2,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          backgroundColor: isDarkTheme ? 'rgba(44, 47, 63, 0.9)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: isDarkTheme ? '#fff' : '#333',
          bodyColor: isDarkTheme ? '#e0e0e0' : '#666',
          borderColor: isDarkTheme ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)',
          borderWidth: 1,
          padding: 12,
          cornerRadius: 8,
          callbacks: {
            label: function(context) {
              return context.label + ' Priority: ' + context.formattedValue;
            }
          }
        }
      },
      animation: {
        animateScale: true,
        animateRotate: true,
        duration: 2000,
        easing: 'easeOutQuart'
      }
    }
  });
  
  var responseTimeLabels = <?= json_encode($responseTimeLabels ?? []); ?>;
  var responseTimeData = <?= json_encode($responseTimes ?? []); ?>;

if (responseTimeLabels.length === 0 || responseTimeData.length === 0) {
    console.warn("No response time data available.");
} else {
    var ctx3 = document.getElementById('responseTimeChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: responseTimeLabels,
            datasets: [{
                label: 'Average Response Time (hours)',
                data: responseTimeData,
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 5,
                barPercentage: 0.7,
                categoryPercentage: 0.7
            }]
        },
        options: {
            indexAxis: 'x',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.formattedValue + ' hours';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (value) => value + 'h' }
                },
                x: { grid: { display: false } }
            },
            animation: { duration: 2000, easing: 'easeOutQuart' }
        }
    });
}



  // Theme toggle detection
  // This listens for theme changes and updates charts accordingly
  const themeToggleObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
        if (document.body.classList.contains('dark-theme')) {
          // Update chart colors for dark theme
          Chart.defaults.color = '#e0e0e0';
          // You would need to destroy and recreate the charts with updated colors
          // This is simplified - a full implementation would update all charts
          console.log('Switched to dark theme');
        } else {
          // Update chart colors for light theme
          Chart.defaults.color = '#6e707e';
          console.log('Switched to light theme');
        }
      }
    });
  });
  
  // Start observing the document body for class changes
  themeToggleObserver.observe(document.body, { 
    attributes: true 
  });
});
$(document).ready(function () {
    $("#loadingSpinner").show();

    // Hide loader when the page is fully loaded
    $(window).on("load", function () {
        $("#loadingSpinner").fadeOut(500);
    });

    // Hide loader when AJAX completes
    $(document).ajaxComplete(function () {
        $("#loadingSpinner").fadeOut(500);
    });

    // Force hide after 5 seconds in case something gets stuck
    setTimeout(() => {
        $("#loadingSpinner").fadeOut(500);
    }, 5000);
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?= $this->endSection() ?> 