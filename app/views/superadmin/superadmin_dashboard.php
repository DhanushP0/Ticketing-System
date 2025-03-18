<?= $this->extend('layouts/superadmin_template') ?>

<?= $this->section('content') ?>
<body class="g-sidenav-show bg-gray-100">
  <div class="container-fluid py-4">
    <!-- Welcome Card -->
    <div class="card shadow-lg border-0 rounded-4 mb-4">
      <div class="card-body text-center">
        <h3 class="fw-bold text-primary">Welcome, <?= session()->get('admin_name'); ?>! 🎉</h3>
        <p class="text-muted">
          Here's your system overview for today - <?= date('F d, Y'); ?>
        </p>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
      <!-- Tickets Stats -->
      <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Tickets</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= count($tickets); ?>
                    <?php $ticketGrowth = 12; // Example value, replace with actual calculation ?>
                    <span class="text-success text-sm font-weight-bolder"><?= $ticketGrowth; ?>%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle">
                  <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Users Stats -->
      <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Users</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= isset($totalUsers) ? $totalUsers : 0; ?>
                    <?php $userGrowth = 3; // Example value, replace with actual calculation ?>
                    <span class="text-success text-sm font-weight-bolder"><?= $userGrowth; ?>%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
                  <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Active Admins Stats -->
      <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Admins</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= isset($activeAdmins) ? $activeAdmins : 0; ?>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                  <i class="ni ni-badge text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Resolved Tickets Stats -->
      <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Resolved Tickets</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php 
                      $resolvedTickets = 0;
                      foreach ($tickets as $ticket) {
                        if ($ticket['status'] === 'resolved') {
                          $resolvedTickets++;
                        }
                      }
                      echo $resolvedTickets;
                    ?>
                    <span class="text-success text-sm font-weight-bolder"><?= isset($resolutionRate) ? $resolutionRate : 0; ?>%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
                  <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
      <!-- Tickets Overview Chart -->
      <div class="col-lg-7 mb-4">
        <div class="card shadow-lg">
          <div class="card-header pb-0 p-3">
            <div class="d-flex justify-content-between">
              <h6 class="mb-0">Tickets Overview</h6>
              <div class="dropdown">
                <button class="btn btn-link text-secondary mb-0" id="ticketDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-ellipsis-v text-xs"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="ticketDropdown">
                  <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                  <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                  <li><a class="dropdown-item" href="#">Last Quarter</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <div class="chart">
              <canvas id="tickets-chart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Ticket Status Distribution -->
      <div class="col-lg-5 mb-4">
        <div class="card shadow-lg">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Ticket Status Distribution</h6>
          </div>
          <div class="card-body p-3">
            <div class="chart">
              <canvas id="ticket-status-chart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity and Performance Row -->
    <div class="row">
      <!-- Admin Activity -->
      <div class="col-lg-8 mb-4">
    <div class="card shadow-lg">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Admin Activity Log</h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="adminActivityLog">
                        <tr><td colspan="4" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function fetchAdminActivityLog() {
    fetch("<?= base_url('admin/getAdminActivityLog') ?>")
    .then(response => response.json())
    .then(data => {
        let logHtml = "";
        if (data.length > 0) {
            data.forEach(activity => {
                logHtml += `
                    <tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">${activity.admin_name}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">${activity.action}</p>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <span class="badge badge-sm bg-gradient-${activity.action === 'Login' ? 'success' : 'danger'}">${activity.action}</span>
                        </td>
                        <td class="align-middle text-center">
                            <span class="text-secondary text-xs font-weight-bold">${new Date(activity.timestamp).toLocaleString()}</span>
                        </td>
                    </tr>
                `;
            });
        } else {
            logHtml = `<tr><td colspan="4" class="text-center">No activity records found</td></tr>`;
        }
        document.getElementById("adminActivityLog").innerHTML = logHtml;
    })
    .catch(error => console.error("Error fetching activity log:", error));
}

// Fetch logs on page load
fetchAdminActivityLog();
</script>


      <!-- Admin Performance -->
      <div class="col-lg-4 mb-4">
        <div class="card shadow-lg">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Admin Performance</h6>
          </div>
          <div class="card-body p-3">
            <?php if(isset($adminPerformance) && count($adminPerformance) > 0): ?>
              <?php foreach($adminPerformance as $admin): ?>
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1">
                    <span class="text-sm font-weight-bold"><?= $admin['name']; ?></span>
                    <span class="text-sm font-weight-bold"><?= $admin['performance']; ?>%</span>
                  </div>
                  <div class="progress">
                    <div class="progress-bar bg-gradient-<?php
                      if($admin['performance'] >= 80) echo 'success';
                      elseif($admin['performance'] >= 60) echo 'info';
                      elseif($admin['performance'] >= 40) echo 'warning';
                      else echo 'danger';
                    ?>" role="progressbar" aria-valuenow="<?= $admin['performance']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $admin['performance']; ?>%"></div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center">No performance data available</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="row">
      <!-- Response Time -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Avg. Response Time</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= isset($avgResponseTime) ? $avgResponseTime : '2.4'; ?> hours
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-danger shadow text-center rounded-circle">
                  <i class="ni ni-watch-time text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Resolution Rate -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Resolution Rate</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= isset($resolutionRate) ? $resolutionRate : '85'; ?>%
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
                  <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- User Satisfaction -->
      <div class="col-lg-4 col-md-12 mb-4">
        <div class="card shadow-lg">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">User Satisfaction</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?= isset($userSatisfaction) ? $userSatisfaction : '4.8'; ?>/5
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                  <i class="ni ni-like-2 text-lg opacity-10" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Files -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

  <script>
    // Initialize charts when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
      // Ticket Overview Chart - Last 7 days
      var ticketsCtx = document.getElementById('tickets-chart').getContext('2d');
      var ticketsChart = new Chart(ticketsCtx, {
        type: 'line',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [
            {
              label: 'New Tickets',
              data: [15, 20, 18, 22, 17, 10, 12],
              borderColor: '#5e72e4',
              backgroundColor: 'rgba(94, 114, 228, 0.1)',
              tension: 0.4,
              fill: true
            },
            {
              label: 'Resolved Tickets',
              data: [12, 17, 14, 18, 16, 9, 10],
              borderColor: '#2dce89',
              backgroundColor: 'rgba(45, 206, 137, 0.1)',
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Ticket Status Distribution Chart
      var statusCtx = document.getElementById('ticket-status-chart').getContext('2d');
      var statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: ['Open', 'In Progress', 'Resolved', 'Closed', 'Pending'],
          datasets: [{
            data: [25, 30, 20, 15, 10],
            backgroundColor: [
              '#f5365c', // open - danger
              '#fb6340', // in progress - orange
              '#2dce89', // resolved - success
              '#11cdef', // closed - info
              '#ffd600'  // pending - warning
            ],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            }
          },
          cutout: '70%'
        }
      });
    });

    // Display notification when the page loads
    $(document).ready(function() {
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
      };
      toastr.success('Dashboard loaded successfully!', 'Welcome');
    });
  </script>
</body>
<?= $this->endSection() ?>