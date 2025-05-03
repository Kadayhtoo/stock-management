<?php
require_once __DIR__ . '/../core/Database.php';

class Product {
    public static function all($limit = 10, $offset = 0) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
    
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function count() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public static function find($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function store($data) {
        $pdo = Database::connect();

        // Validate required fields
        if (!isset($data['name']) || !isset($data['price']) || !isset($data['quantity_available'])) {
            throw new PDOException("Missing required fields");
        }

        // Validate name
        if (empty(trim($data['name']))) {
            throw new PDOException("Product name cannot be empty");
        }

        // Validate price
        if (!is_numeric($data['price']) || $data['price'] < 0) {
            throw new PDOException("Price must be a positive number");
        }

        // Validate quantity
        if (!is_numeric($data['quantity_available']) || $data['quantity_available'] < 0) {
            throw new PDOException("Quantity must be a positive number");
        }

        // Check for duplicate product name
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = ?");
        $stmt->execute([$data['name']]);
        if ($stmt->fetchColumn() > 0) {
            throw new PDOException("A product with this name already exists");
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)");
            return $stmt->execute([
                trim($data['name']),
                (float)$data['price'],
                (int)$data['quantity_available']
            ]);
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Product store error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function update($id, $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, quantity_available = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['price'], $data['quantity_available'], $id]);
    }

    public static function delete($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
