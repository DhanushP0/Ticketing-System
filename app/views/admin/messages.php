<?= $this->extend('layouts/admin_template') ?>

<?= $this->section('content') ?>
<style>
  :root {
    /* Modern color palette */
    --primary: #4f46e5;
    --primary-hover: #4338ca;
    --primary-light: #e0e7ff;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --dark: #111827;
    --light: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;

    /* Message colors */
    --user-bg: #ecfdf5;
    --user-border: #d1fae5;
    --admin-bg: #eff6ff;
    --admin-border: #dbeafe;

    /* Border radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-full: 9999px;

    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

    /* Transitions */
    --transition-fast: all 0.15s ease;
    --transition: all 0.3s ease;
  }

  body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background-color: var(--gray-100);
    color: var(--dark);
    line-height: 1.5;
  }

  .ticket-container {
    max-width: 900px;
    margin: 2rem auto;
    border-radius: var(--radius-lg);
    background: white;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 4rem);
  }

  .ticket-header {
    background-color: white;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
  }

  .ticket-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }

  .ticket-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .ticket-id {
    font-size: 0.875rem;
    color: var(--gray-500);
    background-color: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-weight: 500;
  }

  .ticket-status {
    font-size: 0.75rem;
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: var(--radius-full);
    font-weight: 500;
  }

  .status-open {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success);
  }

  .status-closed {
    background-color: rgba(107, 114, 128, 0.1);
    color: var(--gray-600);
  }

  .ticket-metadata {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
  }

  .chat-container {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    overflow: hidden;
  }

  .chat-box {
    padding: 1.5rem;
    overflow-y: auto;
    background: white;
    display: flex;
    flex-direction: column;
    scroll-behavior: smooth;
    flex-grow: 1;
  }

  .message {
    margin-bottom: 1.25rem;
    max-width: 85%;
    display: flex;
    flex-direction: column;
    position: relative;
    animation: messageAppear 0.3s ease-out;
  }

  @keyframes messageAppear {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .message-content {
    padding: 0.875rem 1.125rem;
    border-radius: var(--radius-md);
    position: relative;
    word-break: break-word;
    box-shadow: var(--shadow-sm);
    line-height: 1.6;
  }

  .message-meta {
    display: flex;
    align-items: center;
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    color: var(--gray-500);
  }

  .message-sender {
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.375rem;
  }

  .message-time {
    margin-left: 0.5rem;
  }

  .user {
    align-self: flex-end;
  }

  .user .message-content {
    background-color: var(--user-bg);
    border: 1px solid var(--user-border);
    color: var(--dark);
    border-top-right-radius: 0;
  }

  .user .message-meta {
    align-self: flex-end;
  }

  .admin {
    align-self: flex-start;
  }

  .admin .message-content {
    background-color: var(--admin-bg);
    border: 1px solid var(--admin-border);
    color: var(--dark);
    border-top-left-radius: 0;
  }

  .admin .message-meta {
    align-self: flex-start;
  }

  .chat-image {
    max-width: 100%;
    max-height: 300px;
    border-radius: var(--radius-sm);
    margin-top: 0.5rem;
    cursor: pointer;
    transition: var(--transition);
  }

  .chat-image:hover {
    opacity: 0.9;
  }

  .chat-video {
    max-width: 100%;
    max-height: 300px;
    border-radius: var(--radius-sm);
    margin-top: 0.5rem;
  }

  .message-form {
    padding: 1rem 1.5rem;
    background-color: white;
    border-top: 1px solid var(--gray-200);
    flex-shrink: 0;
  }

  .message-input-container {
    display: flex;
    align-items: flex-end;
    position: relative;
    gap: 0.5rem;
  }

  .message-input {
    flex-grow: 1;
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    padding: 0.75rem 1rem;
    resize: none;
    line-height: 1.5;
    font-size: 0.95rem;
    max-height: 120px;
    min-height: 56px;
    transition: var(--transition-fast);
    overflow-y: auto;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
  }

  .message-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
  }

  .input-actions {
    display: flex;
    gap: 0.5rem;
  }

  .message-action-btn {
    background-color: white;
    color: var(--gray-500);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-full);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
    flex-shrink: 0;
  }

  .message-action-btn:hover {
    background-color: var(--gray-100);
    color: var(--primary);
    border-color: var(--gray-400);
  }

  .file-input {
    display: none;
  }

  .message-send {
    background-color: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-full);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
    flex-shrink: 0;
  }

  .message-send:hover {
    background-color: var(--primary-hover);
    transform: translateY(-1px);
  }

  .message-send:disabled {
    background-color: var(--gray-300);
    cursor: not-allowed;
    transform: none;
  }

  .ticket-actions {
    display: flex;
    gap: 0.5rem;
  }

  .ticket-action-btn {
    background-color: white;
    border: 1px solid var(--gray-300);
    color: var(--gray-600);
    border-radius: var(--radius-md);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .ticket-action-btn:hover {
    background-color: var(--gray-100);
    border-color: var(--gray-400);
    color: var(--dark);
  }

  .close-ticket-btn {
    background-color: var(--primary);
    color: white;
    border: none;
  }

  .close-ticket-btn:hover {
    background-color: var(--primary-hover);
    color: white;
  }

  .typing-indicator {
    display: none;
    align-self: flex-start;
    color: var(--gray-500);
    font-size: 0.875rem;
    margin-top: -0.5rem;
    margin-bottom: 0.5rem;
    padding-left: 0.5rem;
  }

  .typing-indicator.active {
    display: flex;
    align-items: center;
  }

  .typing-dots {
    display: flex;
    margin-left: 0.5rem;
  }

  .typing-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: var(--gray-400);
    margin-right: 3px;
    animation: typingAnimation 1.5s infinite ease-in-out;
  }

  .typing-dot:nth-child(2) {
    animation-delay: 0.2s;
  }

  .typing-dot:nth-child(3) {
    animation-delay: 0.4s;
    margin-right: 0;
  }

  @keyframes typingAnimation {
    0% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-6px);
    }

    100% {
      transform: translateY(0);
    }
  }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--gray-500);
    padding: 2rem;
    text-align: center;
  }

  .empty-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
  }

  .file-preview {
    display: none;
    margin-top: 0.5rem;
    position: relative;
    max-width: 150px;
  }

  .file-preview.active {
    display: block;
  }

  .file-preview img,
  .file-preview video {
    max-width: 100%;
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-200);
  }

  .remove-file {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: var(--gray-600);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 10px;
  }

  /* Modal for image preview */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.85);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .modal.show {
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
  }

  .modal-content {
    max-width: 90%;
    max-height: 90vh;
  }

  .modal img {
    max-width: 100%;
    max-height: 90vh;
    display: block;
    margin: 0 auto;
  }

  .close-modal {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
  }

  .scrollbar-gray::-webkit-scrollbar {
    width: 6px;
  }

  .scrollbar-gray::-webkit-scrollbar-track {
    background: transparent;
  }

  .scrollbar-gray::-webkit-scrollbar-thumb {
    background-color: var(--gray-300);
    border-radius: 20px;
  }

  .scrollbar-gray::-webkit-scrollbar-thumb:hover {
    background-color: var(--gray-400);
  }

  @media (max-width: 768px) {
    .ticket-container {
      height: calc(100vh - 2rem);
      margin: 1rem;
      max-width: none;
    }

    .ticket-header {
      padding: 1rem;
    }

    .ticket-actions {
      flex-wrap: wrap;
    }

    .message {
      max-width: 90%;
    }
  }

  .char-count {
    position: absolute;
    right: 12px;
    bottom: 12px;
    font-size: 0.75rem;
    color: var(--gray-400);
    pointer-events: none;
  }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="ticket-container">
  <div class="ticket-header">
    <div class="ticket-info">
      <h1 class="ticket-title">
        <?= esc($ticket['subject'] ?? 'Ticket Conversation') ?>
        <span class="ticket-id">#<?= esc($ticket['ticket_id']) ?></span>
        <span class="ticket-status status-<?= strtolower($ticket['status'] ?? 'open') ?>">
          <?= ucfirst($ticket['status'] ?? 'Open') ?>
        </span>
      </h1>
      <div class="ticket-metadata">
        <span><i class="far fa-user fa-sm"></i> <?= esc($ticket['user_name'] ?? 'Customer') ?></span>
        <span class="ms-2"><i class="far fa-calendar fa-sm"></i> Opened:
          <?= date('M d, Y h:i A', strtotime($ticket['created_at'] ?? 'now')) ?></span>
      </div>
    </div>
    <div class="ticket-actions">
      <button class="ticket-action-btn" id="refreshBtn" title="Refresh conversation">
        <i class="fas fa-sync-alt"></i>
        <span>Refresh</span>
      </button>
      <button class="ticket-action-btn" id="backBtn" title="Back to tickets list">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
      </button>
      <?php if (($ticket['status'] ?? 'open') !== 'closed'): ?>
        <button class="ticket-action-btn close-ticket-btn" id="closeTicketBtn" title="Close this ticket">
          <i class="fas fa-check-circle"></i>
          <span>Mark Resolved</span>
        </button>
      <?php endif; ?>
    </div>
  </div>

  <div class="chat-container">
    <div class="chat-box scrollbar-gray" id="chatBox">
      <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
          <div class="message <?= ($msg['sender'] === 'user') ? 'user' : 'admin' ?>">
            <div class="message-meta">
              <span class="message-sender">
                <?php if ($msg['sender'] === 'user'): ?>
                  <i class="fas fa-user-circle"></i>
                <?php else: ?>
                  <i class="fas fa-headset"></i>
                <?php endif; ?>
                <?= ucfirst($msg['sender']) ?>
              </span>
              <span
                class="message-time"><?= isset($msg['created_at']) ? date('M d, h:i A', strtotime($msg['created_at'])) : 'Just now' ?></span>
            </div>

            <!-- Message Content -->
            <div class="message-content">
              <?= nl2br(esc($msg['message'])) ?>

              <!-- Display attachment immediately after text -->
              <?php if (!empty($msg['attachment'])): ?>
                <?php
                $fileExt = pathinfo($msg['attachment'], PATHINFO_EXTENSION);
                $imageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $videoExt = ['mp4', 'webm', 'mov'];
                ?>

                <div class="attachment-preview">
                  <?php if (in_array(strtolower($fileExt), $imageExt)): ?>
                    <img src="<?= base_url($msg['attachment']) ?>" class="chat-image"
                      data-src="<?= base_url($msg['attachment']) ?>" alt="Attachment">
                  <?php elseif (in_array(strtolower($fileExt), $videoExt)): ?>
                    <video controls class="chat-video">
                      <source src="<?= base_url($msg['attachment']) ?>" type="video/<?= $fileExt ?>">
                      Your browser does not support the video tag.
                    </video>
                  <?php else: ?>
                    <div class="file-attachment">
                      <a href="<?= base_url($msg['attachment']) ?>" target="_blank" class="file-link">
                        <i class="fas fa-file"></i> Download Attachment
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-comments empty-icon"></i>
          <p>No messages yet. Start the conversation by sending a message.</p>
        </div>
      <?php endif; ?>
    </div>

    <div class="typing-indicator" id="typingIndicator">
      <span>User is typing</span>
      <div class="typing-dots">
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
      </div>
    </div>

    <!-- Message Reply Form -->
    <div class="message-form">
      <form id="messageForm" enctype="multipart/form-data">
        <input type="hidden" name="ticket_id" value="<?= esc($ticket['ticket_id']) ?>">
        <input type="hidden" name="admin_id" value="<?= esc(session()->get('admin_id')) ?>">

        <div class="message-input-container">
          <textarea name="message" class="message-input" id="messageInput" placeholder="Type your message..."
            maxlength="1000"></textarea>
          <span class="char-count" id="charCount">0/1000</span>

          <div class="input-actions">
            <!-- File Upload Button -->
            <label for="fileInput" class="message-action-btn" title="Attach file">
              <i class="fas fa-paperclip"></i>
            </label>
            <input type="file" name="attachment" id="fileInput" class="file-input" accept="image/*,video/*">

            <!-- Emoji Button -->
            <button type="button" class="message-action-btn" id="emojiBtn" title="Add emoji">
              <i class="far fa-smile"></i>
            </button>

            <!-- Submit Button -->
            <button type="submit" class="message-send" id="sendButton" title="Send message">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>

        <div class="file-preview" id="filePreview">
          <div class="remove-file" id="removeFile">
            <i class="fas fa-times"></i>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Image Preview Modal -->
