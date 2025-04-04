<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../../config/database.php';

try {
    $json = file_get_contents('php://input');
    
    if (empty($json)) {
        throw new Exception('No data received', 400);
    }

    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: '.json_last_error_msg(), 400);
    }

    // Validate required fields
    if (empty($data['email'])) {
        throw new Exception('Email is required', 400);
    }

    if (empty($data['password'])) {
        throw new Exception('Password is required', 400);
    }

    $pdo = Database::getConnection();
    
    // Case-insensitive email search
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE LOWER(email) = LOWER(?)");
    $stmt->execute([trim($data['email'])]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        error_log("Login attempt for non-existent email: " . $data['email']);
        throw new Exception('Incorrect email or password', 401);
    }
    
    // Debug output - remove in production
    error_log("Stored hash: " . $user['password']);
    error_log("Input password: " . $data['password']);
    
    if (!password_verify($data['password'], $user['password'])) {
        error_log("Password verification failed for user: " . $user['email']);
        throw new Exception('Incorrect email or password', 401);
    }
    
    // Start session
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>