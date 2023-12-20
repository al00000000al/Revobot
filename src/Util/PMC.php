<?php

namespace Revobot\Util;

class PMC
{
    /** @var PMC|null */
    private static $instance = null;

    /** @var \McMemcache */
    private $memcache;

    private function __construct()
    {
        $this->memcache = new \McMemcache();
        $this->memcache->addServer('127.0.0.1', 11209);
    }

    /**
     * @return \McMemcache
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new PMC();
        }
        return self::$instance->memcache;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return self::getInstance()->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expiration
     * @return bool
     */
    public static function set($key, $value, $expiration = 0)
    {
        return self::getInstance()->set($key, $value, 0, $expiration);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function delete($key)
    {
        return self::getInstance()->delete($key);
    }

    /**
     * @return mixed
     */
    public static function getVersion()
    {
        return self::getInstance()->getVersion();
    }
}
