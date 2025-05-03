<?php
require_once __DIR__ . '/../models/User.php';

$action = $_GET['action'] ?? 'index';

// Check user session
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

// Optional: Simplify access
$user = $_SESSION['user'];
$role = $user['role'] ?? 'user';


switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalUsers = User::count($limit, $offset);
        $totalPages = ceil($totalUsers / $limit);

        $users = User::all($limit, $offset);
        require __DIR__ . '/../views/users/list.php';
        break;

    case 'create':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        require __DIR__ . '/../views/users/create.php';
        break;

    case 'store':
        try {
            User::store($_POST);
            header('Location: /users');
            exit;
        } catch (PDOException $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../views/users/create.php';
            exit;
        }
        
        break;

    case 'edit':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        $user = User::find($_GET['id']);
        require __DIR__ . '/../views/users/edit.php';
        break;

    case 'update':
        User::update($_POST['id'], $_POST);
        header('Location: /users');
        break;

    case 'delete':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        User::delete($_GET['id']);
        header('Location: /users');
        break;

    default:
        echo "Unknown action.";
        break;
}
