<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="d-flex justify-content-center align-items-center vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <!-- Success banner -->
                        <div class="bg-success bg-gradient p-4 text-white text-center">
                            <div class="success-icon mb-3">
                                <i class="bi bi-check-circle-fill display-1"></i>
                            </div>
                            <h2 class="fw-bold">Ticket Submitted Successfully!</h2>
                            <p class="lead mb-0">Your request has been received</p>
                        </div>
                        
                        <div class="p-4 p-lg-5">
                            <!-- Ticket ID display -->
                            <div class="ticket-id-container bg-light rounded-4 p-4 mb-4">
                                <p class="text-muted mb-1">Your Ticket ID</p>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <h3 class="ticket-id fw-bold text-primary mb-0"><?= esc($ticketId) ?></h3>
                                    <button class="btn btn-sm btn-outline-secondary copy-btn" 
                                           data-clipboard-text="<?= esc($ticketId) ?>" 
                                           title="Copy to clipboard">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <p class="mb-0">We've also sent a confirmation email with your ticket details.</p>
                                <p class="text-muted small">Please save your Ticket ID for future reference.</p>
                            </div>
                            
                            <!-- Next steps -->
                            <div class="d-grid gap-3">
                                <a href="<?= base_url('user/login') ?>" class="btn btn-primary py-3 rounded-3">
                                    <i class="bi bi-list-check me-2"></i>View Tickets
                                </a>
                                <!-- <a href="<?= base_url('user/home') ?>" class="btn btn-outline-secondary py-2 rounded-3">
                                    <i class="bi bi-house me-2"></i>
                                </a> -->
                            </div>
                            
                            <!-- Support information -->
                            <div class="mt-4 pt-3 border-top text-center">
                                <p class="small text-muted mb-0">Need immediate assistance?</p>
                                <p class="fw-medium">
                                    <i class="bi bi-telephone me-1"></i> Call us: 100
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: scale-in 0.6s ease-out;
}

@keyframes scale-in {
    0% { transform: scale(0); opacity: 0; }
    70% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.ticket-id-container {
    transition: all 0.3s ease;
}

.ticket-id-container:hover {
    box-shadow: 0 0 15px rgba(13, 110, 253, 0.2);
}

.copy-btn {
    transition: all 0.2s;
}

.copy-btn:hover {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}

.copy-btn.copied {
    background-color: var(--bs-success);
    color: white;
    border-color: var(--bs-success);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    const copyBtn = document.querySelector('.copy-btn');
    
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const ticketId = this.dataset.clipboardText;
            
            // Copy to clipboard
            navigator.clipboard.writeText(ticketId).then(() => {
                // Visual feedback
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
                this.classList.add('copied');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-clipboard"></i>';
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    }
    
    // Add animation to indicate success
    setTimeout(() => {
        const ticketContainer = document.querySelector('.ticket-id-container');
        if (ticketContainer) {
            ticketContainer.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
            setTimeout(() => {
                ticketContainer.style.backgroundColor = '';
            }, 500);
        }
    }, 1000);
});
</script>
<?= $this->endSection(); ?>
