<?php
require_once('config.php');

class Database
{
    private static $connection;

    private function __construct()
    {
        try {
            self::$connection = new \PDO(Settings::get('db_system') . ':host=' . Settings::get('db_hostname') . ';dbname=' . Settings::get('db_database') . ';charset=' . Settings::get('db_charset'),
                Settings::get('db_user'),
                Settings::get('db_password')
            );
        } catch (\PDOException $ex) {
            print($ex->getMessage());
        }
    }

    public static function connect()
    {
        if (isset(self::$connection)) {
            return self::$connection;
        } else {
            new self();
            if (isset(self::$connection)) {
                return self::$connection;
            } else {
                return false;
            }
        }
    }
}