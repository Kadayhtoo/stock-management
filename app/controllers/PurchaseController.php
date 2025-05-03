<?php
require_once __DIR__ . '/../models/Purchase.php';
require_once __DIR__ . '/../models/Transaction.php';

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

        $totalProducts = Purchase::count();
        $totalPages = ceil($totalProducts / $limit);

        $purchases = Purchase ::all($limit, $offset);
        require __DIR__ . '/../views/transactions/purchase.php';
        break;

    case 'detail':

        $detail = Transaction ::findByPurchaseId($_GET['id']);
        require __DIR__ . '/../views/transactions/purchase-detail.php';
        break;

    default:
        echo "Unknown action.";
        break;
}
