<?php
class Database {
    private static $dsn = 'mysql:host=localhost;port=3307;dbname=job';
    private static $username = 'root';
    private static $password = 'Sainath@123';
    private static $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    private static $db;

    private function __construct() {}

    public static function getDB () {
        if (!isset(self::$db)) {
            try {
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
                self::$db = new PDO(self::$dsn,
                                    self::$username,
                                    self::$password,
                                    self::$options);
            } catch (PDOException $e) {
                $error = $e->getMessage();
                include('view/error.php');
                exit();
            }
        }
        return self::$db;
    }
}
?>