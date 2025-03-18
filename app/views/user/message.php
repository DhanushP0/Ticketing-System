<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="card shadow border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="bi bi-chat-dots me-2"></i>
                Chat with <?= esc($admin['name'] ?? 'Admin') ?>
            </h5>
            <span class="badge bg-light text-dark">
                Ticket #<?= esc($ticket['ticket_id']) ?>
            </span>
        </div>

        <div class="card-body p-0">
            <div id="chat-container" class="p-3" style="height: 450px; overflow-y: auto; background-color: #f8f9fa;">
                <div class="d-flex justify-content-center align-items-center h-100" id="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading messages...</span>
                    </div>
                </div>
                <div id="chat-messages" class="d-flex flex-column gap-3"></div>
            </div>
        </div>

        <div class="card-footer bg-light border-top-0">
            <form id="chat-form" class="needs-validation" enctype="multipart/form-data" novalidate>
                <input type="hidden" id="ticket_id" value="<?= esc($ticket['ticket_id']) ?>">
                <input type="hidden" id="admin_id" value="<?= esc($admin['id'] ?? '') ?>">

                <div class="input-group">
                    <textarea id="message" class="form-control" placeholder="Type your message here..." rows="2" required></textarea>
                    <div class="input-group-text bg-white border-start-0 p-0">
                        <label for="attachment" class="btn btn-outline-secondary border-0 m-0" title="Attach file">
                            <i class="bi bi-paperclip"></i>
                            <span class="visually-hidden">Attach file</span>
                        </label>
                        <input type="file" id="attachment" class="d-none" accept="image/*,video/*,.pdf,.doc,.docx">
                    </div>
                    <button type="submit" class="btn btn-primary" id="send-btn" disabled>
                        <i class="bi bi-send-fill me-1"></i> Send
                    </button>
                </div>
                <div id="attachment-preview" class="mt-2 d-none">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark me-2" id="attachment-name"></span>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="remove-attachment">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Cache DOM elements
    const elements = {
        chatContainer: document.getElementById('chat-container'),
        chatMessages: document.getElementById('chat-messages'),
        chatForm: document.getElementById('chat-form'),
        messageInput: document.getElementById('message'),
        sendButton: document.getElementById('send-btn'),
        attachmentInput: document.getElementById('attachment'),
        attachmentPreview: document.getElementById('attachment-preview'),
        attachmentName: document.getElementById('attachment-name'),
        removeAttachmentBtn: document.getElementById('remove-attachment'),
        loadingSpinner: document.getElementById('loading-spinner')
    };
    
    const config = {
        ticketId: document.getElementById('ticket_id').value,
        adminId: document.getElementById('admin_id').value,
        baseUrl: '<?= base_url() ?>',
        apiUrls: {
            getMessages: '<?= site_url('user/getMessages') ?>',
            sendMessage: '<?= site_url('user/sendMessage') ?>'
        },
        polling: {
            current: 3000,     // Start with 3 seconds
            min: 2000,         // Min 2 seconds
            max: 15000,        // Max 15 seconds
            inactivityTimer: 0
        }
    };
    
    // State management - improved to track loaded message IDs
    const state = {
        lastMessageTimestamp: 0,
        isPolling: false,
        isFirstLoad: true,
        messageQueue: [],
        loadedMessageIds: new Set() // Track loaded message IDs to prevent duplicates
    };

    // Initialize app
    init();
    
    function init() {
        attachEventListeners();
        loadMessages();
        startPolling();
        setupVisibilityListener();
    }
    
    function attachEventListeners() {
        // Enable send button only when message is not empty
        elements.messageInput.addEventListener('input', toggleSendButton);

        // Show attachment preview when file is selected
        elements.attachmentInput.addEventListener('change', handleAttachmentChange);

        // Remove attachment when cancel button is clicked
        elements.removeAttachmentBtn.addEventListener('click', removeAttachment);
        
        // Form submission
        elements.chatForm.addEventListener('submit', sendMessage);
        
        // Enable textarea submission with Enter (but Shift+Enter for new line)
        elements.messageInput.addEventListener('keydown', handleEnterKey);
    }
    
    function toggleSendButton() {
        elements.sendButton.disabled = elements.messageInput.value.trim() === '' && 
        !elements.attachmentInput.files.length;
    }
    
    function handleAttachmentChange() {
        if (elements.attachmentInput.files.length) {
            const file = elements.attachmentInput.files[0];
            elements.attachmentName.textContent = `${file.name} (${formatFileSize(file.size)})`;
            elements.attachmentPreview.classList.remove('d-none');
            elements.sendButton.disabled = false;
        } else {
            elements.attachmentPreview.classList.add('d-none');
            elements.sendButton.disabled = elements.messageInput.value.trim() === '';
        }
    }
    
    function removeAttachment() {
        elements.attachmentInput.value = '';
        elements.attachmentPreview.classList.add('d-none');
        elements.sendButton.disabled = elements.messageInput.value.trim() === '';
    }
    
    function handleEnterKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!elements.sendButton.disabled) {
                elements.chatForm.dispatchEvent(new Event('submit'));
            }
        }
    }
    
    function setupVisibilityListener() {
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                // Reset polling interval when page becomes visible
                config.polling.inactivityTimer = 0;
                config.polling.current = config.polling.min;
                loadMessages(false); // Immediately check for new messages
            } else {
                // Slow down polling when page is not visible
                config.polling.current = config.polling.max;
            }
        });
    }
    
    function startPolling() {
        const pollMessages = () => {
            if (!state.isPolling) {
                setTimeout(async () => {
                    try {
                        const hasNewMessages = await loadMessages(false);
                        config.polling.inactivityTimer = hasNewMessages ? 0 : config.polling.inactivityTimer + config.polling.current;
                        
                        // Adjust polling interval based on activity
                        if (hasNewMessages) {
                            // More frequent polling when active
                            config.polling.current = config.polling.min;
                        } else if (config.polling.inactivityTimer > 30000) { // 30 seconds of inactivity
                            // Less frequent polling when inactive
                            config.polling.current = Math.min(config.polling.max, config.polling.current * 1.5);
                        }
                    } catch (error) {
                        console.error('Error polling messages:', error);
                        config.polling.current = Math.min(config.polling.max, config.polling.current + 1000);
                    }
                    pollMessages();
                }, config.polling.current);
            }
        };
        
        // Start polling
        pollMessages();
    }
    
    // Message handling - improved to prevent duplicates
    async function loadMessages(showSpinner = true) {
        if (state.isPolling) return false; // Prevent multiple simultaneous fetches
        state.isPolling = true;

        if (showSpinner && state.isFirstLoad) {
            elements.loadingSpinner.classList.remove('d-none');
        }

        try {
            const response = await fetch(`${config.apiUrls.getMessages}?ticket_id=${config.ticketId}&since=${state.lastMessageTimestamp}`);
            if (!response.ok) throw new Error('Network response was not ok');

            const messages = await response.json();
            
            if (showSpinner && state.isFirstLoad) {
                elements.loadingSpinner.classList.add('d-none');
                state.isFirstLoad = false;
                
                if (messages.length === 0) {
                    elements.chatMessages.innerHTML = '<div class="text-center text-muted">No messages yet. Start the conversation!</div>';
                } else {
                    elements.chatMessages.innerHTML = '';
                }
            }

            if (messages.length > 0) {
                // Filter out messages we've already loaded
                const newMessages = messages.filter(msg => !state.loadedMessageIds.has(msg.id));

                if (newMessages.length > 0) {
                    // Update the timestamp based on the newest message
                    const sortedMessages = [...newMessages].sort((a, b) => 
                        new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
                    );
                    
                    state.lastMessageTimestamp = new Date(sortedMessages[0].created_at).getTime();
                    
                    // Add new messages to the queue
                    state.messageQueue.push(...newMessages);
                    
                    // Track these message IDs as loaded
                    newMessages.forEach(msg => state.loadedMessageIds.add(msg.id));
                    
                    processMessageQueue();
                    return true;
                }
            }

            return false;
        } catch (error) {
            console.error('Error loading messages:', error);
            if (showSpinner && state.isFirstLoad) {
                elements.loadingSpinner.classList.add('d-none');
                elements.chatMessages.innerHTML = '<div class="alert alert-danger">Failed to load messages. Please refresh the page.</div>';
                state.isFirstLoad = false;
            }
            return false;
        } finally {
            state.isPolling = false; // Ensure polling flag is reset
        }
    }

    function processMessageQueue() {
        if (state.messageQueue.length === 0) return;
        
        const currentBatch = state.messageQueue.splice(0, 10); // Process 10 messages at a time
        
        currentBatch.forEach(renderMessage);
        
        // Auto-scroll to the latest message
        scrollToBottom();
        
        // Process remaining messages, if any
        if (state.messageQueue.length > 0) {
            setTimeout(processMessageQueue, 50);
        }
    }
    
    function renderMessage(msg) {
        // Add data-id attribute to each message for easier tracking
        const isSelf = msg.sender !== 'admin';
        const messageEl = document.createElement('div');
        messageEl.classList.add(
            'message-container', 
            'd-flex', 
            isSelf ? 'justify-content-end' : 'justify-content-start'
        );
        
        const maxWidth = 'max-width: 75%;';
        const messageContent = `
            <div class="message ${isSelf ? 'user-message bg-primary text-white' : 'admin-message bg-light'}" 
                 style="${maxWidth}" data-message-id="${msg.id}">
                <div class="message-content">${escapeHtml(msg.message)}</div>
                ${msg.attachment ? renderAttachment(msg.attachment) : ''}
                <div class="message-meta">
                    <small class="${isSelf ? 'text-white-50' : 'text-muted'}">${formatTimestamp(msg.created_at)}</small>
                </div>
            </div>
        `;
        
        messageEl.innerHTML = messageContent;
        elements.chatMessages.appendChild(messageEl);
    }
    
    function scrollToBottom() {
        setTimeout(() => {
            elements.chatContainer.scrollTop = elements.chatContainer.scrollHeight;
        }, 100);
    }
    
    // Safely escape HTML content
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
            .replace(/\n/g, "<br>");
    }

    // Render attachment based on type
    function renderAttachment(attachmentPath) {
        const extension = attachmentPath.split('.').pop().toLowerCase();
        const fullPath = `${config.baseUrl}/${attachmentPath}`;
        
        // Check if it's an image
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
            return `
                <div class="attachment-container my-2">
                    <a href="${fullPath}" target="_blank" class="attachment-link">
                        <img src="${fullPath}" class="img-thumbnail" style="max-height: 200px;" loading="lazy" alt="Attachment">
                    </a>
                </div>
            `;
        }
        
        // If it's a video
        if (['mp4', 'webm', 'ogg'].includes(extension)) {
            return `
                <div class="attachment-container my-2">
                    <video controls class="img-thumbnail" style="max-height: 200px;">
                        <source src="${fullPath}" type="video/${extension === 'mp4' ? 'mp4' : extension === 'webm' ? 'webm' : 'ogg'}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            `;
        }
        
        // For documents and other files
        const iconClass = {
            'pdf': 'bi-file-earmark-pdf',
            'doc': 'bi-file-earmark-word',
            'docx': 'bi-file-earmark-word',
            'xls': 'bi-file-earmark-excel',
            'xlsx': 'bi-file-earmark-excel',
            'txt': 'bi-file-earmark-text'
        }[extension] || 'bi-file-earmark';
        
        return `
            <div class="attachment-container my-2">
                <a href="${fullPath}" target="_blank" class="attachment-link d-flex align-items-center">
                    <i class="bi ${iconClass} fs-4 me-2"></i>
                    <span>Attachment (${extension.toUpperCase()})</span>
                </a>
            </div>
        `;
    }

    // Format timestamp
    function formatTimestamp(timestamp) {
        if (!timestamp) return '';

        const messageDate = new Date(timestamp);
        const now = new Date();
        const isToday = messageDate.toDateString() === now.toDateString();
        
        if (isToday) {
            return messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else {
            return messageDate.toLocaleDateString([], { month: 'short', day: 'numeric' }) + 
                   ' ' + messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        else return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Send message
    async function sendMessage(e) {
        e.preventDefault();
        
        const message = elements.messageInput.value.trim();
        const attachment = elements.attachmentInput.files[0];
        
        if (!message && !attachment) return;
        
        // Disable form while sending
        elements.sendButton.disabled = true;
        elements.sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
        
        // Add optimistic UI update
        const tempId = 'msg-' + Date.now();
        const tempMessageEl = createOptimisticMessage(tempId, message, attachment);
        elements.chatMessages.appendChild(tempMessageEl);
        scrollToBottom();
        
        // Prepare form data
        const formData = new FormData();
        formData.append('ticket_id', config.ticketId);
        formData.append('admin_id', config.adminId);
        formData.append('message', message);
        if (attachment) {
            formData.append('attachment', attachment);
        }

        try {
            const response = await fetch(config.apiUrls.sendMessage, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            // Remove optimistic message
            document.getElementById(tempId)?.remove();
            
            if (data.status === 'success') {
                resetForm();
                // Force immediate reload of messages
                await loadMessages(false);
            } else {
                throw new Error(data.message || 'Failed to send message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            
            // Show error in place of the optimistic message
            const tempMessageEl = document.getElementById(tempId);
            if (tempMessageEl) {
                tempMessageEl.innerHTML = `
                    <div class="alert alert-danger">
                        Failed to send message. Please try again.
                    </div>
                `;
            }
        } finally {
            elements.sendButton.disabled = false;
            elements.sendButton.innerHTML = '<i class="bi bi-send-fill me-1"></i> Send';
        }
    }
    
    function createOptimisticMessage(id, message, attachment) {
        const tempMessage = document.createElement('div');
        tempMessage.id = id;
        tempMessage.classList.add('message-container', 'd-flex', 'justify-content-end', 'opacity-75');
        
        let attachmentPreviewHTML = '';
        if (attachment) {
            attachmentPreviewHTML = `
                <div class="attachment-container my-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-paperclip me-1"></i>
                        <span>${attachment.name} (uploading...)</span>
                    </div>
                </div>
            `;
        }
        
        tempMessage.innerHTML = `
            <div class="message user-message bg-primary text-white" style="max-width: 75%;">
                <div class="message-content">${escapeHtml(message)}</div>
                ${attachmentPreviewHTML}
                <div class="message-meta">
                    <small class="text-white-50">Sending...</small>
                </div>
            </div>
        `;
        
        return tempMessage;
    }
    
    function resetForm() {
        elements.messageInput.value = '';
        elements.attachmentInput.value = '';
        elements.attachmentPreview.classList.add('d-none');
    }
});

// Removed the separate refreshConversation function since it duplicates functionality
// and could cause message duplication issues
</script>

<style>
:root {
    --message-shadow: 0 2px 5px rgba(0,0,0,0.08);
    --message-animation-duration: 0.3s;
    --message-spacing: 0.75rem;
    --message-padding: 0.85rem;
    --message-border-radius: 1.25rem;
    --message-border-radius-self: 0.25rem;
    --user-message-bg: #0d6efd;
    --admin-message-bg: #f8f9fa;
    --admin-message-border: #e9ecef;
}

.message-container {
    animation: fadeIn var(--message-animation-duration) ease-in-out;
    margin-bottom: var(--message-spacing);
    position: relative;
}

.message {
    padding: var(--message-padding);
    border-radius: var(--message-border-radius);
    box-shadow: var(--message-shadow);
    position: relative;
    transition: all 0.2s ease;
}

.user-message {
    border-bottom-right-radius: var(--message-border-radius-self) !important;
    background-color: var(--user-message-bg);
}

.admin-message {
    border-bottom-left-radius: var(--message-border-radius-self) !important;
    background-color: var(--admin-message-bg);
    border: 1px solid var(--admin-message-border);
}

.message-meta {
    margin-top: 0.25rem;
    font-size: 0.75rem;
}

.attachment-link {
    text-decoration: none;
    color: inherit;
    transition: opacity 0.2s ease;
    display: inline-block;
}

.attachment-link:hover {
    opacity: 0.9;
}

.attachment-container {
    border-radius: 0.5rem;
    overflow: hidden;
}

.img-thumbnail {
    transition: transform 0.2s ease;
}

.img-thumbnail:hover {
    transform: scale(1.02);
}

#chat-container {
    scrollbar-width: thin;
    scrollbar-color: rgba(0,0,0,0.2) transparent;
}

#chat-container::-webkit-scrollbar {
    width: 6px;
}

#chat-container::-webkit-scrollbar-track {
    background: transparent;
}

#chat-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 6px;
}

#message {
    resize: none;
    transition: height 0.2s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?= $this->endSection() ?>