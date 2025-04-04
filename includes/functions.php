<?php
require_once __DIR__ . '/../config/database.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function jsonResponse($success, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>