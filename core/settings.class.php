<?php
abstract class Settings
{
    private static $settings = array();

    public static function get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : false;
    }

    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
