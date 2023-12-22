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
        if (!self::isValidUrl($url) || self::isLocalUrl($url)) {
            return "Невалидный или локальный URL";
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
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
        // Проверка валидности URL
        return filter_var($url, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\/.+$/', $url);
    }

    private static function isLocalUrl($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return false;
        }

        $ip = gethostbyname($host);
        return self::isLocalIp($ip);
    }

    private static function isLocalIp($ip)
    {
        $longIp = ip2long($ip);
        $min = ip2long("100.64.0.0");
        $max = ip2long("100.127.255.255");

        return (
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE |  FILTER_FLAG_NO_RES_RANGE) === false ||
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE |  FILTER_FLAG_NO_RES_RANGE) === false ||
            ($longIp >= $min && $longIp <= $max) // Дополнительная проверка для диапазона 100.64.0.0/10
        );
    }
}
