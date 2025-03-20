<?= $this->extend('layouts/superadmin_template') ?>

<?= $this->section('content') ?>

<!-- Ticket Table -->
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-header bg-gradient-primary p-3">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="text-black mb-0">Ticket Management</h3>
              </div>
              <div class="col-auto">
                <div class="bg-white rounded px-3 py-2">
                  <span class="text-primary fw-bold">Total Tickets: <?= count($tickets) ?></span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="card-body px-4 py-4">
            <!-- Search Bar -->
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                  </span>
                  <input type="text" id="searchBox" class="form-control border-start-0 ps-0" placeholder="Search tickets...">
                </div>
              </div>
              <div class="col-md-6 text-end">
                <!-- Filter Dropdown Button -->
                <div class="btn-group me-2">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i> Filter
                  </button>
                  <div class="dropdown-menu p-3" style="width: 280px; height:200px; overflow:scroll;">
                    <h6 class="dropdown-header">Filter Options</h6>
                    <div class="mb-3">
                      <label class="form-label">Category</label>
                      <select id="categoryFilter" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        <?php foreach (array_unique(array_column($tickets, 'category')) as $category): ?>
                          <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Urgency</label>
                      <select id="urgencyFilter" class="form-select form-select-sm">
                        <option value="">All Urgencies</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                      </select>
                    </div>
                    <div class="d-flex justify-content-between">
                      <button type="button" id="resetFilters" class="btn btn-sm btn-outline-secondary">Reset</button>
                      <button type="button" id="applyFilters" class="btn btn-sm btn-primary">Apply Filters</button>
                    </div>
                  </div>
                </div>
                
                <!-- Export Button -->
                <!-- <button type="button" class="btn btn-success me-2">
                  <i class="fas fa-file-export me-2"></i> Export
                </button> -->
                
                <!-- Refresh Button -->
                <button type="button" id="refreshTable" class="btn btn-light border">
                  <i class="fas fa-sync-alt"></i>
                </button>
              </div>
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters" class="mb-3 d-none">
              <div class="d-flex align-items-center">
                <span class="me-2 text-muted"><i class="fas fa-filter me-1"></i> Active filters:</span>
                <div id="filterBadges" class="d-flex flex-wrap gap-2">
                  <!-- Filter badges will be added here dynamically -->
                </div>
                <button id="clearAllFilters" class="btn btn-sm btn-link text-danger ms-auto">Clear All</button>
              </div>
            </div>

            <!-- Skeleton Loading -->
            <div id="tableSkeletonLoading" class="d-none">
              <div class="skeleton-loader mb-3"></div>
              <div class="skeleton-loader mb-3"></div>
              <div class="skeleton-loader mb-3"></div>
              <div class="skeleton-loader mb-3"></div>
              <div class="skeleton-loader mb-3"></div>
            </div>

            <!-- Ticket Table -->
            <div class="table-responsive">
              <table class="table align-items-center table-hover">
                <thead class="bg-light">
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ticket</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Urgency</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assigned Admin</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                  </tr>
                </thead>
                <tbody id="ticketTable">
                  <?php foreach ($tickets as $ticket): ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm ticket-title"><?= esc($ticket['title']); ?></h6>
                            <p class="text-xs text-muted mb-0">ID: <?= esc($ticket['ticket_id']); ?></p>
                          </div>
                        </div>
                      </td>
                      <td class="ticket-category">
                        <span class="badge bg-light text-dark">
                          <?= esc($ticket['category']); ?>
                        </span>
                      </td>
                      <td class="ticket-urgency">
                        <span class="badge 
                          <?= $ticket['urgency'] === 'High' ? 'bg-danger' : ($ticket['urgency'] === 'Medium' ? 'bg-warning text-dark' : 'bg-success') ?>">
                          <?= esc($ticket['urgency']); ?>
                        </span>
                      </td>
                      <td class="ticket-status">
                        <span class="badge 
                          <?= $ticket['status'] === 'Open' ? 'bg-info' : 
                            ($ticket['status'] === 'In Progress' ? 'bg-warning text-dark' : 
                              ($ticket['status'] === 'Resolved' ? 'bg-success' : 'bg-secondary')) ?>">
                          <?= esc($ticket['status']); ?>
                        </span>
                      </td>
                      <td>
                        <?php if (!empty($ticket['assigned_admin_id'])): ?>
                          <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center">
                              <span class="text-white text-uppercase"><?= substr($ticket['assigned_admin_name'], 0, 1) ?></span>
                            </div>
                            <span class="text-success fw-bold"><?= esc($ticket['assigned_admin_name']); ?></span>
                          </div>
                        <?php else: ?>
                          <span class="text-danger">Not Assigned</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="d-flex flex-column">
                          <span class="text-sm"><?= date('d M Y', strtotime($ticket['created_at'])) ?></span>
                          <span class="text-xs text-muted"><?= date('H:i A', strtotime($ticket['created_at'])) ?></span>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex">
                          <!-- Assign Admin Dropdown -->
                          <div class="dropdown me-2">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                              Assign
                            </button>
                            <div class="dropdown-menu p-2" style="width: 240px;">
                              <form action="<?= base_url('superadmin/assignAdmin') ?>" method="post">
                                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                <div class="mb-2">
                                  <select name="admin_id" class="form-select form-select-sm" required>
                                    <option value="">Select Admin</option>
                                    <?php foreach ($admins as $admin): ?>
                                      <?php if ($admin['category'] === $ticket['category']): ?>
                                        <option value="<?= $admin['id'] ?>" <?= ($ticket['assigned_admin_id'] == $admin['id']) ? 'selected' : '' ?>>
                                          <?= $admin['name'] ?>
                                        </option>
                                      <?php endif; ?>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">Save Assignment</button>
                              </form>
                            </div>
                          </div>
                          
                          <!-- History Button -->
                          <button type="button" class="btn btn-sm btn-outline-info history-btn" 
                            data-ticket-id="<?= $ticket['id'] ?>" 
                            data-ticket-title="<?= esc($ticket['title']) ?>">
                            <i class="fas fa-history"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div> <!-- End Table Responsive -->
            
            <!-- No Results Message -->
            <div id="noResults" class="text-center py-4 d-none">
              <div class="mb-3">
                <i class="fas fa-search fa-3x text-muted"></i>
              </div>
              <h5>No matching tickets found</h5>
              <p class="text-muted">Try adjusting your search criteria or clearing filters</p>
              <button id="clearAllFiltersBtn" class="btn btn-outline-primary">Clear All Filters</button>
            </div>
            
          </div> <!-- End Card Body -->
        </div> <!-- End Card -->
      </div>
    </div>
  </div>
