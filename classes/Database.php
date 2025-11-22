<?php
class Database {

    private static $db;

    public static function getConnection() {
        if (self::$db) {
            // Er is al een verbinding
            return self::$db;
        } else {
            try {
                // Maak de verbinding aan
                self::$db = new PDO('mysql:host=localhost;dbname=webshop;charset=utf8', 'root', '');
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$db;
            } catch (PDOException $e) {
                die("Databaseverbinding mislukt: " . $e->getMessage());
            }
        }
    }
}
