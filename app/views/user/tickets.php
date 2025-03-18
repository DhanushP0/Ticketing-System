<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h2 class="fw-bold mb-2">
                <i class="bi bi-ticket-perforated me-2"></i>Your Tickets
            </h2>
            <p class="text-muted">View and manage all your support requests</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= session()->getFlashdata('success'); ?></div>
        </div>
    <?php endif; ?>

    <?php if (empty($tickets)): ?>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h4 class="fw-semibold mb-3">No tickets submitted yet</h4>
                <p class="text-muted mb-4">Get started by creating your first support ticket</p>
                <a href="<?= base_url('user/submit_ticket') ?>" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-plus-circle me-2"></i>Submit a Ticket
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($tickets as $ticket): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold text-truncate" title="<?= esc($ticket['title']); ?>">
                                    <?= esc($ticket['title']); ?>
                                </h5>

                                <!-- Status Badge -->
                                <span class="badge rounded-pill <?= getStatusClass($ticket['status']); ?>">
                                    <?= getStatusIcon($ticket['status']); ?>         <?= esc($ticket['status']); ?>
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted mb-0 description-text">
                                    <?= (strlen($ticket['description']) > 100) ? substr(esc($ticket['description']), 0, 100) . '...' : esc($ticket['description']); ?>
                                </p>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <!-- Category Badge -->
                                <span class="badge rounded-pill bg-light text-dark border">
                                    <i class="bi bi-folder me-1"></i> <?= esc($ticket['category']); ?>
                                </span>

                                <!-- Urgency Badge -->
                                <span class="badge rounded-pill <?= getUrgencyClass($ticket['urgency']); ?>">
                                    <?= getUrgencyIcon($ticket['urgency']); ?>         <?= esc($ticket['urgency']); ?>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center text-muted mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-1"></i>
                                    <small><?= date('d M Y, h:i A', strtotime($ticket['created_at'])) ?></small>
                                </div>

                                <a href="<?= base_url('user/ticket/' . $ticket['id']) ?>"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-arrow-right"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Helper functions for badge styling
function getStatusClass($status)
{
    switch ($status) {
        case 'Open':
            return 'bg-info text-dark';
        case 'In Progress':
            return 'bg-warning text-dark';
        case 'Resolved':
            return 'bg-success';
        case 'Closed':
            return 'bg-secondary';
        default:
            return 'bg-secondary';
    }
}

function getStatusIcon($status)
{
    switch ($status) {
        case 'Open':
            return '<i class="bi bi-record-circle"></i>';
        case 'In Progress':
            return '<i class="bi bi-tools"></i>';
        case 'Resolved':
            return '<i class="bi bi-check-circle"></i>';
        case 'Closed':
            return '<i class="bi bi-archive"></i>';
        default:
            return '<i class="bi bi-question-circle"></i>';
    }
}

function getUrgencyClass($urgency)
{
    switch ($urgency) {
        case 'High':
            return 'bg-danger';
        case 'Medium':
            return 'bg-warning text-dark';
        case 'Low':
            return 'bg-success';
        default:
            return 'bg-secondary';
    }
}

function getUrgencyIcon($urgency)
{
    switch ($urgency) {
        case 'High':
            return '<i class="bi bi-exclamation-triangle"></i>';
        case 'Medium':
            return '<i class="bi bi-exclamation"></i>';
        case 'Low':
            return '<i class="bi bi-info-circle"></i>';
        default:
            return '<i class="bi bi-question-circle"></i>';
    }
}
?>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .description-text {
        max-height: 4.5rem;
        overflow: hidden;
    }

    .rounded-pill {
        border-radius: var(--bs-border-radius-pill) !important;
        padding: 10px;
    }
</style>

<?= $this->endSection(); ?>