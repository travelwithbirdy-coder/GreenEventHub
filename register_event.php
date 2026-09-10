<?php
/* api/register_event.php — POST, student only. JSON: { eventId }
   Registers the logged-in student for an event, checking capacity
   and preventing double registration. */

require_once __DIR__ . '/../config/db.php';
requireStudentLogin();

$b = getJsonBody();
$eventId = (int)($b['eventId'] ?? 0);
$studentId = $_SESSION['student_id'];

$eventStmt = $pdo->prepare(
    'SELECT capacity, (SELECT COUNT(*) FROM registrations WHERE event_id = ?) AS seatsTaken
     FROM events WHERE id = ?'
);
$eventStmt->execute([$eventId, $eventId]);
$event = $eventStmt->fetch();

if (!$event) {
    sendJson(['success' => false, 'message' => 'This event could not be found.'], 404);
}
if ($event['seatsTaken'] >= $event['capacity']) {
    sendJson(['success' => false, 'message' => 'This event is fully booked.'], 409);
}

$dupCheck = $pdo->prepare('SELECT id FROM registrations WHERE event_id = ? AND student_id = ?');
$dupCheck->execute([$eventId, $studentId]);
if ($dupCheck->fetch()) {
    sendJson(['success' => false, 'message' => 'You are already registered for this event.'], 409);
}

$insert = $pdo->prepare(
    'INSERT INTO registrations (event_id, student_id, registered_on) VALUES (?, ?, CURDATE())'
);
$insert->execute([$eventId, $studentId]);

sendJson(['success' => true, 'message' => "You're in! This event has been added to your schedule."]);
