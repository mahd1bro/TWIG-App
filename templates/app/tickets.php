<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

Auth::requireAuth();
$user = Auth::getUser();

$database = new Database();
$db = $database->getConnection();

$message = '';
$error = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['create_ticket'])) {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'open';
        $priority = $_POST['priority'] ?? 'medium';
        
        $query = "INSERT INTO tickets (user_id, title, description, status, priority) 
                  VALUES (:user_id, :title, :description, :status, :priority)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':priority', $priority);
        
        if ($stmt->execute()) {
            $message = 'Ticket created successfully!';
        } else {
            $error = 'Failed to create ticket';
        }
    }
    
    if (isset($_POST['update_ticket'])) {
        $id = $_POST['ticket_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        
        $query = "UPDATE tickets SET title = :title, description = :description, 
                  status = :status, priority = :priority WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':priority', $priority);
        
        if ($stmt->execute()) {
            $message = 'Ticket updated successfully!';
        } else {
            $error = 'Failed to update ticket';
        }
    }
    
    if (isset($_POST['delete_ticket'])) {
        $id = $_POST['ticket_id'] ?? '';
        
        $query = "DELETE FROM tickets WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user['id']);
        
        if ($stmt->execute()) {
            $message = 'Ticket deleted successfully!';
        } else {
            $error = 'Failed to delete ticket';
        }
    }
}

// Get filter parameters
$statusFilter = $_GET['status'] ?? 'all';
$priorityFilter = $_GET['priority'] ?? 'all';

// Build query with filters
$query = "SELECT * FROM tickets WHERE user_id = :user_id";
$params = [':user_id' => $user['id']];

if ($statusFilter !== 'all') {
    $query .= " AND status = :status";
    $params[':status'] = $statusFilter;
}

if ($priorityFilter !== 'all') {
    $query .= " AND priority = :priority";
    $params[':priority'] = $priorityFilter;
}

$query .= " ORDER BY created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('app/tickets.twig', [
    'user' => $user,
    'tickets' => $tickets,
    'message' => $message,
    'error' => $error,
    'statusFilter' => $statusFilter,
    'priorityFilter' => $priorityFilter,
    'isLoggedIn' => true
]);
?>