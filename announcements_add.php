<?php
/* api/announcements_add.php — POST, admin only. JSON: { title, message } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$b = getJsonBody();
$title = trim($b['title'] ?? '');
$message = trim($b['message'] ?? '');

if ($title === '' || $message === '') {
    sendJson(['success' => false, 'message' => 'Title and message are both required.'], 400);
}

$stmt = $pdo->prepare(
    'INSERT INTO announcements (title, message, post_date, posted_by) VALUES (?, ?, CURDATE(), ?)'
);
$stmt->execute([$title, $message, $_SESSION['admin_name']]);

sendJson(['success' => true, 'id' => $pdo->lastInsertId()]);
