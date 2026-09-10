<?php
/* api/events_add.php — POST, admin only.
   JSON: { title, categoryId, date, time, venue, organizer, capacity, description } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$b = getJsonBody();
$title = trim($b['title'] ?? '');
$categoryId = (int)($b['categoryId'] ?? 0);
$date = $b['date'] ?? '';
$time = $b['time'] ?? '';
$venue = trim($b['venue'] ?? '');
$organizer = trim($b['organizer'] ?? '');
$capacity = (int)($b['capacity'] ?? 0);
$description = trim($b['description'] ?? '');

if ($title === '' || $categoryId <= 0 || $date === '' || $time === '' || $venue === '' || $capacity <= 0) {
    sendJson(['success' => false, 'message' => 'Please fill in every required field.'], 400);
}

$stmt = $pdo->prepare(
    'INSERT INTO events (title, category_id, event_date, event_time, venue, organizer, capacity, description)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$title, $categoryId, $date, $time, $venue, $organizer, $capacity, $description]);

sendJson(['success' => true, 'id' => $pdo->lastInsertId()]);
