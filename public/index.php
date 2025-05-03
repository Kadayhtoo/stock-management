<?php
session_start();
// require_once __DIR__ . '/../app/core/bootstrap.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = $_GET['action'] ?? 'index';

switch ($request) {
    case '/products':
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin
        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden: Only admin can access the products management.";
            exit;
        }
        // Optional: Parse query string (e.g., ?action=create)
        $query = $_GET['action'] ?? null;
        $_GET['action'] = $query;
    
        require __DIR__ . '/../app/controllers/ProductController.php';
        break;

    case '/users':
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin
        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden: Only admin can access the products management.";
            exit;
        }
        // Optional: Parse query string (e.g., ?action=create)
        $query = $_GET['action'] ?? null;
        $_GET['action'] = $query;
    
        require __DIR__ . '/../app/controllers/UserController.php';
        break;

    case '/purchases':
    
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    
        // Parse JSON payload
        $payload = json_decode($_POST['payload'] ?? '', true);
        if (!$payload || !isset($payload['items'])) {
            http_response_code(400);
            echo "Invalid purchase data.";
            exit;
        }
         // Optional: Parse query string (e.g., ?action=create)
        $query = $_GET['action'] ?? null;
        $_GET['action'] = $query;
        // You can pass $payload to the controller here
        require __DIR__ . '/../app/controllers/TransactionController.php';
        break;

    case '/purchase-records':

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        $query = $_GET['action'] ?? null;
        $_GET['action'] = $query;
        require __DIR__ . '/../app/controllers/PurchaseController.php';
        break;

    case '/transactions':

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin
        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden: Only admin can access the products management.";
            exit;
        }
        // You can pass $payload to the controller here
        require __DIR__ . '/../app/controllers/TransactionController.php';
        break;
    
    case '/':
        // Default route shows the registration form
        $_GET['action'] = 'register';
        require __DIR__ . '/../app/controllers/AuthController.php';
        break;

    case '/register':
        $_GET['action'] = 'register';
        require __DIR__ . '/../app/controllers/AuthController.php';
        break;

    case '/login':
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            header('Location: /dashboard');
            exit;
        }
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user')
        {
            header('Location: /home');
            exit;
        }
        $_GET['action'] = 'login';
        require __DIR__ . '/../app/controllers/AuthController.php';
        break;

    case '/logout':
        $_GET['action'] = 'logout';
        require __DIR__ . '/../app/controllers/AuthController.php';
        logout();
        break;

    case '/dashboard':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin' ) {
            header('Location: /login');
            exit;
        }
        require __DIR__ . '/../app/views/dashboard.php';
        break;
    case '/home':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
            header('Location: /login');
            exit;
        }
        require __DIR__ . '/../app/controllers/HomeController.php';
        break;
    
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