</main>

<!-- Ticket History Modal -->
<div class="modal fade" id="ticketHistoryModal" tabindex="-1" aria-labelledby="ticketHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title text-white" id="ticketHistoryModalLabel">Ticket History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Skeleton Loading for Modal -->
        <div id="modalSkeletonLoading">
          <div class="skeleton-loader mb-3"></div>
          <div class="skeleton-loader mb-3"></div>
          <div class="skeleton-loader mb-3"></div>
          <div class="skeleton-loader mb-3"></div>
        </div>
        
        <!-- Ticket Info -->
        <div class="ticket-info mb-4 d-none">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-primary">Ticket Information</h6>
            <span class="badge bg-primary" id="modalTicketId"></span>
          </div>
          <h5 id="modalTicketTitle"></h5>
        </div>
        
        <!-- Timeline -->
        <div class="timeline mt-4 d-none">
          <h6 class="mb-3 text-primary">Activity Timeline</h6>
          <div class="timeline-container" id="ticketTimeline">
            <!-- Timeline items will be inserted here -->
          </div>
        </div>
        
        <!-- No History Message -->
        <div id="noHistoryMessage" class="text-center py-4 d-none">
          <div class="mb-3">
            <i class="fas fa-info-circle fa-3x text-muted"></i>
          </div>
          <h5>No history available</h5>
          <p class="text-muted">This ticket doesn't have any status changes or assignment history yet.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Additional CSS -->
<style>
  /* Skeleton Loading */
  .skeleton-loader {
    height: 40px;
    width: 100%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 4px;
  }
  
  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
  
