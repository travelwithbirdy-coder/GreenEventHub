<?php
/* api/admin_login.php
   POST JSON: { username, password } */

require_once __DIR__ . '/../config/db.php';

$body = getJsonBody();
$username = trim($body['username'] ?? '');
$password = $body['password'] ?? '';

$stmt = $pdo->prepare('SELECT id, username, password, full_name FROM admins WHERE username = ?');
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password'])) {
    sendJson(['success' => false, 'message' => 'Username or password is incorrect.'], 401);
}

$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['full_name'];

sendJson([
    'success' => true,
    'admin' => [
        'username' => $admin['username'],
        'fullName' => $admin['full_name'],
    ],
]);
