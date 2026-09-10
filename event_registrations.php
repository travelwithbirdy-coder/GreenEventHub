<?php
/* api/event_registrations.php — GET ?eventId=123, admin only.
   Returns the participant list for one event (used by the
   Registrations page and its printable list). */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$eventId = (int)($_GET['eventId'] ?? 0);

$sql = 'SELECT s.full_name AS fullName, s.student_id AS studentId, s.email, r.registered_on AS registeredOn
        FROM registrations r
        JOIN students s ON s.id = r.student_id
        WHERE r.event_id = ?
        ORDER BY r.registered_on, s.full_name';

$stmt = $pdo->prepare($sql);
$stmt->execute([$eventId]);
$participants = $stmt->fetchAll();

$eventStmt = $pdo->prepare('SELECT title, capacity FROM events WHERE id = ?');
$eventStmt->execute([$eventId]);
$event = $eventStmt->fetch();

sendJson([
    'event' => $event ?: null,
    'participants' => $participants,
]);
