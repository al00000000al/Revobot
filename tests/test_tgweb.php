<?php

use Revobot\Config;
use Revobot\Util\Curl;

require '../vendor/autoload.php';
require '../config.php';

$chat = '';

$tries = 0;

while ($tries < 10) {
    $chat = (string)getChat();
    if (!empty($chat)) {
        $result = (string)verifyLink($chat);
        if (strlen($result) > 0) {
            echo $result;
            exit;
        }
    }
    $tries++;
}


return $chat;
function verifyLink($link)
{
    $html = Curl::get(trim($link));
    $tmp_path = Config::get('base_path') . '/test_tmp.txt';
    file_put_contents($tmp_path, $html);
    $re = '/<meta property="og:title" content="([^"]+)"/';
    preg_match($re, $html, $matches, PREG_OFFSET_CAPTURE, 0);
    $chatTitle = "Join group chat on Telegram";
    if (isset($matches[1][0])) {
        $chatTitle = $matches[1][0];
    }
    if ($chatTitle == "Join group chat on Telegram") {
        return '';
    } else {
        $re = '/<meta property="og:description" content="([^"]+)"/';
        preg_match($re, $html, $matches, PREG_OFFSET_CAPTURE, 0);
        $chatDescription = '';
        if (isset($matches[1][0])) {
            $chatDescription = $matches[1][0];
        }
        $re = '/<meta property="og:image" content="([^"]+)"/';
        preg_match($re, $html, $matches, PREG_OFFSET_CAPTURE, 0);
        $chatImage = '';
        if (isset($matches[1][0])) {
            $chatImage = $matches[1][0];
        }
        return implode(' ', [$link, $chatTitle, $chatDescription, $chatImage]);
    }
}


function getChat()
{
    $file_path = Config::get('base_path') . '/channels.json';
    if (!file_exists($file_path)) {
        $data = file_get_contents('https://web.archive.org/cdx/search/cdx?url=https://t.me/joinchat/*&output=text&fl=original&collapse=urlkey');
        file_put_contents($file_path, $data);
    }
    $f = fopen($file_path, "r");

    if (!$f) {
        return '';
    }

    $selectedLine = '';
    $lineNumber = 0;

    while (!feof($f)) {
        $line = fgets($f);
        $lineNumber++;
        if (rand(1, $lineNumber) == 1) {
            $selectedLine = $line;
        }
    }

    fclose($f);
    return $selectedLine;
}
