<?php
/* api/student_logout.php — POST, clears the student session. */

require_once __DIR__ . '/../config/db.php';

unset($_SESSION['student_id'], $_SESSION['student_name']);
sendJson(['success' => true]);
