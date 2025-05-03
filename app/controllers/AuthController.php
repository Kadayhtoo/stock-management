<?php
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (User::register($username, $email, $password)) {
            header("Location: /login");
            exit;
        } else {
            $error = "Registration failed.";
        }
    }

    if ($_GET['action'] === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /dashboard');
            } else {
                header('Location: /home');
            }
            exit;
        } else {
            $error = "Invalid login credentials.";
        }
    }
}
if ($_GET['action'] === 'logout') {
    logout(); 
}

$view = $_GET['action'] === 'register' ? 'register' : 'login';
require_once __DIR__ . "/../views/auth/{$view}.php";

function logout(){
    session_unset();     // Remove all session variables
    session_destroy();   // Destroy the session
    header('Location: /login'); // Redirect to login page
    exit;
}


