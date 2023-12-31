<?php

namespace Revobot;

class Response
{
    public static function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        // http_response_code($statusCode);
        echo json_encode($data);
    }

    public static function text($text, $statusCode = 200)
    {
        header('Content-Type: text/plain');
        // http_response_code($statusCode);
        echo $text;
    }

    public static function notFound($message = 'Not Found')
    {
        header('HTTP/1.0 404 Not Found');
        echo $message;
    }

    public static function html($htmlContent, $statusCode = 200)
    {
        header('Content-Type: text/html');
        // http_response_code($statusCode);
        echo $htmlContent;
    }
}
