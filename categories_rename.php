<?php
/* api/categories_rename.php — POST, admin only. JSON: { id, name } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$body = getJsonBody();
$id = (int)($body['id'] ?? 0);
$name = trim($body['name'] ?? '');

if ($id <= 0 || $name === '') {
    sendJson(['success' => false, 'message' => 'Missing category id or name.'], 400);
}

$stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
$stmt->execute([$name, $id]);

sendJson(['success' => true]);
