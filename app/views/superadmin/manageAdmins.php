<?= $this->extend('layouts/superadmin_template') ?>

<?= $this->section('content') ?>

<main class="main-content">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
          <!-- Header with stats summary -->
          <div class="card-header bg-gradient-primary p-4">
            <div class="row align-items-center">
              <div class="col-lg-6"style="width: 100%;">
                <h3 class="text-black mb-0 fw-italic">Admin Management</h3>
              </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-lg-end mt-3 mt-lg-0 mb-3">
              <button class="btn btn-light btn-lg rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#addAdminModal">
                <i class="fas fa-user-plus me-2"></i>New Admin
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
                  <input type="text" class="form-control border-0 py-2" id="searchAdmin"
                    placeholder="Search administrators...">
                </div>
              </div>
              <div class="col-lg-6 d-flex justify-content-lg-end">
                <div class="btn-group rounded-pill shadow-sm">
                  <button class="btn btn-light px-3 py-2" id="currentFilter">
                    <i class="fas fa-filter me-2"></i>All Admins
                  </button>
                  <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow" style="max-height: 300px; overflow: scroll;">
                    <li>
                      <h6 class="dropdown-header">By Category</h6>
                    </li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="all">Show All</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="Technical Support">Technical
                        Support</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="Billing">Billing</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="General Inquiry">General
                        Inquiry</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="Account Issues">Account Issues</a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <h6 class="dropdown-header">By Role</h6>
                    </li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="admin">Admins</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="superadmin">Superadmins</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Admin Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover table-striped" id="adminsTable">
                <thead>
                  <tr>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Admin</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Category</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7">Role</th>
                    <th class="fw-bold text-uppercase text-xs text-secondary opacity-7 text-end">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($admins as $admin): ?>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center px-2 py-3">
                          <div
                            class="avatar avatar-md bg-gradient-primary rounded-circle me-3 text-white d-flex align-items-center justify-content-center">
                            <?= strtoupper(substr($admin['name'], 0, 0)) ?>
                          </div>
                          <div class="d-flex flex-column">
                            <h6 class="mb-0 text-sm"><?= esc($admin['name']) ?></h6>
                            <p class="text-secondary text-xs mb-0"><?= esc($admin['email']) ?></p>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <?php
                        // Assigning badge colors based on category
                        $categoryBadgeClass = match ($admin['category']) {
                          'Technical Support' => 'bg-light text-dark',
                          'Billing' => 'bg-light text-dark',
                          'General Inquiry' => 'bg-light text-dark',
                          'Account Issues' => 'bg-light text-dark',
                          default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $categoryBadgeClass ?> rounded-pill px-3 py-2">
                          <?= esc($admin['category'] ?: 'N/A') ?>
                        </span>
                      </td>
                      <td class="align-middle">
                        <span
                          class="badge <?= $admin['role'] === 'superadmin' ? 'bg-dark' : 'bg-primary' ?> rounded-pill px-3 py-2">
                          <?= esc($admin['role']) ?>
                        </span>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex justify-content-end gap-2">
                          <button class="btn btn-sm btn-outline-primary rounded-pill edit-admin"
                            data-id="<?= $admin['id'] ?>" data-name="<?= esc($admin['name']) ?>"
                            data-email="<?= esc($admin['email']) ?>" data-category="<?= esc($admin['category']) ?>"
                            data-role="<?= esc($admin['role']) ?>" data-bs-toggle="modal"
                            data-bs-target="#editAdminModal">
                            <i class="fas fa-edit me-1"></i> Edit
                          </button>

                          <button type="button" class="btn btn-sm btn-outline-danger rounded-pill delete-admin-btn"
                            data-id="<?= $admin['id'] ?>" data-name="<?= esc($admin['name']) ?>">
                            <i class="fas fa-trash-alt me-1"></i> Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div id="noResults" class="text-center py-5 d-none">
              <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted"></i>
              </div>
              <h4 class="mb-3">No matching tickets found</h4>
              <p class="text-muted fs-5 mb-4">Try adjusting your search criteria or clearing filters</p>
              <button id="clearAllFiltersBtn" class="btn btn-outline-primary btn-lg px-4">Clear All Filters</button>
            </div>

            <?php if (empty($admins)): ?>
              <div class="text-center py-5">
                <div class="mb-4">
                  <div class="avatar avatar-xl avatar-rounded bg-light p-4 mx-auto">
                    <i class="fas fa-users fa-4x text-secondary opacity-6"></i>
                  </div>
                </div>
                <h4 class="text-secondary">No administrators found</h4>
                <p class="text-muted">Get started by adding a new administrator</p>
                <button class="btn btn-primary btn-lg rounded-pill mt-3" data-bs-toggle="modal"
                  data-bs-target="#addAdminModal">
                  <i class="fas fa-user-plus me-2"></i>Add New Admin
                </button>
              </div>
            <?php endif; ?>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
              <?= $pager->links ?? '' ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header bg-gradient-primary">
        <h5 class="modal-title text-black">
          <i class="fas fa-user-plus me-2"></i>Add New Administrator
        </h5>
        <button type="button" class="btn-close btn-close-grey" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('superadmin/addAdmin'); ?>" method="post" id="addAdminForm">
        <?= csrf_field() ?>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" class="form-control form-control-lg rounded-3" required maxlength="100">
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control form-control-lg rounded-3" required>
            <div class="form-text">This email will be used for login and notifications</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Category</label>
              <select class="form-select form-select-lg rounded-3" name="category" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Technical Support">Technical Support</option>
                <option value="Billing">Billing</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Account Issues">Account Issues</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Access Role</label>
              <select name="role" class="form-select form-select-lg rounded-3">
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
              </select>
            </div>
          </div>
          <div class="alert alert-warning rounded-3 d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle fs-5 me-3"></i>
            <div>Superadmins have full system access including user management</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group input-group-lg">
              <input type="password" name="password" id="password" class="form-control rounded-start-3" required
                minlength="8">
              <button class="btn btn-outline-secondary rounded-end-3 toggle-password" type="button"
                data-target="password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="form-text">Password must be at least 8 characters</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Confirm Password</label>
            <div class="input-group input-group-lg">
              <input type="password" name="password_confirm" id="password_confirm" class="form-control rounded-start-3"
                required minlength="8">
              <button class="btn btn-outline-secondary rounded-end-3 toggle-password" type="button"
                data-target="password_confirm">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4"
            data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
            <i class="fas fa-save me-2"></i>Create Admin
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header bg-gradient-primary">
        <h5 class="modal-title text-black">
          <i class="fas fa-user-edit me-2"></i>Edit Administrator
        </h5>
        <button type="button" class="btn-close btn-close-grey" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('superadmin/editAdmin'); ?>" method="post" id="editAdminForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="editAdminId">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" id="editAdminName" class="form-control form-control-lg rounded-3" required
              maxlength="100">
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" id="editAdminEmail" class="form-control form-control-lg rounded-3"
              required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Category</label>
              <select class="form-select form-select-lg rounded-3" name="category" id="editAdminCategory" required>
                <option value="" disabled>Select a category</option>
                <option value="Technical Support">Technical Support</option>
                <option value="Billing">Billing</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Account Issues">Account Issues</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Access Role</label>
              <select name="role" id="editAdminRole" class="form-select form-select-lg rounded-3">
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
              </select>
            </div>
          </div>
          <div class="alert alert-warning rounded-3 d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle fs-5 me-3"></i>
            <div>Superadmins have full system access including user management</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group input-group-lg">
              <input type="password" name="password" id="editPassword" class="form-control rounded-start-3"
                minlength="8">
              <button class="btn btn-outline-secondary rounded-end-3 toggle-password" type="button"
                data-target="editPassword">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="form-text">Leave blank to keep current password</div>
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4"
            data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="fas fa-save me-2"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white">
          <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <div class="avatar avatar-xl avatar-rounded bg-danger bg-opacity-10 p-3 mx-auto mb-3">
            <i class="fas fa-user-times fa-3x text-danger"></i>
          </div>
          <p class="mb-1">Are you sure you want to delete:</p>
          <h5 class="fw-bold" id="deleteAdminName"></h5>
        </div>
        <div class="alert alert-warning rounded-3 d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-triangle me-3"></i>
          <div>This action cannot be undone.</div>
        </div>
      </div>
      <div class="modal-footer bg-light p-3">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
          data-bs-dismiss="modal">Cancel</button>
        <form action="<?= base_url('superadmin/deleteAdmin') ?>" method="post" id="deleteAdminForm">
          <?= csrf_field() ?>
          <input type="hidden" name="id" id="deleteAdminId">
          <button type="submit" class="btn btn-danger rounded-pill px-4">
            <i class="fas fa-trash-alt me-2"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit admin button handlers
    document.querySelectorAll('.edit-admin').forEach(button => {
      button.addEventListener('click', function () {
        document.getElementById('editAdminId').value = this.dataset.id;
        document.getElementById('editAdminName').value = this.dataset.name;
        document.getElementById('editAdminEmail').value = this.dataset.email;
        document.getElementById('editAdminCategory').value = this.dataset.category;
        document.getElementById('editAdminRole').value = this.dataset.role;
      });
    });
    const noResults = document.getElementById('noResults');

    // Delete admin button handlers
    document.querySelectorAll('.delete-admin-btn').forEach(button => {
      button.addEventListener('click', function () {
        const adminId = this.dataset.id;
        const adminName = this.dataset.name;

        document.getElementById('deleteAdminId').value = adminId;
        document.getElementById('deleteAdminName').textContent = adminName;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
      });
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const passwordInput = document.getElementById(targetId);

        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          this.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
          passwordInput.type = 'password';
          this.innerHTML = '<i class="fas fa-eye"></i>';
        }
      });
    });

    // Search functionality
    const searchInput = document.getElementById('searchAdmin');
    if (searchInput) {
      searchInput.addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('adminsTable');

        table.querySelectorAll('tbody tr').forEach(row => {
          let found = false;
          row.querySelectorAll('td').forEach(cell => {
            if (cell.textContent.toLowerCase().indexOf(searchValue) > -1) {
              found = true;
            }
          });

          if (found) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }

    // Form validation
    const addAdminForm = document.getElementById('addAdminForm');
    if (addAdminForm) {
      addAdminForm.addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;

        if (password !== passwordConfirm) {
          e.preventDefault();
          alert('Passwords do not match.');
        }
      });
    }

    // Filter functionality
    document.querySelectorAll(".filter-option").forEach(item => {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        const filterValue = this.getAttribute("data-filter").toLowerCase();
        const filterText = this.textContent;
        document.getElementById('currentFilter').innerHTML = '<i class="fas fa-filter me-2"></i>' + filterText;

        document.querySelectorAll("#adminsTable tbody tr").forEach(row => {
          if (filterValue === "all") {
            row.style.display = "";
            return;
          }

          const categoryCell = row.querySelector("td:nth-child(2)");
          const roleCell = row.querySelector("td:nth-child(3)");

          const category = categoryCell.textContent.trim().toLowerCase();
          const role = roleCell.textContent.trim().toLowerCase();

          if (category.includes(filterValue) || role.includes(filterValue)) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      });
    });
  });
</script>
<?= $this->endSection() ?>