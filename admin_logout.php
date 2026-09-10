<?php
/* api/admin_logout.php — POST, clears the admin session. */

require_once __DIR__ . '/../config/db.php';

unset($_SESSION['admin_id'], $_SESSION['admin_name']);
sendJson(['success' => true]);
