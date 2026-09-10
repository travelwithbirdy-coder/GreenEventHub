<?php
/* api/session_check.php — GET
   Tells the frontend who (if anyone) is currently logged in, so
   pages can redirect to login or show the right name in the nav. */

require_once __DIR__ . '/../config/db.php';

$response = ['student' => null, 'admin' => null];

if (!empty($_SESSION['student_id'])) {
    $response['student'] = [
        'id' => $_SESSION['student_id'],
        'fullName' => $_SESSION['student_name'],
    ];
}

if (!empty($_SESSION['admin_id'])) {
    $response['admin'] = [
        'id' => $_SESSION['admin_id'],
        'fullName' => $_SESSION['admin_name'],
    ];
}

sendJson($response);
