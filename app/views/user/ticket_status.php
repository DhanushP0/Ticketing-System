<?php
if (!session()->get('isLoggedIn') || session()->get('role') !== 'user') {
    header('Location: ' . site_url('user/login'));
    exit();
}

// Ensure $ticket is set before using it
if (!isset($ticket)) {
    echo "<p>Ticket details not found.</p>";
    exit();
}
?>

<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient-light p-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 fw-bold">
                            <i class="bi bi-ticket-perforated-fill me-2"></i>Ticket Details
                        </h3>
                        <span class="badge bg-primary rounded-pill fs-6"><?= esc($ticket['ticket_id']) ?></span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Ticket Status Summary -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-<?= $ticket['status'] === 'Resolved' ? 'success' : ($ticket['status'] === 'In Progress' ? 'primary' : 'secondary') ?> p-2 fs-6">
                                        <i class="bi bi-<?= $ticket['status'] === 'Resolved' ? 'check-circle' : ($ticket['status'] === 'In Progress' ? 'arrow-repeat' : 'hourglass-split') ?>"></i>
                                        <?= esc($ticket['status']) ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-<?= $ticket['urgency'] === 'High' ? 'danger' : ($ticket['urgency'] === 'Medium' ? 'warning' : 'success') ?> p-2 fs-6">
                                        <i class="bi bi-exclamation-<?= $ticket['urgency'] === 'High' ? 'triangle' : ($ticket['urgency'] === 'Medium' ? 'circle' : 'diamond') ?>"></i>
                                        <?= esc($ticket['urgency']) ?> Priority
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Details -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <h4 class="fw-bold"><?= esc($ticket['title']) ?></h4>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-envelope me-1"></i><?= esc($ticket['email']) ?>
                                </p>
                            </div>
                            
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Description</h6>
                                    <p class="card-text"><?= nl2br(esc($ticket['description'])) ?></p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                                        <?php if (!empty($ticket['assigned_admin'])): ?>
                                            <?= strtoupper(substr($ticket['assigned_admin'], 0, 1)) ?>
                                        <?php else: ?>
                                            <i class="bi bi-person"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0"><strong>Assigned to:</strong>
                                        <?= !empty($ticket['assigned_admin']) ? esc($ticket['assigned_admin']) : '<span class="text-muted">Not Assigned Yet</span>'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attachments Section -->
                        <?php if (!empty($ticket['attachments'])): ?>
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-paperclip me-1"></i>Attachments
                                </h6>
                                <div class="attachment-gallery">
                                    <div class="row g-3">
                                        <?php
                                        // Decode JSON or split CSV
                                        $attachments = json_decode($ticket['attachments'], true);
                                        if (!is_array($attachments)) {
                                            $attachments = explode(',', $ticket['attachments']);
                                        }

                                        foreach ($attachments as $index => $attachment):
                                            $attachment = trim($attachment, '"');
                                            $file_ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                            $file_url = base_url(trim($attachment));
                                            $file_name = basename($attachment);
                                        ?>
                                            <div class="col-lg-3 col-md-4 col-6">
                                                <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                                    <a href="<?= esc($file_url) ?>" 
                                                       data-fancybox="gallery" 
                                                       data-caption="<?= esc($file_name) ?>" 
                                                       class="attachment-thumbnail">
                                                        <div class="card h-100 border-0 shadow-sm hover-zoom">
                                                            <img src="<?= esc($file_url) ?>" 
                                                                 class="card-img-top rounded" 
                                                                 alt="<?= esc($file_name) ?>" 
                                                                 style="height: 120px; object-fit: cover;">
                                                            <div class="card-img-overlay d-flex align-items-end p-2">
                                                                <div class="badge bg-dark bg-opacity-75 text-white w-100 text-start text-truncate">
                                                                    <i class="bi bi-image me-1"></i><?= esc($file_name) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php elseif (in_array($file_ext, ['mp4', 'avi', 'mov', 'webm'])): ?>
                                                    <a href="<?= esc($file_url) ?>" 
                                                       data-fancybox="videos" 
                                                       data-caption="<?= esc($file_name) ?>" 
                                                       class="attachment-thumbnail">
                                                        <div class="card h-100 border-0 shadow-sm hover-zoom">
                                                            <div class="card-img-top rounded bg-dark d-flex align-items-center justify-content-center" style="height: 120px;">
                                                                <i class="bi bi-play-circle text-white" style="font-size: 2rem;"></i>
                                                            </div>
                                                            <div class="card-img-overlay d-flex align-items-end p-2">
                                                                <div class="badge bg-dark bg-opacity-75 text-white w-100 text-start text-truncate">
                                                                    <i class="bi bi-film me-1"></i><?= esc($file_name) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= esc($file_url) ?>" target="_blank" class="attachment-thumbnail">
                                                        <div class="card h-100 border-0 shadow-sm hover-zoom">
                                                            <div class="card-img-top rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                                                <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                                                            </div>
                                                            <div class="card-img-overlay d-flex align-items-end p-2">
                                                                <div class="badge bg-dark bg-opacity-75 text-white w-100 text-start text-truncate">
                                                                    <i class="bi bi-file-earmark me-1"></i><?= esc($file_name) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Message Button -->
                        <div class="col-12">
                            <a href="<?= site_url('user/message/' . $ticket['ticket_id'] . '/' . $ticket['assigned_admin_id']) ?>" 
                               class="btn btn-primary w-100 py-3 rounded-3 mt-2">
                                <i class="bi bi-chat-dots-fill me-2"></i>Message Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom styles -->
<style>
.bg-gradient-light {
    background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
}

.hover-zoom {
    transition: transform 0.2s, box-shadow 0.2s;
}

.hover-zoom:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.attachment-thumbnail {
    text-decoration: none;
    color: inherit;
    display: block;
}

.avatar-placeholder {
    font-weight: bold;
}
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Fancybox CSS -->
<!-- Try using version 4.0 instead of 5.0 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Fancybox.bind("[data-fancybox]", {
        // Add a proper toolbar with close button
        Toolbar: {
            display: [
                { id: "prev", position: "center" },
                { id: "counter", position: "center" },
                { id: "next", position: "center" },
                { id: "slideshow", position: "center" },
                { id: "fullscreen", position: "right" },
                { id: "download", position: "right" },
                { id: "thumbs", position: "right" },
                { id: "close", position: "right" }
            ]
        },
        // Make sure buttons are visible
        showClass: "fancybox-zoomIn",
        hideClass: "fancybox-zoomOut",
        // Add key support
        keyboard: {
            Escape: "close",
            Delete: "close",
            Backspace: "close",
            PageUp: "next",
            PageDown: "prev",
            ArrowUp: "next",
            ArrowDown: "prev",
            ArrowRight: "next",
            ArrowLeft: "prev"
        },
        // Add click-to-close
        Image: {
            click: "close",
            doubleClick: "zoom"
        }
    });
});
    
    // Initialize Fancybox for videos
    Fancybox.bind("[data-fancybox='videos']", {
        compact: false,
        idle: false,
        animated: true,
        Toolbar: {
            display: [
                { id: "counter", position: "center" },
                { id: "close", position: "right" }
            ]
        }
    });
});
</script>

<?= $this->endSection(); ?>