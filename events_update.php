<?php
/* api/events_update.php — POST, admin only.
   JSON: { id, title, categoryId, date, time, venue, organizer, capacity, description } */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$b = getJsonBody();
$id = (int)($b['id'] ?? 0);
$title = trim($b['title'] ?? '');
$categoryId = (int)($b['categoryId'] ?? 0);
$date = $b['date'] ?? '';
$time = $b['time'] ?? '';
$venue = trim($b['venue'] ?? '');
$organizer = trim($b['organizer'] ?? '');
$capacity = (int)($b['capacity'] ?? 0);
$description = trim($b['description'] ?? '');

if ($id <= 0 || $title === '' || $categoryId <= 0 || $date === '' || $time === '' || $venue === '' || $capacity <= 0) {
    sendJson(['success' => false, 'message' => 'Please fill in every required field.'], 400);
}

$stmt = $pdo->prepare(
    'UPDATE events
     SET title = ?, category_id = ?, event_date = ?, event_time = ?, venue = ?, organizer = ?, capacity = ?, description = ?
     WHERE id = ?'
);
$stmt->execute([$title, $categoryId, $date, $time, $venue, $organizer, $capacity, $description, $id]);

sendJson(['success' => true]);
