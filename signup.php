<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'includes/database.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$error = '';
$success = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if email exists
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $error = 'Email already registered';
        } else {
            // Create user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! Please login.';
                // Don't redirect immediately, show success message
            } else {
                $error = 'Failed to create account';
            }
        }
    }
}

echo $twig->render('auth/signup.twig', [
    'error' => $error,
    'success' => $success,
    'postData' => $_POST // Pass form data back to template
]);
?>