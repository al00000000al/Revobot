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
        if (0) {
            $this->memcache = new \McMemcache();
        }
        #ifndef KPHP
        $this->memcache = new \Memcache();
        #endif

        $this->memcache->addServer('127.0.0.1', 11209);
    }

    /**
     *
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
     * @param int $flags
     * @param int $expiration
     * @return bool
     */
    public static function set($key, $value, $flags = 0, $expiration = 0)
    {
        return self::getInstance()->set($key, $value, $flags, $expiration);
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

    /**
     * Увеличивает числовое значение ключа на указанное число.
     * Если ключ не существует, он будет создан с начальным значением $increment.
     *
     * @param string $key
     * @param int $increment
     * @return mixed
     */
    public static function increment($key, $increment = 1)
    {
        $memcache = self::getInstance();

        // Попытка инкремента
        $result = $memcache->increment($key, $increment);

        // Если ключ не существует, создаем его с начальным значением $increment
        if ($result === false) {
            $memcache->set($key, $increment);
            return $increment;
        }

        return $result;
    }
}
