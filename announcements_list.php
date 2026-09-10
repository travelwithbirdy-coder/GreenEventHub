<?php
/* api/announcements_list.php — GET, public. */

require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query(
    'SELECT id, title, message, post_date AS date, posted_by AS postedBy
     FROM announcements ORDER BY post_date DESC, id DESC'
);

sendJson($stmt->fetchAll());
