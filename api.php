<?php
// Database connection
include "conn.php";
session_start(); // Start the session at the top

// Handling the incoming requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'signin':
            handleSignIn($pdo);
            break;
        case 'signup':
            handleSignUp($pdo);
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
}

// Handle SignIn
function handleSignIn($pdo) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
        return;
    }

    // Check if the user exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        // Store user ID and username in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(['status' => 'success', 'message' => 'Sign in successful']);
    } else {
        // Failed login
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }
}

// Handle SignUp
function handleSignUp($pdo) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        return;
    }

    // Check if the username or email already exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
        return;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    if ($stmt->execute([$username, $email, $hashedPassword])) {
        // Optionally log the user in immediately after sign-up
        $userId = $pdo->lastInsertId(); // Get the ID of the newly inserted user
        $_SESSION['user_id'] = $userId; // Store user ID in the session
        $_SESSION['username'] = $username; // Store username in the session

        echo json_encode(['status' => 'success', 'message' => 'Sign up successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to register user']);
    }
}
?>
