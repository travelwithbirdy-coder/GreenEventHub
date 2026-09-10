<?php
/* api/events_get.php — GET ?id=123, public. Returns one event. */

require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);

$sql = 'SELECT
          e.id, e.title, e.category_id AS categoryId, c.name AS categoryName,
          e.event_date AS date, e.event_time AS time, e.venue, e.organizer,
          e.capacity, e.description,
          (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS seatsTaken
        FROM events e
        JOIN categories c ON c.id = e.category_id
        WHERE e.id = ?';

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    sendJson(['success' => false, 'message' => 'Event not found.'], 404);
}

sendJson($event);
