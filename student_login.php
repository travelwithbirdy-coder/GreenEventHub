<?php
/* api/student_login.php
   POST JSON: { email, password }
   Logs a student in and starts a session. */

require_once __DIR__ . '/../config/db.php';

$body = getJsonBody();
$email = trim($body['email'] ?? '');
$password = $body['password'] ?? '';

$stmt = $pdo->prepare('SELECT id, full_name, email, password FROM students WHERE email = ?');
$stmt->execute([$email]);
$student = $stmt->fetch();

if (!$student || !password_verify($password, $student['password'])) {
    sendJson(['success' => false, 'message' => 'Email or password is incorrect.'], 401);
}

$_SESSION['student_id'] = $student['id'];
$_SESSION['student_name'] = $student['full_name'];

sendJson([
    'success' => true,
    'student' => [
        'id' => $student['id'],
        'fullName' => $student['full_name'],
        'email' => $student['email'],
    ],
]);
