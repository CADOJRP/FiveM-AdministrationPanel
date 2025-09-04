<?php
require_once 'config.php';

class Database {
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . Config::get('mysql_host') . ';dbname=' . Config::get('mysql_db'),
                    Config::get('mysql_user'),
                    Config::get('mysql_pass'),
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                die('Database error.');
            }
        }
        return self::$pdo;
    }
}

function dbquery($query, $params = [], $fetch = false) {
    $pdo = Database::connect();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $fetch ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->rowCount();
}
?>
