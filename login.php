<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$error = '';
$success = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            Auth::login($user);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Invalid email or password';
    }
}

echo $twig->render('auth/login.twig', [
    'error' => $error,
    'success' => $success
]);
?>