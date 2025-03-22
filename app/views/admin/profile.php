<?= $this->extend('layouts/admin_template') ?>
<?= $this->section('content') ?>

<style>
  .profile-container {
    padding: 3rem 0;
    background-color: #f8f9fa;
    min-height: 100vh;
  }

  .profile-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    max-width: 450px;
    margin: 0 auto;
  }

  .profile-header {
    background-color: #4361ee;
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    text-align: center;
  }

  .profile-picture-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
  }

  .profile-picture {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .profile-picture-edit {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #fff;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;
  }

  .profile-title {
    color: #fff;
    font-weight: 600;
    margin-top: 1rem;
  }

  .profile-form {
    padding: 0.5rem 2rem 2rem;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
    margin-bottom: 0.5rem;
  }

  .form-control {
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.1);
  }

  .btn-update {
    background-color: #4361ee;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 100%;
  }

  .btn-update:hover {
    background-color: #3a56d4;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
  }

  .file-input-hidden {
    display: none;
  }
</style>

<div class="profile-container">
  <div class="container">
    <div class="profile-card">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-picture-container">
          <img src="<?= base_url('uploads/profile_pictures/' . (session('profile_picture') ?: 'default.jpg')); ?>" 
               alt="Profile Picture" class="profile-picture">
          <label for="profile-pic-upload" class="profile-picture-edit">
            <i class="bi bi-camera-fill"></i>
          </label>
        </div>
        <h4 class="profile-title">My Profile</h4>
      </div>

      <!-- Profile Form -->
      <div class="profile-form">
        <form action="<?= base_url('admin/update-profile') ?>" method="post" enctype="multipart/form-data">
          <input type="file" id="profile-pic-upload" name="profile_picture" class="file-input-hidden" 
                 onchange="document.getElementById('file-chosen').textContent = this.files[0].name">
          
          <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= session('admin_name'); ?>" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= session('admin_email'); ?>" required>
          </div>
          
          <button type="submit" class="btn btn-update">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    <?php if (session()->getFlashdata('profile_updated')): ?>
      toastr.success("<?= session()->getFlashdata('profile_updated'); ?>");
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      toastr.error("<?= session()->getFlashdata('error'); ?>");
    <?php endif; ?>
  });
</script>

<?= $this->endSection() ?>