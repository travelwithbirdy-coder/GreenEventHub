<?php
/* api/events_list.php — GET, public.
   Returns every event with its category name and seats-taken count. */

require_once __DIR__ . '/../config/db.php';

$sql = 'SELECT
          e.id, e.title, e.category_id AS categoryId, c.name AS categoryName,
          e.event_date AS date, e.event_time AS time, e.venue, e.organizer,
          e.capacity, e.description,
          (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS seatsTaken
        FROM events e
        JOIN categories c ON c.id = e.category_id
        ORDER BY e.event_date, e.event_time';

sendJson($pdo->query($sql)->fetchAll());
