<?php

namespace Revobot\Util;

class Curl
{

    /**
     * @param $url
     * @param $data
     * @param mixed $options
     * @return mixed
     */
    public static function post($url, $data, $options = []){

        $curl_headers = (array)$options['headers'] ?? false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    public static function get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}