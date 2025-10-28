<?php
session_start();

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function getUser() {
        return $_SESSION['user'] ?? null;
    }
    
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
    }
    
    public static function logout() {
        session_destroy();
    }
    
    public static function requireAuth() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
}
?>