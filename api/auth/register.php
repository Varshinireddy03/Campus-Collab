<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../../config/database.php';

try {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    
    if (empty($json)) {
        throw new Exception('No data received', 400);
    }

    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: '.json_last_error_msg(), 400);
    }

    // Validate all fields
    $errors = [];

    if (empty($data['name'])) {
        $errors['name'] = 'Full name is required';
    }

    if (empty($data['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($data['password'])) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($data['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    if (empty($data['confirm_password'])) {
        $errors['confirm_password'] = 'Please confirm your password';
    } elseif ($data['password'] !== $data['confirm_password']) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }

    $pdo = Database::getConnection();
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    
    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Email already registered'
        ]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['email'], $hashedPassword]);
    
    // Get the new user's ID
    $userId = $pdo->lastInsertId();
    
    // Start session and log user in
    session_start();
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $data['email'];
    $_SESSION['user_name'] = $data['name'];
    
    // Success response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!',
        'user' => [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email']
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