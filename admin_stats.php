<?php
/* api/admin_stats.php — GET, admin only.
   Powers the four number cards + "coming up next" list on the
   admin dashboard. */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$totalEvents = $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$totalCategories = $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$totalRegistrations = $pdo->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
$totalStudents = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

$upcomingStmt = $pdo->query(
    'SELECT e.id, e.title, e.event_date AS date, e.capacity,
            (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS seatsTaken
     FROM events e
     ORDER BY e.event_date, e.event_time
     LIMIT 5'
);

sendJson([
    'totalEvents' => (int)$totalEvents,
    'totalCategories' => (int)$totalCategories,
    'totalRegistrations' => (int)$totalRegistrations,
    'totalStudents' => (int)$totalStudents,
    'upcomingEvents' => $upcomingStmt->fetchAll(),
]);