<div class="modal" id="imageModal">
  <span class="close-modal" id="closeModal">&times;</span>
  <div class="modal-content">
    <img id="modalImage" src="" alt="Full size image">
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    const chatBox = $("#chatBox");
    const messageInput = $("#messageInput");
    const fileInput = $("#fileInput");
    const sendButton = $("#sendButton");
    const filePreview = $("#filePreview");
    const removeFile = $("#removeFile");
    const charCount = $("#charCount");
    const modal = $("#imageModal");
    const modalImage = $("#modalImage");
    const closeModal = $("#closeModal");
    const refreshBtn = $("#refreshBtn");
    const backBtn = $("#backBtn");
    const closeTicketBtn = $("#closeTicketBtn");
    const typingIndicator = $("#typingIndicator");

    let typingTimer;
    let isUserTyping = false;

    // Scroll to bottom of chat
    function scrollToBottom() {
      chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    scrollToBottom();

    // Handle character count
    messageInput.on('input', function () {
      const count = $(this).val().length;
      charCount.text(`${count}/1000`);

      if (count > 900) {
        charCount.css('color', '#ef4444');
      } else {
        charCount.css('color', 'var(--gray-400)');
      }

      // Auto-adjust height
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    });

    // File preview
    fileInput.change(function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        const fileType = file.type.split('/')[0];

        reader.onload = function (e) {
          if (fileType === 'image') {
            filePreview.html(`
                        <div class="remove-file" id="removeFile">
                            <i class="fas fa-times"></i>
                        </div>
                        <img src="${e.target.result}" alt="File preview">
                    `);
          } else if (fileType === 'video') {
            filePreview.html(`
                        <div class="remove-file" id="removeFile">
                            <i class="fas fa-times"></i>
                        </div>
                        <video controls>
                            <source src="${e.target.result}" type="${file.type}">
                        </video>
                    `);
          }
          filePreview.addClass('active');

          // Reattach event listener to remove button
          $("#removeFile").click(function (e) {
            e.preventDefault();
            fileInput.val('');
            filePreview.removeClass('active');
          });
        };

        reader.readAsDataURL(file);
      }
    });

    // Remove file button
    removeFile.click(function (e) {
      e.preventDefault();
      fileInput.val('');
      filePreview.removeClass('active');
    });

    // Send message on form submit
    $("#messageForm").submit(function (e) {
      e.preventDefault();
      const message = messageInput.val().trim();
      const file = fileInput[0].files.length > 0 ? fileInput[0].files[0] : null;

      if (!message && !file) {
        alert("Please enter a message or attach a file.");
        return;
      }

      sendButton.prop('disabled', true);
      const formData = new FormData(this);

      $.ajax({
        url: "<?= site_url('admin/sendMessage') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            let messageHTML = `
                        <div class="message admin">
                            <div class="message-meta">
                                <span class="message-sender">
                                    <i class="fas fa-headset"></i> Admin
                                </span>
                                <span class="message-time">${response.time}</span>
                            </div>
                            <div class="message-content">
                                ${message ? formatMessage(message) : ""}
                    `;

                    if (response.attachment) {
    const fileExt = response.attachment.split('.').pop().trim().toLowerCase();

    console.log("Attachment URL:", response.attachment);  // Debug file path
    console.log("Extracted File Extension:", fileExt);    // Debug file extension

    const imageExt = ["jpg", "jpeg", "png", "gif", "webp"];
    const videoExt = ["mp4", "webm", "mov"];

    if (imageExt.includes(fileExt)) {
        messageHTML += `<img src="${response.attachment}" class="chat-image" data-src="${response.attachment}" alt="Attachment">`;
    } else if (videoExt.includes(fileExt)) {
        messageHTML += `
            <video controls class="chat-video">
                <source src="${response.attachment}" type="video/${fileExt}">
            </video>
        `;
    } else {
        messageHTML += `
            <div class="file-attachment">
                <a href="${response.attachment}" target="_blank" class="file-link">
                    <i class="fas fa-file"></i> Download Attachment
                </a>
            </div>
        `;
    }
}

            messageHTML += `</div></div>`;

            chatBox.append(messageHTML);
            messageInput.val("").css("height", "auto");
            fileInput.val("");
            filePreview.removeClass('active');
            charCount.text("0/1000");
            scrollToBottom();

            // Reattach image click event
            $('.chat-image').off('click').on('click', function () {
              const imgSrc = $(this).data('src');
              modalImage.attr('src', imgSrc);
              modal.addClass('show');
            });
          } else {
            alert("Failed to send message: " + response.message);
          }
          sendButton.prop('disabled', false);
        },
        error: function (xhr) {
          alert("Server error: " + (xhr.responseJSON?.message || "Please try again."));
          sendButton.prop('disabled', false);
        }
      });
    });

    // Format message with line breaks
    function formatMessage(message) {
      return message.replace(/\n/g, '<br>');
    }

    // Image modal functionality
    $('.chat-image').on('click', function () {
      const imgSrc = $(this).data('src');
      modalImage.attr('src', imgSrc);
      modal.addClass('show');
    });

    closeModal.on('click', function () {
      modal.removeClass('show');
    });

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && modal.hasClass('show')) {
        modal.removeClass('show');
      }
    });

    // Close ticket button
    closeTicketBtn.on('click', function () {
      if (confirm("Are you sure you want to close this ticket?")) {
        $.ajax({
          url: "<?= site_url('admin/closeTicket') ?>",
          type: "POST",
          data: { ticket_id: "<?= esc($ticket['ticket_id']) ?>" },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              alert("Ticket has been closed successfully.");
              location.reload();
            } else {
              alert("Failed to close ticket: " + response.message);
            }
          },
          error: function () {
            alert("Server error, please try again.");
          }
        });
      }
    });

    // Back button
    backBtn.on('click', function () {
      window.location.href = "<?= site_url('admin/tickets') ?>";
    });

    // Refresh button
    refreshBtn.on('click', function () {
      location.reload();
    });

    // Typing indicator functionality
    messageInput.on('focus', function () {
      // Set up socket connection for typing indicator if needed
      startTypingIndicator();
    });

    messageInput.on('keydown', function () {
      if (!isUserTyping) {
        isUserTyping = true;
        sendTypingStatus(true);
      }

      clearTimeout(typingTimer);
      typingTimer = setTimeout(function () {
        isUserTyping = false;
        sendTypingStatus(false);
      }, 2000);
    });

    function startTypingIndicator() {
      // You would implement your websocket or polling logic here
      // This is a placeholder for the actual implementation
      console.log("Typing indicator initialized");
    }

    function sendTypingStatus(isTyping) {
      // Send typing status to server
      $.ajax({
        url: "<?= site_url('admin/updateTypingStatus') ?>",
        type: "POST",
        data: {
          ticket_id: "<?= esc($ticket['ticket_id']) ?>",
          is_typing: isTyping ? 1 : 0
        },
        dataType: "json",
        success: function (response) {
          console.log("Typing status updated:", response);
        }
      });
    }

    // Handle emoji button
    $("#emojiBtn").on('click', function () {
      // This would be where you'd implement your emoji picker
      // For example, using a library like emoji-picker-element
      alert("Emoji picker functionality would be implemented here");
    });

    // Auto-refresh conversation
    function setupAutoRefresh() {
      setInterval(function () {
        if (!$("#messageInput").is(":focus") && !modal.hasClass('show')) {
          refreshConversation();
        }
      }, 30000); // Refresh every 30 seconds
    }

    function refreshConversation() {
      $.ajax({
        url: "<?= site_url('admin/getMessages') ?>",
        type: "GET",
        data: { ticket_id: "<?= esc($ticket['ticket_id']) ?>" },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            // Only update if there are new messages
            if (response.messages.length > $('.message').length) {
              updateChatBox(response.messages);
            }

            // Update ticket status if changed
            if (response.ticket_status !== "<?= esc($ticket['status']) ?>") {
              location.reload();
            }
          }
        }
      });
    }

    function updateChatBox(messages) {
      chatBox.empty();

      if (messages.length === 0) {
        chatBox.html(`
            <div class="empty-state">
                <i class="fas fa-comments empty-icon"></i>
                <p>No messages yet. Start the conversation by sending a message.</p>
            </div>
        `);
        return;
      }

      messages.forEach(function (msg) {
        let messageHTML = `
            <div class="message ${msg.sender === 'user' ? 'user' : 'admin'}">
                <div class="message-meta">
                    <span class="message-sender">
                        ${msg.sender === 'user' ? '<i class="fas fa-user-circle"></i>' : '<i class="fas fa-headset"></i>'}
                        ${msg.sender === 'user' ? 'User' : 'Admin'}
                    </span>
                    <span class="message-time">${msg.created_at}</span>
                </div>
                <div class="message-content">
                    ${formatMessage(msg.message)}
        `;

        if (msg.attachment) {
          const fileExt = msg.attachment.split('.').pop().toLowerCase();
          const imageExt = ["jpg", "jpeg", "png", "gif", "webp"];
          const videoExt = ["mp4", "webm", "mov"];

          if (imageExt.includes(fileExt)) {
            messageHTML += `<img src="${msg.attachment}" class="chat-image" data-src="${msg.attachment}" alt="Attachment">`;
          } else if (videoExt.includes(fileExt)) {
            messageHTML += `
                    <video controls class="chat-video">
                        <source src="${msg.attachment}" type="video/${fileExt}">
                    </video>
                `;
          } else {
            messageHTML += `
                    <div class="file-attachment">
                        <a href="${msg.attachment}" target="_blank" class="file-link">
                            <i class="fas fa-file"></i> Download Attachment
                        </a>
                    </div>
                `;
          }
        }

        messageHTML += `</div></div>`;
        chatBox.append(messageHTML);
      });

      scrollToBottom();

      // Reattach image click event
      $('.chat-image').off('click').on('click', function () {
        const imgSrc = $(this).data('src');
        modalImage.attr('src', imgSrc);
        modal.addClass('show');
      });
    }

    // Initialize
    setupAutoRefresh();

    // Listen for websocket events (if implemented)
    function setupWebsocketListeners() {
      // This would be where you'd implement websocket listeners
      // For real-time updates like typing indicators and new messages
      console.log("Websocket listeners would be set up here");
    }

    // Handle window resizing
    $(window).on('resize', function () {
      scrollToBottom();
    });

    // Document click handler for closing modal
    $(document).on('click', function (e) {
      if ($(e.target).is(modal)) {
        modal.removeClass('show');
      }
    });
  });

</script>
<?= $this->endSection() ?>