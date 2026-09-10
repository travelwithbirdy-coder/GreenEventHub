<?php
/* api/my_schedule.php — GET, student only.
   Returns the events the logged-in student has registered for. */

require_once __DIR__ . '/../config/db.php';
requireStudentLogin();

$sql = 'SELECT
          e.id, e.title, e.category_id AS categoryId, c.name AS categoryName,
          e.event_date AS date, e.event_time AS time, e.venue, e.organizer,
          e.capacity, e.description, r.registered_on AS registeredOn
        FROM registrations r
        JOIN events e ON e.id = r.event_id
        JOIN categories c ON c.id = e.category_id
        WHERE r.student_id = ?
        ORDER BY e.event_date, e.event_time';

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['student_id']]);

sendJson($stmt->fetchAll());
