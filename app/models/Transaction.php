<?php
require_once __DIR__ . '/../core/Database.php';

class Transaction {
    public static function all($limit = 10, $offset = 0) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT 
                transactions.*,
                products.name AS product_name,
                products.price AS product_price
            FROM transactions
            JOIN products ON transactions.product_id = products.id
        LIMIT :limit OFFSET :offset");
    
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM transactions");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public static function findByPurchaseId($id){
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
        SELECT 
            transactions.*,
            products.name AS product_name,
            products.price AS product_price
        FROM transactions
        JOIN products ON transactions.product_id = products.id
        WHERE transactions.purchase_id = ?
    ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function store($data) {
        $pdo = Database::connect();
    
        // Decode payload (expects JSON string under 'payload' key)
        $payload = json_decode($data['payload'], true);
    
        if (!isset($payload['items']) || !is_array($payload['items'])) {
            return false;
        }
    
        $userId = $_SESSION['user']['id'];
        $totalPurchasePrice = 0;
        $itemsToProcess = [];
    
        try {
            // Pre-validate and calculate total price
            foreach ($payload['items'] as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
    
                $stmt = $pdo->prepare("SELECT price, quantity_available FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if (!$product || $product['quantity_available'] < $quantity) {
                    throw new Exception("Insufficient stock or product not found for product ID $productId");
                }
    
                $lineTotal = $product['price'] * $quantity;
                $totalPurchasePrice += $lineTotal;
    
                $itemsToProcess[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product['price'],
                    'line_total' => $lineTotal
                ];
            }
    
            // Start transaction
            $pdo->beginTransaction();
    
            // Step 1: Insert into `purchases` table
            $stmt = $pdo->prepare("INSERT INTO purchases (user_id, total_price) VALUES (?, ?)");
            $stmt->execute([$userId, $totalPurchasePrice]);
            $purchaseId = $pdo->lastInsertId();
    
            // Step 2: Insert individual transactions
            foreach ($itemsToProcess as $item) {
                $stmt = $pdo->prepare("INSERT INTO transactions (purchase_id, user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $purchaseId,
                    $userId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['line_total']
                ]);
    
                // Update stock
                $stmt = $pdo->prepare("UPDATE products SET quantity_available = quantity_available - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
    
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            return false;
        }
    }
    
    
}
