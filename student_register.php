<?php
/* api/student_register.php
   POST JSON: { fullName, studentId, email, password }
   Creates a new student account. */

require_once __DIR__ . '/../config/db.php';

$body = getJsonBody();
$fullName = trim($body['fullName'] ?? '');
$studentId = trim($body['studentId'] ?? '');
$email = trim($body['email'] ?? '');
$password = $body['password'] ?? '';

if ($fullName === '' || $studentId === '' || $email === '' || $password === '') {
    sendJson(['success' => false, 'message' => 'Please fill in every field before continuing.'], 400);
}
if (strlen($password) < 6) {
    sendJson(['success' => false, 'message' => 'Your password should be at least 6 characters.'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJson(['success' => false, 'message' => 'Please enter a valid email address.'], 400);
}

$check = $pdo->prepare('SELECT id FROM students WHERE email = ?');
$check->execute([$email]);
if ($check->fetch()) {
    sendJson(['success' => false, 'message' => 'An account already exists for this email. Try logging in instead.'], 409);
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare(
    'INSERT INTO students (full_name, student_id, email, password) VALUES (?, ?, ?, ?)'
);

try {
    $insert->execute([$fullName, $studentId, $email, $hashed]);
} catch (PDOException $e) {
    // Most likely the student_id (not just the email) was already used
    sendJson(['success' => false, 'message' => 'That student ID or email is already registered.'], 409);
}

sendJson(['success' => true, 'message' => 'Account created! You can now log in.']);
