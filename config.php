<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sql_note_smm79');
define('DB_USER', 'sql_note_smm79');
define('DB_PASS', 'sql_note_smm79');

// Login Credentials
define('ADMIN_USER', 'khuongsosad');
define('ADMIN_PASS', 'khuongsosad');

// Site Configuration
define('SITE_URL', 'https://note.smm79.com/');

// Database Connection
function getDB()
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Start session
session_start();

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Require login
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
