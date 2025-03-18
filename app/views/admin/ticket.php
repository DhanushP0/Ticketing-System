<?= $this->extend('layouts/admin_template') ?>

<?= $this->section('content') ?>
<!-- Modern Styling and Libraries -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <h2 class="text-primary fw-bold mb-4">Ticket Management</h2>
          <p class="text-muted mb-4">Welcome, <strong><?= esc(session()->get('admin_name')); ?></strong></p>

          <!-- Filter Dropdown Button -->
          <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary" type="button" id="filterToggleBtn">
              <i class="fas fa-filter"></i> Advanced Filters
            </button>
          </div>


          <!-- Filter Dropdown -->
          <div class="collapse mt-3" id="filterDropdown">
            <div class="card card-body">
              <form method="GET" id="advancedFilterForm">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="modalFilterTicketId" class="form-label">Ticket ID</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                      <input type="text" name="ticket_id" id="modalFilterTicketId" class="form-control"
                        value="<?= isset($_GET['ticket_id']) ? esc($_GET['ticket_id']) : '' ?>"
                        placeholder="Enter Ticket ID">
                    </div>
                  </div>


                  <div class="col-md-6">
                    <label for="modalFilterTitle" class="form-label">Title</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-heading"></i></span>
                      <input type="text" name="title" id="modalFilterTitle" class="form-control"
                        value="<?= isset($_GET['title']) ? esc($_GET['title']) : '' ?>" placeholder="Enter title">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="modalFilterUrgency" class="form-label">Urgency</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                      <select name="urgency" id="modalFilterUrgency" class="form-select">
                        <option value="all">All Urgencies</option>
                        <option value="High" <?= isset($_GET['urgency']) && $_GET['urgency'] == 'High' ? 'selected' : '' ?>>High</option>
                        <option value="Medium" <?= isset($_GET['urgency']) && $_GET['urgency'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="Low" <?= isset($_GET['urgency']) && $_GET['urgency'] == 'Low' ? 'selected' : '' ?>>
                          Low</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="modalFilterStatus" class="form-label">Status</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                      <select name="status" id="modalFilterStatus" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="Open" <?= isset($_GET['status']) && $_GET['status'] == 'Open' ? 'selected' : '' ?>>
                          Open</option>
                        <option value="In Progress" <?= isset($_GET['status']) && $_GET['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Resolved" <?= isset($_GET['status']) && $_GET['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                      </select>
                    </div>
                  </div>
                  </div>

                <div class="d-flex justify-content-between mt-4">
                  <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-2"></i>Reset Filters
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Apply Filters
                  </button>
                </div>
              </form>
            </div>
          </div>


          <!-- Active Filters Display -->
          <?php if (
            isset($_GET) && count(array_filter($_GET, function ($value) {
                        return $value !== 'all' && $value !== '';
                      })) > 0
          ): ?>
            
            <div class="alert alert-info mb-4">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong><i class="fas fa-filter me-2"></i>Active Filters:</strong>
                  <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($value !== 'all' && $value !== ''): ?>
                      <span class="badge bg-primary me-2">
                        <?= esc(ucfirst(str_replace('_', ' ', $key))) ?>: <?= esc($value) ?>
                        <a href="<?= current_url() . '?' . http_build_query(array_diff_key($_GET, [$key => ''])) ?>"
                          class="text-white ms-1">
                          <i class="fas fa-times"></i>
                        </a>
                      </span>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
                <a href="<?= current_url() ?>" class="btn btn-sm btn-outline-primary">Clear All</a>
              </div>
            </div>
          <?php endif; ?>

          <!-- Tickets Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle" id="ticketsTable">
              <thead class="bg-light">
                <tr>
                  <th class="fw-bold">Ticket ID</th>
                  <th class="fw-bold">Title</th>
                  <th class="fw-bold">Description</th>
                  <th class="fw-bold">Urgency</th>
                  <th class="fw-bold">Status</th>
                  <th class="fw-bold">Action</th>
                  <th class="fw-bold">Attachment</th>
                  <th class="fw-bold">Messages</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($tickets)): ?>
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>No tickets found matching your filters.</p>
                      </div>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($tickets as $ticket): ?>
                    <tr>
                      <td><?= esc($ticket['ticket_id']); ?></td>
                      <td class="text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip"
                        title="<?= esc($ticket['title']); ?>">
                        <?= esc($ticket['title']); ?>
                      </td>
                      <td class="text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip"
                        title="<?= esc($ticket['description']); ?>">
                        <?= esc($ticket['description']); ?>
                      </td>
                      <td>
                        <span
                          class="badge <?= $ticket['urgency'] === 'High' ? 'bg-danger' : ($ticket['urgency'] === 'Medium' ? 'bg-warning text-dark' : 'bg-success') ?>">
                          <?= esc($ticket['urgency']); ?>
                        </span>
                      </td>
                      <td>
                        <span
                          class="badge <?= $ticket['status'] === 'Open' ? 'bg-info' : ($ticket['status'] === 'In Progress' ? 'bg-warning text-dark' : 'bg-success') ?>">
                          <?= esc($ticket['status']); ?>
                        </span>
                      </td>
                      <td>
                        <form action="<?= base_url('admin/update-ticket') ?>" method="post"
                          id="statusForm<?= $ticket['id']; ?>">
                          <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']); ?>">
                          <select name="status" class="form-select form-select-sm" required
                            onchange="submitForm(<?= $ticket['id']; ?>)">
                            <option value="Open" <?= $ticket['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                            <option value="In Progress" <?= $ticket['status'] === 'In Progress' ? 'selected' : ''; ?>>In
                              Progress</option>
                            <option value="Resolved" <?= $ticket['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved
                            </option>
                          </select>
                        </form>
                      </td>
                      <td>
                        <?php $attachments = json_decode($ticket['attachments'], true); ?>
                        <?php if (!empty($attachments)): ?>
                          <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($attachments as $index => $attachment): ?>
                              <a href="<?= base_url($attachment) ?>" class="btn btn-sm btn-outline-primary dropdown-toggle"
                                data-fancybox="gallery-<?= $ticket['id'] ?>"
                                data-caption="Attachment #<?= $index + 1 ?> for Ticket <?= esc($ticket['ticket_id']); ?>">
                                <i class="fas fa-paperclip me-1"></i><?= $index + 1 ?>
                              </a>
                            <?php endforeach; ?>
                          </div>
                        <?php else: ?>
                          <span class="text-muted"><i class="fas fa-times me-1"></i>None</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <a href="<?= site_url('admin/messages/' . esc($ticket['ticket_id'])) ?>"
                          class="btn btn-sm btn-primary">
                          <i class="fas fa-comments me-1"></i>Messages
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <?php if (!empty($pager)): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
              <div class="text-muted">
                Showing <?= count($tickets) ?> of <?= $total_tickets ?? 0 ?> tickets
              </div>
              <?= $pager->links() ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Fancybox
    Fancybox.bind("[data-fancybox]", {
      Thumbs: {
        autoStart: true
      }
    });

    $(document).ready(function () {
      // Toastr notifications setup
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000
      };

      <?php if (session()->getFlashdata('success')): ?>
        toastr.success("<?= esc(session()->getFlashdata('success')); ?>");
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        toastr.error("<?= esc(session()->getFlashdata('error')); ?>");
      <?php endif; ?>

      <?php if (session()->getFlashdata('warning')): ?>
        toastr.warning("<?= esc(session()->getFlashdata('warning')); ?>");
      <?php endif; ?>

      <?php if (session()->getFlashdata('info')): ?>
        toastr.info("<?= esc(session()->getFlashdata('info')); ?>");
      <?php endif; ?>
    });

    // Reset filters button
    document.getElementById('resetFilters').addEventListener('click', function () {
      document.querySelectorAll('#advancedFilterForm input, #advancedFilterForm select').forEach(element => {
        if (element.type === 'text' || element.type === 'date') {
          element.value = '';
        } else if (element.tagName === 'SELECT') {
          element.value = element.querySelector('option').value;
        }
      });
    });

    // Live Filter 
    const ticketIdFilter = document.getElementById('modalFilterTicketId');
    const titleFilter = document.getElementById('modalFilterTitle');

    // Add input event listeners for live feedback
    [ticketIdFilter, titleFilter].forEach(element => {
      if (element) {
        element.addEventListener('input', function () {
          if (this.value.length >= 3) {
            this.classList.add('is-valid');
          } else {
            this.classList.remove('is-valid');
          }
        });
      }
    });
  });

  // Function to update ticket status via AJAX
  function updateTicketStatus(ticketId, status) {
    fetch('<?= base_url("admin/update-ticket-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
      },
      body: JSON.stringify({ ticket_id: ticketId, status: status })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          toastr.error(data.message || "Error updating ticket status");
        }
      })
      .catch(error => {
        toastr.error("An error occurred while updating the ticket");
        console.error("Error:", error);
      });
  }
  function submitForm(ticketId) {
    var form = $("#statusForm" + ticketId);
    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: form.serialize(),
      success: function (response) {
        toastr.success("Ticket status updated successfully!");
      },
      error: function () {
        toastr.error("Failed to update ticket status.");
      }
    });
  }
  document.getElementById("filterToggleBtn").addEventListener("click", function () {
    var filterDropdown = document.getElementById("filterDropdown");
    var bsCollapse = new bootstrap.Collapse(filterDropdown, {
      toggle: true
    });
  });

</script>
<!-- Bootstrap JS (Include before `</body>`) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?= $this->endSection() ?>