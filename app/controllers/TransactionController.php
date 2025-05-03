<?php
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

        $totalTransactions = Transaction::count($limit, $offset);
        $totalPages = ceil($totalTransactions / $limit);

        $transactions = Transaction ::all($limit, $offset);
        require __DIR__ . '/../views/transactions/list.php';
        break;

    case 'store':
        Transaction::store($_POST);
        header('Location: /purchase-records');
        break;

    default:
        echo "Unknown action.";
        break;
}
