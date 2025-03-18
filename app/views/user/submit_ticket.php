<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="text-center mb-4 fw-bold text-primary">
                        <i class="bi bi-ticket-perforated-fill me-2"></i>Submit a Support Ticket
                    </h2>

                    <form action="<?= base_url('user/submit_ticket') ?>" method="post" enctype="multipart/form-data" id="ticketForm">
                        <!-- Progress Indicator -->
                        <div class="mb-4">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" id="formProgress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-primary">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control form-control-lg border-start-0 ps-0" id="email" name="email"
                                    required placeholder="yourname@example.com">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label fw-medium">Ticket Subject</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-primary">
                                    <i class="bi bi-type"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg border-start-0 ps-0" id="title" name="title"
                                    required placeholder="Brief summary of your issue">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="category" class="form-label fw-medium">Category</label>
                                <div class="input-group input-group-lg dropdown">
                                    <span class="input-group-text bg-light border-end-0 text-primary">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <select class="form-select form-select-lg border-start-0 ps-0" id="category" name="category" required>
                                        <option value="" selected disabled>Select category</option>
                                        <option value="Technical Support">Technical Support</option>
                                        <option value="Billing">Billing & Payments</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Account Issues">Account Management</option>
                                        <option value="Feature Request">Feature Request</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-medium d-block">Priority Level</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="urgency" id="low" value="Low" required>
                                    <label class="btn btn-outline-success py-3 w-100" for="low">
                                        <i class="bi bi-thermometer-low me-2"></i>Low
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="urgency" id="medium" value="Medium">
                                    <label class="btn btn-outline-warning py-3 w-100" for="medium">
                                        <i class="bi bi-thermometer-half me-2"></i>Medium
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="urgency" id="high" value="High">
                                    <label class="btn btn-outline-danger py-3 w-100" for="high">
                                        <i class="bi bi-thermometer-high me-2"></i>High
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">Description</label>
                            <div class="input-group">
                                <textarea class="form-control form-control-lg p-3" id="description" name="description"
                                    rows="5" required placeholder="Please provide detailed information about your issue..."></textarea>
                            </div>
                            <div class="form-text text-end"><span id="charCount">0</span>/2000 characters</div>
                        </div>

                        <!-- Modern File Upload with Preview -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Attachments</label>
                            <div id="dropzone" class="border-2 border-dashed border-primary-subtle rounded-4 p-5 text-center cursor-pointer bg-light bg-opacity-50 transition-all hover:bg-light">
                                <i class="bi bi-cloud-arrow-up-fill fs-1 text-primary mb-2 d-block"></i>
                                <h5 class="text-dark">Drag files here or click to browse</h5>
                                <p class="text-muted mb-0">Support for images and videos (max 10MB each)</p>
                                <input type="file" id="fileInput" name="attachments[]" multiple hidden
                                    accept="image/*, video/*">
                            </div>
                            <div id="filePreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-primary btn-lg py-3 rounded-4 d-flex align-items-center justify-content-center">
                                <i class="bi bi-send-fill me-2"></i>
                                <span>Submit Ticket</span>
                            </button>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="mb-2">Already submitted a ticket?</p>
                            <a href="<?= base_url('user/tickets') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-clock-history me-2"></i>Check Ticket Status
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
.border-dashed {
    border-style: dashed !important;
}

.cursor-pointer {
    cursor: pointer;
}

.transition-all {
    transition: all 0.3s ease;
}

.hover\:bg-light:hover {
    background-color: #f8f9fa !important;
}

.file-preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    width: 120px;
    height: 120px;
}

.file-preview-item img, 
.file-preview-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-preview-item .remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
}

.file-preview-item .remove-btn:hover {
    background: rgba(220,53,69,0.8);
}

.file-preview-item .file-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 4px;
    font-size: 12px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.btn-check:checked + .btn-outline-success,
.btn-check:active + .btn-outline-success {
    background-color: var(--bs-success) !important;
    color: #fff !important;
}

.btn-check:checked + .btn-outline-warning,
.btn-check:active + .btn-outline-warning {
    background-color: var(--bs-warning) !important;
    color: #fff !important;
}

