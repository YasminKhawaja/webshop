<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host = 'sql108.infinityfree.com';      // MySQL Host Name
            $db   = 'if0_40824519_glowcaredb';      // MySQL DB Name
            $user = 'if0_40824519';                 // MySQL User Name
            $pass = 'IVQzsKM37I3';       // zelfde wachtwoord als control panel

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {
                self::$connection = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die("Databaseverbinding mislukt: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
