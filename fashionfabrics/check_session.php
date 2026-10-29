<?php
// This file should be included in all pages that require session checking
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize session variables if not set
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = false;
    $_SESSION['username'] = '';
    $_SESSION['email'] = '';
    $_SESSION['full_name'] = '';
    $_SESSION['user_type'] = 'customer';
    $_SESSION['user_id'] = 0;
    $_SESSION['phone'] = '';
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to require admin access
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }
}
?>