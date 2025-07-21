<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $conn;

    public static function getConnection() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
