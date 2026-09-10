<?php
/* api/categories_delete.php — POST, admin only. JSON: { id }
   Refuses to delete a category that's still used by an event, same
   rule the frontend-only version enforced. */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$body = getJsonBody();
$id = (int)($body['id'] ?? 0);

$usage = $pdo->prepare('SELECT COUNT(*) AS total FROM events WHERE category_id = ?');
$usage->execute([$id]);
if ($usage->fetch()['total'] > 0) {
    sendJson(['success' => false, 'message' => 'This category is used by at least one event. Reassign or delete those events first.'], 409);
}

$stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
$stmt->execute([$id]);

sendJson(['success' => true]);
