<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $host = 'db';
            $db = 'stock_db';
            $user = 'root';
            $pass = 'root';

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$pdo;
    }
}
