<?= $this->extend('layouts/superadmin_template') ?>

<?= $this->section('content') ?>

<?php if (isset($session) && $session->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= $session->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body">
            <h2 class="text-primary fw-bold text-center mb-4">Admin Management</h2>
            
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAdminModal">Add New Admin</button>
            
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Category</th>
                    <th>Role</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($admins as $admin): ?>
                    <tr>
                      <td><?= $admin['id'] ?></td>
                      <td><?= esc($admin['name']) ?></td>
                      <td><?= esc($admin['email']) ?></td>
                      <td><?= esc($admin['category'] ?: 'N/A') ?></td>
                      <td><?= esc($admin['role']) ?></td>
                      <td>
                        <button class="btn btn-primary btn-sm edit-admin" data-id="<?= $admin['id'] ?>"
                          data-name="<?= esc($admin['name']) ?>" data-email="<?= esc($admin['email']) ?>"
                          data-category="<?= esc($admin['category']) ?>" data-role="<?= esc($admin['role']) ?>"
                          data-bs-toggle="modal" data-bs-target="#editAdminModal">
                          Edit
                        </button>

                        <?php if ($session->get('role') === 'superadmin'): ?>
                          <a href="<?= base_url('admin/delete/' . $admin['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Add Admin Modal -->
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

<script>
  document.querySelectorAll('.edit-admin').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('editAdminId').value = this.dataset.id;
      document.getElementById('editAdminName').value = this.dataset.name;
      document.getElementById('editAdminEmail').value = this.dataset.email;
      document.getElementById('editAdminCategory').value = this.dataset.category;
      document.getElementById('editAdminRole').value = this.dataset.role;
    });
  });
</script>
<?= $this->endSection() ?>
