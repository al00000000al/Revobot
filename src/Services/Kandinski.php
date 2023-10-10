<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class Kandinski
{
    const API_PATH = 'https://api.fusionbrain.ai/web/api/v1/text2image/';

    public static function generate($query) {

        // URL, на который будет отправлен запрос
        $url = 'https://api.fusionbrain.ai/web/api/v1/text2image/run?model_id=1';

        // Данные для отправки
        $params = json_encode([
            'type' => 'GENERATE',
            'style' => 'DEFAULT',
            'width' => 1024,
            'height' => 1024,
            'generateParams' => array(
                'query' => $query
            )
        ]);

        // Формирование данных в формат multipart/form-data
        $boundary = "----WebKitFormBoundaryJOk1LBPxGRdRO02u";
        $postFields = "--{$boundary}\r\nContent-Disposition: form-data; name=\"params\"\r\nContent-Type: application/json\r\n\r\n" . ($params) . "\r\n";
        $postFields .= "--{$boundary}--";

        $response = Curl::post($url, $postFields, ['headers' => [ "Content-Type: multipart/form-data; boundary={$boundary}",
        "Content-Length: " . strlen($postFields)]]);

        $uuid = $response['uuid'];
        return (string) self::getImage($uuid);
    }

    private static function getImage($uuid) {
        $data = json_decode(Curl::get(self::API_PATH. 'status/'.$uuid), true);
        while($data['status'] === 'INITIAL') {
            $data = json_decode(Curl::get(self::API_PATH. 'status/'.$uuid), true);
            sleep(1);
        }
        if($data['status'] === 'DONE'){
            $path =  'photo'.time().'.jpg';
            self::base64_to_jpeg($data['images'][0], 'photo'.time().'.jpg');
            return $path;
        }
        return '';
    }

    private static function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');
        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($base64_string));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }
}
