<?= $this->extend('layouts/admin_template') ?>

<?= $this->section('content') ?>
  <style>
    /* Centering the card */
    .profile-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8f9fa;
    }

    /* Compact profile card */
    .profile-card {
      width: 350px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Profile Picture */
    .profile-picture {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #fff;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Form Inputs */
    .form-control {
      border-radius: 8px;
    }

    /* Button */
    .btn-primary {
      border-radius: 8px;
    }
  </style>
  <div class="container profile-container">
    <div class="card profile-card shadow-lg">
      <div class="card-body text-center">
        <h3 class="mb-3">Profile</h3>

        <!-- Profile Picture -->
        <img src="<?= base_url('uploads/profile_pictures/1740922010_74d7f7388e03b1566e5e.jpg' . session('profile_picture')); ?>" 
     alt="Profile Picture" class="profile-picture">

        <!-- Update Profile Form -->
        <form action="<?= base_url('admin/update-profile') ?>" method="post" enctype="multipart/form-data" class="mt-3">

          <div class="mb-3">
            <label class="form-label">Update Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= session('admin_name'); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= session('admin_email'); ?>" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      <?php if (session()->getFlashdata('profile_updated')): ?>
        toastr.success("<?= session('profile_updated'); ?>");
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        toastr.error("<?= session('error'); ?>");
      <?php endif; ?>
    });
  </script>

<?= $this->endSection() ?>
