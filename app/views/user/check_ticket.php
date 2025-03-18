<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Ticket Status</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h2>Check Your Ticket Status</h2>
        <?php if (session()->getFlashdata('error')): ?>
            <p class="error"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>
        <form action="<?= base_url('user/check_ticket_status') ?>" method="post">
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Ticket ID:</label>
            <input type="text" name="ticket_id" required>

            <button type="submit">Check Status</button>
        </form>
    </div>
</body>
</html>
