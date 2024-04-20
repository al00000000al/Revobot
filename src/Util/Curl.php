<?php

namespace Revobot\Util;

class Curl
{

    /**
     * @param string $url
     * @param mixed $data
     * @param mixed[] $options
     */
    public static function post($url, $data, array $options = [])
    {
        if (!self::isValidUrl($url)) {
            if (isset($options['need_json_decode'])) {
                return ['error' => "Невалидный URL"];
            }
            return "Невалидный URL";
        }

        if (self::isLocalUrl($url)) {
            if (isset($options['need_json_decode'])) {
                return ['error' => "Локальный URL"];
            }
            return "Локальный URL";
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        if (!empty($options['headers'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER,  (array)$options['headers']);
        }
        $res = curl_exec($ch);
        curl_close($ch);
        if (isset($options['need_json_decode'])) {
            return json_decode($res, true);
        }
        return $res;
    }

    public static function get($url)
    {
        if (!self::isValidUrl($url) || self::isLocalUrl($url)) {
            return "Невалидный или локальный URL";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); //timeout in seconds
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    // Функция для получения случайного прокси с pubproxy.com
    public static function getRandomProxy()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://pubproxy.com/api/proxy?https=true&type=http&post=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['data'][0]['ipPort'])) {
                return $data['data'][0]['ipPort'];
            }
        }

        return null;
    }

    private static function isValidUrl($url)
    {
        return preg_match('/^https?:\/\/[a-zA-Z0-9.-А-Яа-яёЁ\s\:\/\\\\\%&\*\(\)_\?\-=\+]+$/', $url);
    }

    private static function isLocalUrl($url)
    {
        global $NoCheck;
        if ((bool)$NoCheck) {
            return false;
        }
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return true;
        }

        $ips = gethostbynamel($host);
        if ($ips === false) {
            return true;
        }
        foreach ($ips as $ip) {
            if (self::isLocalIp($ip)) {
                return true;
            }
        }
        return false;
    }

    // Функция проверки, является ли IP локальным
    private static function isLocalIp($ip)
    {
        // Проверка на IPv4 локальные диапазоны
        if (self::isLocalIpv4($ip)) {
            return true;
        }

        // Проверка на IPv6 локальные диапазоны
        if (self::isLocalIpv6($ip)) {
            return true;
        }

        return false;
    }

    private static function isLocalIpv4($ip)
    {
        // Диапазоны локальных IPv4 адресов
        $ipv4Patterns = [
            '/^127\./',                   // 127.0.0.0/8 - Loopback
            '/^10\./',                     // 10.0.0.0/8 - Private-network
            '/^172\.(1[6-9]|2[0-9]|3[0-1])\./', // 172.16.0.0/12 - Private-network
            '/^192\.168\./',               // 192.168.0.0/16 - Private-network
            '/^100\.(6[4-9]|[7-9][0-9]|1[0-1][0-9]|12[0-7])\./' // 100.64.0.0/10 - CGNAT
        ];

        foreach ($ipv4Patterns as $pattern) {
            if (preg_match($pattern, $ip)) {
                return true;
            }
        }

        return false;
    }

    private static function isLocalIpv6($ip)
    {
        // Диапазоны локальных IPv6 адресов
        if (preg_match('/^(::1$|fc00::|fd00::)/', $ip)) { // Loopback и Unique local address
            return true;
        }

        return false;
    }
}
