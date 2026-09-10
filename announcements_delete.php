<?php
/* api/announcements_delete.php — POST, admin only. JSON: { id } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$b = getJsonBody();
$id = (int)($b['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
$stmt->execute([$id]);

sendJson(['success' => true]);
