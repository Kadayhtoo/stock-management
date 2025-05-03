<?php
require_once __DIR__ . '/../models/Product.php';

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

        $totalProducts = Product::count();
        $totalPages = ceil($totalProducts / $limit);
        
        $products = Product::all($limit, $offset);
        require __DIR__ . '/../views/products/list.php';
        break;

    case 'create':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        require __DIR__ . '/../views/products/create.php';
        break;

    case 'store':
        try {
            Product::store($_POST);
    
            header('Location: /products');
            exit;
        } catch (PDOException $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../views/products/create.php';
            exit;
        }
        break;

    case 'edit':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        $product = Product::find($_GET['id']);
        require __DIR__ . '/../views/products/edit.php';
        break;

    case 'update':
        Product::update($_POST['id'], $_POST);
        header('Location: /products');
        break;

    case 'delete':
        if ($role !== 'admin') {
            echo "403 Forbidden: Admin access only.";
            exit;
        }
        Product::delete($_GET['id']);
        header('Location: /products');
        break;

    default:
        echo "Unknown action.";
        break;
}
