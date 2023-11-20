<?php

namespace Revobot\Util;

class Curl
{

    public static function post($url, $data, $options = []){

        $curl_headers = isset($options['headers']) ? (array)$options['headers'] : false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($curl_headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
        }
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    public static function get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

     // Функция для получения случайного прокси с pubproxy.com
    public static function getRandomProxy() {
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
}
