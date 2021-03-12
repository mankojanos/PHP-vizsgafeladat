<?php

/**
 * Class PDOconnection
 * Singleton database connection
 */
class PDOconnection {
    private static $dbhost = 'localhost';
    private static $dbname = 'MVCForum';
    private static $dbusername = 'root';
    private static $dbpassword = '';
    private static $db_singleton = null;

    public static function getCopy() {
        if(self::$db_singleton == null) {
            self::$db_singleton = new PDO("mysql:host=" . self::$dbhost . "; dbname=" . self::$dbname . ";charset=utf8", self::$dbusername, self::$dbpassword, [
                //TODO: just for development:
                PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$db_singleton;
    }
}
