<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Set to 'cache' in production
]);

// Database connection
$database = new Database();
$db = $database->getConnection();

// Check authentication
$isLoggedIn = Auth::isLoggedIn();
$user = Auth::getUser();

if ($isLoggedIn) {
    header('Location: dashboard.php');
    exit;
} else {
    echo $twig->render('landing.twig', [
        'isLoggedIn' => $isLoggedIn,
        'user' => $user
    ]);
}
?>