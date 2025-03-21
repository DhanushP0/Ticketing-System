<?= $this->extend('layouts/superadmin_template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Ticket Management Main Content -->
<main class="main-content">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
          <!-- Header with stats summary -->
          <div class="card-header bg-gradient-primary p-4">
            <div class="row align-items-center">
              <div class="col-lg-6" style="width: 100%;">
                <h3 class="text-black mb-0 fw-italic">Ticket Management</h3>
              </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-between mt-3 mt-lg-0 mb-3">
              <div class="bg-white rounded px-4 py-3">
                <span class="text-primary fw-bold fs-5">Total Tickets: <?= count($tickets) ?></span>
              </div>
              <!-- Refresh Button -->
              <button type="button" id="refreshTable" class="btn btn-light btn-lg rounded-pill shadow-sm px-4">
                <i class="fas fa-sync-alt me-2"></i>Refresh Data
              </button>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <div class="d-flex">
                  <div class="me-3">
                    <i class="fas fa-check-circle fa-lg"></i>
                  </div>
                  <div>
                    <?= session()->getFlashdata('success') ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <div class="d-flex">
                  <div class="me-3">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                  </div>
                  <div>
                    <?= session()->getFlashdata('error') ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!-- Search and Filter Bar -->
            <div class="row g-3 mb-4">
              <div class="col-lg-6">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                  <span class="input-group-text border-0 bg-white px-3">
                    <i class="fas fa-search text-primary"></i>
                  </span>
                  <input type="text" class="form-control border-0 py-2" id="searchBox" 
                    placeholder="Search tickets by ID or title...">
                </div>
              </div>
              <div class="col-lg-6 d-flex justify-content-lg-end">
                <div class="btn-group rounded-pill shadow-sm">
                  <button class="btn btn-light px-3 py-2" id="currentFilter">
                    <i class="fas fa-filter me-2"></i>All Tickets
                  </button>
                  <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow p-3" style="width: 320px; max-height: 300px; overflow:scroll;">
                    <li>
                      <h6 class="dropdown-header">Filter Options</h6>
                    </li>
                    <li class="mb-3">
                      <label class="form-label fw-semibold">Category</label>
                      <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach (array_unique(array_column($tickets, 'category')) as $category): ?>
                          <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </li>
                    <li class="mb-3">
                      <label class="form-label fw-semibold">Urgency</label>
                      <select id="urgencyFilter" class="form-select">
                        <option value="">All Urgencies</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                      </select>
                    </li>
                    <li class="mb-3">
                      <label class="form-label fw-semibold">Status</label>
                      <select id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                      </select>
                    </li>
                    <li>
                      <div class="d-flex justify-content-between mt-2">
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary">Reset</button>
                        <button type="button" id="applyFilters" class="btn btn-primary px-4">Apply Filters</button>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters" class="mb-4 d-none">
              <div class="d-flex align-items-center bg-light p-3 rounded-3">
                <span class="me-3 text-muted fs-5"><i class="fas fa-filter me-2"></i> Active filters:</span>
                <div id="filterBadges" class="d-flex flex-wrap gap-3">
                  <!-- Filter badges will be added here dynamically -->
                </div>
                <button id="clearAllFilters" class="btn btn-link text-danger ms-auto fw-semibold">Clear All</button>
              </div>
            </div>

            <!-- Skeleton Loading -->
            <div id="tableSkeletonLoading" class="d-none">
              <div class="skeleton-loader mb-4"></div>
              <div class="skeleton-loader mb-4"></div>
              <div class="skeleton-loader mb-4"></div>
              <div class="skeleton-loader mb-4"></div>
              <div class="skeleton-loader mb-4"></div>
            </div>

            <!-- Ticket Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover table-striped" id="ticketTable">
                <thead>
                  <tr>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Ticket</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Category</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Urgency</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Status</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Assigned Admin</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Created At</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7 text-end">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($tickets as $ticket): ?>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center px-2 py-3">
                          <div class="d-flex flex-column">
                            <h6 class="mb-0 text-sm ticket-title"><?= esc($ticket['title']); ?></h6>
                            <p class="text-secondary text-xs mb-0">ID: <?= esc($ticket['ticket_id']); ?></p>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle ticket-category">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                          <?= esc($ticket['category']); ?>
                        </span>
                      </td>
                      <td class="align-middle ticket-urgency">
                        <span class="badge rounded-pill px-3 py-2
                          <?= $ticket['urgency'] === 'High' ? 'bg-danger' : ($ticket['urgency'] === 'Medium' ? 'bg-warning text-dark' : 'bg-success') ?>">
                          <?= esc($ticket['urgency']); ?>
                        </span>
                      </td>
                      <td class="align-middle ticket-status">
                        <span class="badge rounded-pill px-3 py-2
                          <?= $ticket['status'] === 'Open' ? 'bg-info' : 
                            ($ticket['status'] === 'In Progress' ? 'bg-warning text-dark' : 
                              ($ticket['status'] === 'Resolved' ? 'bg-success' : 'bg-secondary')) ?>">
                          <?= esc($ticket['status']); ?>
                        </span>
                      </td>
                      <td class="align-middle">
                        <?php if (!empty($ticket['assigned_admin_id'])): ?>
                          <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-gradient-primary rounded-circle me-3 text-white d-flex align-items-center justify-content-center">
                              <span><?= substr($ticket['assigned_admin_name'], 0, 1) ?></span>
                            </div>
                            <span class="text-sm"><?= esc($ticket['assigned_admin_name']); ?></span>
                          </div>
                        <?php else: ?>
                          <span class="text-danger text-sm fw-semibold">Not Assigned</span>
                        <?php endif; ?>
                      </td>
                      <td class="align-middle">
                        <div class="d-flex flex-column">
                          <span class="text-sm fw-semibold"><?= date('d M Y', strtotime($ticket['created_at'])) ?></span>
                          <span class="text-xs text-muted"><?= date('H:i A', strtotime($ticket['created_at'])) ?></span>
                        </div>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex justify-content-end gap-2">
                          <!-- Assign Admin Button -->
                          <div class="dropdown">
                            <button class="btn btn-sm btn-primary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                              <i class="fas fa-user-tag me-1"></i> Assign
                            </button>
                            <div class="dropdown-menu p-3" style="width: 280px;">
                              <form action="<?= base_url('superadmin/assignAdmin') ?>" method="post">
                                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                <div class="mb-3">
                                  <select name="admin_id" class="form-select" required>
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
                                <button type="submit" class="btn btn-primary w-100">Save Assignment</button>
                              </form>
                            </div>
                          </div>
                          
                          <!-- History Button -->
                          <button type="button" class="btn btn-sm btn-outline-info rounded-pill history-btn" 
                            data-ticket-id="<?= $ticket['id'] ?>" 
                            data-ticket-title="<?= esc($ticket['title']) ?>">
                            <i class="fas fa-history me-1"></i> History
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div> <!-- End Table Responsive -->
            
            <!-- No Results Message -->
            <div id="noResults" class="text-center py-5 d-none">
              <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted"></i>
              </div>
              <h4 class="mb-3">No matching tickets found</h4>
              <p class="text-muted fs-5 mb-4">Try adjusting your search criteria or clearing filters</p>
              <button id="clearAllFiltersBtn" class="btn btn-outline-primary btn-lg px-4">Clear All Filters</button>
            </div>
            
            <!-- Empty State -->
            <?php if (empty($tickets)): ?>
              <div class="text-center py-5">
                <div class="mb-4">
                  <div class="avatar avatar-xl avatar-rounded bg-light p-4 mx-auto">
                    <i class="fas fa-ticket-alt fa-4x text-secondary opacity-6"></i>
                  </div>
                </div>
                <h4 class="text-secondary">No tickets found</h4>
                <p class="text-muted">There are currently no support tickets in the system</p>
              </div>
            <?php endif; ?>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
              <?= $pager->links ?? '' ?>
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
      <div class="modal-header bg-gradient-info p-4">
        <h4 class="modal-title text-black fw-bold" id="ticketHistoryModalLabel">Ticket History</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Skeleton Loading for Modal -->
        <div id="modalSkeletonLoading">
          <div class="skeleton-loader mb-4"></div>
          <div class="skeleton-loader mb-4"></div>
          <div class="skeleton-loader mb-4"></div>
          <div class="skeleton-loader mb-4"></div>
        </div>
        
        <!-- Ticket Info -->
        <div class="ticket-info mb-5 d-none">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 text-primary fw-bold">Ticket Information</h5>
            <span class="badge bg-primary px-3 py-2 fs-6" id="modalTicketId"></span>
          </div>
          <h4 id="modalTicketTitle" class="fw-bold"></h4>
        </div>
        
        <!-- Timeline -->
        <div class="timeline mt-5 d-none">
          <h5 class="mb-4 text-primary fw-bold">Activity Timeline</h5>
          <div class="timeline-container" id="ticketTimeline">
            <!-- Timeline items will be inserted here -->
          </div>
        </div>
        
        <!-- No History Message -->
        <div id="noHistoryMessage" class="text-center py-5 d-none">
          <div class="mb-4">
            <i class="fas fa-info-circle fa-4x text-muted"></i>
          </div>
          <h4 class="mb-3">No history available</h4>
          <p class="text-muted fs-5">This ticket doesn't have any status changes or assignment history yet.</p>
        </div>
      </div>
      <div class="modal-footer p-3">
        <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Additional CSS -->
<style>
  /* Skeleton Loading */
  .skeleton-loader {
    height: 60px;
    width: 100%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
  }
  
  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
  
  .timeline-container {
    position: relative;
    padding-left: 50px;
  }
  
  .timeline-item {
    position: relative;
    margin-bottom: 35px;
    padding-bottom: 20px;
  }
  
  .timeline-item:before {
    content: '';
    position: absolute;
    left: -35px;
    top: 0;
    height: 100%;
    width: 3px;
    background-color: #e9ecef;
  }
  
  .timeline-item:last-child:before {
    height: 15px;
  }
  
  .timeline-item .timeline-point {
    position: absolute;
    left: -46px;
    top: 0;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background-color: #fff;
    border: 3px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 1;
  }
  
  .timeline-point.assignment { border-color: #4CAF50; color: #4CAF50; }
  .timeline-point.status-change { border-color: #2196F3; color: #2196F3; }
  .timeline-point.status-open { border-color: #2196F3; color: #2196F3; }
  .timeline-point.status-in { border-color: #FF9800; color: #FF9800; }
  .timeline-point.status-resolved { border-color: #4CAF50; color: #4CAF50; }
  .timeline-point.status-closed { border-color: #9E9E9E; color: #9E9E9E; }
  .timeline-point.status-created { border-color: #9C27B0; color: #9C27B0; }
  
  .timeline-content {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }
  
  .timeline-date {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 8px;
  }
  
  .timeline-title {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 10px;
  }
  
  .timeline-details {
    font-size: 15px;
  }
  
  .avatar-md {
    width: 40px;
    height: 40px;
  }
  
  /* Add hover effect for rows */
  .table > tbody > tr:hover {
    background-color: #f8f9fa;
  }
  
  /* Pagination styles */
  .pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
  }
  
  .pagination li {
    margin: 0 3px;
  }
  
  .pagination li a,
  .pagination li span {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #5e72e4;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    text-decoration: none;
  }
  
  .pagination li.active a {
    z-index: 3;
    color: #fff;
    background-color: #5e72e4;
    border-color: #5e72e4;
  }
  
  .pagination li.disabled span {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
  }
  
  .pagination li a:hover {
    z-index: 2;
    color: #233dd2;
    text-decoration: none;
    background-color: #e9ecef;
    border-color: #dee2e6;
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
      timeOut: 4000
    };
    
    // Search functionality
    const searchBox = document.getElementById('searchBox');
    const ticketTable = document.getElementById('ticketTable');
    const noResults = document.getElementById('noResults');
    const currentFilter = document.getElementById('currentFilter');
    
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
      
      // Update current filter text
      let filterText = 'All Tickets';
      const filters = [];
      
      if (categoryFilter.value) filters.push(categoryFilter.value);
      if (urgencyFilter.value) filters.push(urgencyFilter.value);
      if (statusFilter.value) filters.push(statusFilter.value);
      
      if (filters.length > 0) {
        filterText = filters.join(', ');
      }
      
      currentFilter.innerHTML = `<i class="fas fa-filter me-2"></i>${filterText}`;
    });
    
    resetFilters.addEventListener('click', function() {
      categoryFilter.value = '';
      urgencyFilter.value = '';
      statusFilter.value = '';
      activeFilters.classList.add('d-none');
      filterBadges.innerHTML = '';
      currentFilter.innerHTML = '<i class="fas fa-filter me-2"></i>All Tickets';
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
      currentFilter.innerHTML = '<i class="fas fa-filter me-2"></i>All Tickets';
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
      badge.className = 'badge bg-white text-dark px-3 py-2 d-flex align-items-center shadow-sm';
      badge.innerHTML = `
        <span class="fs-6">${text}</span>
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
        const ticketIdElement = row.querySelector('.text-xs'); // Get the ticket ID element
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
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
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
        let pointClass = "status-change";
        let eventText = `${item.old_status} → ${item.new_status}`; // Default transition text

        if (item.old_status === null && item.new_status === "Open") {
          iconClass = "fas fa-plus-circle"; // Ticket creation
          pointClass = "status-created";
          eventText = "Ticket Created";
        } else if (item.assigned_admin_name) {
          iconClass = "fas fa-user-tag"; // Admin reassignment
          pointClass = "assignment";
          eventText = `Reassigned to ${item.assigned_admin_name}`;
        } else if (item.new_status) {
          // Set specific point class based on new status
          pointClass = "status-" + item.new_status.toLowerCase().replace(' ', '');
        }

        timelineItem.innerHTML = `
          <div class="timeline-point ${pointClass}">
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
  
  $(document).ready(function() {
    <?php if (session()->getFlashdata('success')): ?>
      toastr.success("<?= session()->getFlashdata('success') ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      toastr.error("<?= session()->getFlashdata('error') ?>");
    <?php endif; ?>
  });
</script>

<?= $this->endSection() ?>