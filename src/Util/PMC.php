<?php

namespace Revobot\Util;

class PMC
{
    private static $instance = null;
    private $memcache;

    private function __construct()
    {
        $this->memcache = new \Memcache();
        $this->memcache->connect('127.0.0.1', 11209);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new PMC();
        }
        return self::$instance->memcache;
    }

    public static function get($key)
    {
        return self::getInstance()->get($key);
    }

    public static function set($key, $value, $expiration = 0)
    {
        return self::getInstance()->set($key, $value, 0, $expiration);
    }

    public static function delete($key)
    {
        return self::getInstance()->delete($key);
    }

    public static function getVersion()
    {
        return self::getInstance()->getVersion();
    }
}