.btn-check:checked + .btn-outline-danger,
.btn-check:active + .btn-outline-danger {
    background-color: var(--bs-danger) !important;
    color: #fff !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // File upload functionality
    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const filePreview = document.getElementById("filePreview");
    let uploadedFiles = [];

    // Character counter for description
    const description = document.getElementById("description");
    const charCount = document.getElementById("charCount");

    description.addEventListener("input", function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 2000) {
            charCount.classList.add("text-danger");
            description.classList.add("border-danger");
        } else {
            charCount.classList.remove("text-danger");
            description.classList.remove("border-danger");
        }
    });

    // Progress bar update
    const formInputs = document.querySelectorAll('#ticketForm input, #ticketForm textarea, #ticketForm select');
    const progressBar = document.getElementById('formProgress');
    
    function updateProgress() {
        let filledCount = 0;
        formInputs.forEach(input => {
            if (input.type === 'radio') {
                if (input.checked) filledCount++;
            } else if (input.type === 'file') {
                if (uploadedFiles.length > 0) filledCount++;
            } else if (input.value) {
                filledCount++;
            }
        });
        
        // Count radio groups as one
        const progress = (filledCount / (formInputs.length - 2)) * 100; // -2 for radio buttons (count as one)
        progressBar.style.width = `${Math.min(progress, 100)}%`;
    }
    
    formInputs.forEach(input => {
        input.addEventListener('change', updateProgress);
        input.addEventListener('input', updateProgress);
    });

    // Drag and drop functionality
    dropzone.addEventListener("click", function () {
        fileInput.click();
    });

    dropzone.addEventListener("dragover", function (event) {
        event.preventDefault();
        dropzone.classList.add("bg-light");
    });

    dropzone.addEventListener("dragleave", function () {
        dropzone.classList.remove("bg-light");
    });

    dropzone.addEventListener("drop", function (event) {
        event.preventDefault();
        dropzone.classList.remove("bg-light");
        
        if (event.dataTransfer.files.length > 0) {
            handleFiles(event.dataTransfer.files);
        }
    });

    fileInput.addEventListener("change", function (event) {
        if (event.target.files.length > 0) {
            handleFiles(event.target.files);
        }
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            // Check file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert(`File ${file.name} exceeds the 10MB limit.`);
                return;
            }
            
            // Check if file is already in the list
            if (uploadedFiles.some(f => f.name === file.name && f.size === file.size)) {
                return;
            }
            
            uploadedFiles.push(file);
            
            const preview = document.createElement("div");
            preview.classList.add("file-preview-item");
            
            const fileId = `file-${Date.now()}-${Math.random().toString(36).substring(2, 9)}`;
            preview.dataset.fileId = fileId;
            
            const reader = new FileReader();
            reader.onload = function (e) {
                if (file.type.startsWith("image/")) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <div class="file-name">${file.name}</div>
                        <div class="remove-btn" data-file-id="${fileId}"><i class="bi bi-x"></i></div>
                    `;
                } else if (file.type.startsWith("video/")) {
                    preview.innerHTML = `
                        <div style="background-color: #000; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-play-circle-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                        <div class="file-name">${file.name}</div>
                        <div class="remove-btn" data-file-id="${fileId}"><i class="bi bi-x"></i></div>
                    `;
                }
                
                filePreview.appendChild(preview);
                
                // Add event listener to remove button
                preview.querySelector(".remove-btn").addEventListener("click", function() {
                    const fileId = this.dataset.fileId;
                    removeFile(fileId);
                });
            };
            
            reader.readAsDataURL(file);
        });
        
        // Create a new FileList from the uploadedFiles array
        updateFileInput();
        updateProgress();
    }
    
    function removeFile(fileId) {
        const preview = document.querySelector(`.file-preview-item[data-file-id="${fileId}"]`);
        const fileName = preview.querySelector(".file-name").textContent;
        
        // Remove from uploadedFiles array
        uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
        
        // Remove preview element
        preview.remove();
        
        // Update file input
        updateFileInput();
        updateProgress();
    }
    
    function updateFileInput() {
        // Create a new DataTransfer object
        const dataTransfer = new DataTransfer();
        
        // Add files to DataTransfer object
        uploadedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        
        // Set files property of file input
        fileInput.files = dataTransfer.files;
    }
    
    // Form validation
    const form = document.getElementById("ticketForm");
    form.addEventListener("submit", function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Find first invalid input and focus it
            const invalidInput = form.querySelector(":invalid");
            if (invalidInput) {
                invalidInput.focus();
                
                // Scroll to invalid input with smooth behavior
                invalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }
        
        form.classList.add("was-validated");
    });
});
</script>
<?= $this->endSection(); ?>