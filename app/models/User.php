<?php
require_once __DIR__ . '/../core/Database.php';

class User {
    public static function register($username, $email, $password) {
        $pdo = Database::connect();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed]);
    }
    public static function findByEmail($email) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    public static function all($limit = 10, $offset = 0) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC LIMIT :limit OFFSET :offset");
    
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function count() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public static function find($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function store($data) {
        $pdo = Database::connect();
        // Validate required fields
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            throw new PDOException("Missing required fields");
        }

        // Validate name
        if (empty(trim($data['username']))) {
            throw new PDOException("User name cannot be empty");
        }

        // Validate email
        if (empty(trim($data['email']))) {
            throw new PDOException("Email cannot be empty");
        }

        // Validate email
        if (empty(trim($data['password']))) {
            throw new PDOException("Password cannot be empty");
        }

        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetchColumn() > 0) {
            throw new PDOException("Email already exists");
        }

        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$data['username']]);
        if ($stmt->fetchColumn() > 0) {
            throw new PDOException("User Name already exists");
        }

        try {
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$data['username'], $data['email'], $hashed, $data['role']]);
        } catch (PDOException $e) {
            error_log("User store error: " . $e->getMessage());
            throw $e;
        }

        
    }

    public static function update($id, $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?,role = ? WHERE id = ?");
        return $stmt->execute([$data['username'], $data['email'], $data['role'], $id]);
    }

    public static function delete($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