.timeline-container {
    position: relative;
    padding-left: 40px;
  }
  
  .timeline-item {
    position: relative;
    margin-bottom: 25px;
    padding-bottom: 15px;
  }
  
  .timeline-item:before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    height: 100%;
    width: 2px;
    background-color: #e9ecef;
  }
  
  .timeline-item:last-child:before {
    height: 10px;
  }
  
  .timeline-item .timeline-point {
    position: absolute;
    left: -39px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #fff;
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    z-index: 1;
  }
  
  .timeline-point.assignment { border-color: #4CAF50; color: #4CAF50; }
  .timeline-point.status-change { border-color: #2196F3; color: #2196F3; }
  .timeline-point.created { border-color: #9C27B0; color: #9C27B0; }
  
  .timeline-content {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 6px;
  }
  
  .timeline-date {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
  }
  
  .timeline-title {
    font-weight: 600;
    margin-bottom: 8px;
  }
  
  .timeline-details {
    font-size: 14px;
  }
</style>

<!-- Toastr and JS scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize toastr options
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      timeOut: 3000
    };
    
    // Search functionality
    const searchBox = document.getElementById('searchBox');
    const ticketTable = document.getElementById('ticketTable');
    const noResults = document.getElementById('noResults');
    
    searchBox.addEventListener('keyup', function() {
      filterTickets();
    });
    
    // Filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    const urgencyFilter = document.getElementById('urgencyFilter');
    const statusFilter = document.getElementById('statusFilter');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const activeFilters = document.getElementById('activeFilters');
    const filterBadges = document.getElementById('filterBadges');
    const clearAllFilters = document.getElementById('clearAllFilters');
    const clearAllFiltersBtn = document.getElementById('clearAllFiltersBtn');
    
    applyFilters.addEventListener('click', function() {
      updateFilterBadges();
      filterTickets();
    });
    
    resetFilters.addEventListener('click', function() {
      categoryFilter.value = '';
      urgencyFilter.value = '';
      statusFilter.value = '';
      activeFilters.classList.add('d-none');
      filterBadges.innerHTML = '';
      filterTickets();
    });
    
    clearAllFilters.addEventListener('click', function() {
      resetAllFilters();
    });
    
    clearAllFiltersBtn.addEventListener('click', function() {
      resetAllFilters();
    });
    
    function resetAllFilters() {
      searchBox.value = '';
      categoryFilter.value = '';
      urgencyFilter.value = '';
      statusFilter.value = '';
      activeFilters.classList.add('d-none');
      filterBadges.innerHTML = '';
      filterTickets();
    }
    
    function updateFilterBadges() {
      filterBadges.innerHTML = '';
      let hasFilters = false;
      
      if (categoryFilter.value) {
        addFilterBadge('Category: ' + categoryFilter.value, 'category');
        hasFilters = true;
      }
      
      if (urgencyFilter.value) {
        addFilterBadge('Urgency: ' + urgencyFilter.value, 'urgency');
        hasFilters = true;
      }
      
      if (statusFilter.value) {
        addFilterBadge('Status: ' + statusFilter.value, 'status');
        hasFilters = true;
      }
      
      if (hasFilters) {
        activeFilters.classList.remove('d-none');
      } else {
        activeFilters.classList.add('d-none');
      }
    }
    
    function addFilterBadge(text, type) {
      const badge = document.createElement('div');
      badge.className = 'badge bg-light text-dark px-3 py-2 d-flex align-items-center';
      badge.innerHTML = `
        <span>${text}</span>
        <button class="btn btn-link btn-sm text-danger p-0 ms-2" data-filter-type="${type}">
          <i class="fas fa-times"></i>
        </button>
      `;
      
      badge.querySelector('button').addEventListener('click', function() {
        const filterType = this.getAttribute('data-filter-type');
        if (filterType === 'category') categoryFilter.value = '';
        if (filterType === 'urgency') urgencyFilter.value = '';
        if (filterType === 'status') statusFilter.value = '';
        
        updateFilterBadges();
        filterTickets();
      });
      
      filterBadges.appendChild(badge);
    }
    
    function filterTickets() {
  const searchTerm = searchBox.value.toLowerCase();
  const category = categoryFilter.value.toLowerCase();
  const urgency = urgencyFilter.value.toLowerCase();
  const status = statusFilter.value.toLowerCase();

  const rows = ticketTable.querySelectorAll('tr');
  let visibleCount = 0;

  rows.forEach(row => {
    const ticketIdElement = row.querySelector('.ticket-title + p'); // Selects the <p> tag next to .ticket-title
    const ticketId = ticketIdElement ? ticketIdElement.textContent.replace('ID: ', '').toLowerCase() : ''; // Extract ID text
    const title = row.querySelector('.ticket-title')?.textContent.toLowerCase() || '';
    const rowCategory = row.querySelector('.ticket-category')?.textContent.toLowerCase() || '';
    const rowUrgency = row.querySelector('.ticket-urgency')?.textContent.toLowerCase() || '';
    const rowStatus = row.querySelector('.ticket-status')?.textContent.toLowerCase() || '';

    const matchesSearch = ticketId.includes(searchTerm) || title.includes(searchTerm);
    const matchesCategory = category === '' || rowCategory.includes(category);
    const matchesUrgency = urgency === '' || rowUrgency.includes(urgency);
    const matchesStatus = status === '' || rowStatus.includes(status);

    if (matchesSearch && matchesCategory && matchesUrgency && matchesStatus) {
      row.classList.remove('d-none');
      visibleCount++;
    } else {
      row.classList.add('d-none');
    }
  });

  if (visibleCount === 0) {
    noResults.classList.remove('d-none');
  } else {
    noResults.classList.add('d-none');
  }
}

    // Refresh button functionality
    const refreshTable = document.getElementById('refreshTable');
    const tableSkeletonLoading = document.getElementById('tableSkeletonLoading');
    
    refreshTable.addEventListener('click', function() {
      // Show skeleton loader
      const tableContent = document.querySelector('.table-responsive');
      tableContent.classList.add('d-none');
      tableSkeletonLoading.classList.remove('d-none');
      
      // Simulate refresh delay
      setTimeout(function() {
        // Hide skeleton, show table
        tableSkeletonLoading.classList.add('d-none');
        tableContent.classList.remove('d-none');
        
        // Show success message
        toastr.success('Ticket data refreshed successfully!');
      }, 1000);
    });
    
    // Ticket history modal functionality
 const historyButtons = document.querySelectorAll('.history-btn');
 const modalSkeletonLoading = document.getElementById('modalSkeletonLoading');
 const ticketInfo = document.querySelector('.ticket-info');
 const timeline = document.querySelector('.timeline');
 const noHistoryMessage = document.getElementById('noHistoryMessage');
 const modalTicketId = document.getElementById('modalTicketId');
 const modalTicketTitle = document.getElementById('modalTicketTitle');
 const ticketTimeline = document.getElementById('ticketTimeline');

 historyButtons.forEach(button => {
    button.addEventListener('click', function () {
        const ticketId = this.getAttribute('data-ticket-id');
        const ticketTitle = this.getAttribute('data-ticket-title');

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('ticketHistoryModal'));
        modal.show();

        // Show loading, hide content
        modalSkeletonLoading.classList.remove('d-none');
        ticketInfo.classList.add('d-none');
        timeline.classList.add('d-none');
        noHistoryMessage.classList.add('d-none');

        // Update ticket info in modal
        modalTicketId.textContent = 'ID: ' + ticketId;
        modalTicketTitle.textContent = ticketTitle;

        // Fetch real ticket history from API
        fetch(`/ticket/getTicketHistory/${ticketId}`)
            .then(response => response.json())
            .then(data => {
                modalSkeletonLoading.classList.add('d-none');

                if (data.length > 0) {
                    ticketInfo.classList.remove('d-none');
                    timeline.classList.remove('d-none');
                    displayTicketHistory(data);
                } else {
                    noHistoryMessage.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error fetching ticket history:', error);
                modalSkeletonLoading.classList.add('d-none');
                noHistoryMessage.classList.remove('d-none');
            });
    });
});

function displayTicketHistory(historyData) {
    ticketTimeline.innerHTML = ''; // Clear previous timeline

    historyData.forEach(item => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';

        let iconClass = "fas fa-sync-alt"; // Default for status changes
        let eventText = `${item.old_status} → ${item.new_status}`; // Default transition text

        if (item.old_status === null && item.new_status === "Open") {
            iconClass = "fas fa-plus-circle"; // Ticket creation
            eventText = "Ticket Created";
        } else if (item.assigned_admin_name) {
            iconClass = "fas fa-user-tag"; // Admin reassignment
            eventText = `Reassigned to ${item.assigned_admin_name}`;
        }

        timelineItem.innerHTML = `
            <div class="timeline-point status-${item.new_status?.toLowerCase() || 'created'}">
                <i class="${iconClass}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-date">${formatDate(item.changed_at)}</div>
                <div class="timeline-title">${eventText}</div>
                <div class="timeline-details">Updated by <b>${item.admin_name}</b></div>
            </div>
        `;

        ticketTimeline.appendChild(timelineItem);
    });
}
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
  });
</script>

<?= $this->endSection() ?>