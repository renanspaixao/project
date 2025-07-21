<?php

namespace App\Models;

use Core\Database;
use PDO;
use PDOException;

class Model extends Database
{
    private static $query;

    private static function prepExec($prep, $params)
    {
        try {
            self::$query = self::getConnection()->prepare($prep);
            $success = self::$query->execute($params);

            if (!$success) {
                throw new PDOException("Erro ao executar query.");
            }

            return true;
        } catch (PDOException $e) {
            http_response_code(400);
            echo "Erro: " . $e->getMessage();
            echo "<br>Query: $prep";
            echo "<br>Parâmetros: ";
            print_r($params);
            return false;
        }
    }

    public static function insert($table, $statement, $params)
    {
        $pdo = self::getConnection();
        $prep = "INSERT INTO $table SET $statement";

        try {
            $stmt = $pdo->prepare($prep);
            $success = $stmt->execute($params);

            if (!$success) {
                throw new PDOException("Erro ao executar INSERT.");
            }

            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            http_response_code(400);
            echo "Erro: " . $e->getMessage();
            echo "<br>Query: $prep";
            echo "<br>Parâmetros: ";
            print_r($params);
            return false;
        }
    }

    public static function update($table, $statement, $params, $where)
    {
        $prep = "UPDATE $table SET $statement $where";
        return self::prepExec($prep, $params);
    }

    public static function delete($table, $where, $params)
    {
        $prep = "DELETE FROM $table $where";
        return self::prepExec($prep, $params);
    }

    public static function select($fields, $table, $where = '', $params = [])
    {
        $prep = "SELECT $fields FROM $table $where";
        self::prepExec($prep, $params);
        return self::$query;
    }

    
}
