<?php
/* api/categories_list.php — GET, public.
   Returns every category, plus how many events use each one. */

require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query(
    'SELECT c.id, c.name, COUNT(e.id) AS eventCount
     FROM categories c
     LEFT JOIN events e ON e.category_id = c.id
     GROUP BY c.id, c.name
     ORDER BY c.name'
);

sendJson($stmt->fetchAll());
