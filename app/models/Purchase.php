<?php
require_once __DIR__ . '/../core/Database.php';

class Purchase {
    public static function all($limit = 10, $offset = 0) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM purchases ORDER BY id DESC LIMIT :limit OFFSET :offset");
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
}