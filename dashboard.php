<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

Auth::requireAuth();
$user = Auth::getUser();

$database = new Database();
$db = $database->getConnection();

// Get ticket statistics
$query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
    SUM(CASE WHEN status = 'in-progress' THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
    FROM tickets WHERE user_id = :user_id";
    
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user['id']);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get recent tickets
$query = "SELECT * FROM tickets WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user['id']);
$stmt->execute();
$recentTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('app/dashboard.twig', [
    'user' => $user,
    'stats' => $stats,
    'recentTickets' => $recentTickets,
    'isLoggedIn' => true
]);
?>