<?php
/* api/events_delete.php — POST, admin only. JSON: { id }
   Registrations for the event are removed automatically (ON DELETE
   CASCADE on the registrations table). */

require_once __DIR__ . '/../config/db.php';
requireAdminLogin();

$b = getJsonBody();
$id = (int)($b['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
$stmt->execute([$id]);

sendJson(['success' => true]);
