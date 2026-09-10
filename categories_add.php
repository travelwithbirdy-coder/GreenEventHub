<?php
/* api/categories_add.php — POST, admin only. JSON: { name } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$body = getJsonBody();
$name = trim($body['name'] ?? '');

if ($name === '') {
    sendJson(['success' => false, 'message' => 'Category name cannot be empty.'], 400);
}

try {
    $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
    $stmt->execute([$name]);
} catch (PDOException $e) {
    sendJson(['success' => false, 'message' => 'That category already exists.'], 409);
}

sendJson(['success' => true, 'id' => $pdo->lastInsertId()]);
