<?php
require_once __DIR__ . '/../config.php';

global $config;

$bot_token = $config['tg_key'];

if (!isset($_GET['url'], $_GET['chat_id'], $_GET['access_code'])) {
    die(200);
}
$url = $_GET['url'];
$chat_id = $_GET['chat_id'];
$file_name = md5($url) . '.mp4';
$file_name_lock = md5($url) . '.lock';
if ($_GET['access_code'] !== $config['dl_video_api_key']) {
    die(403);
}

if (file_exists($file_name_lock)) {
    die(200);
}

$response_message_json  = json_decode(sendTg($chat_id, "Скачиваем видео"), true);
if (!isset($response_message_json['result']['message_id'])) {
    die(403);
}
$message_id = $response_message_json['result']['message_id'];
if (file_exists($file_name)) {
    deleteMsgTg($chat_id, $message_id);
    sendVideoTg($chat_id, $file_name);
    die(200);
}

file_put_contents($file_name_lock, '1');
$response = json_decode(getVideoInst($url), true);



if (!isset($response['data']['urls'][0])) {
    deleteMsgTg($chat_id, $message_id);
    sendTg($chat_id, "Не удалось скачать");
    @unlink($file_name_lock);
    @unlink($file_name);
}

$video_url = $response['data']['urls'][0];

saveVideo($video_url, $file_name);
sendVideoTg($chat_id, $file_name);
deleteMsgTg($chat_id, $message_id);
@unlink($file_name_lock);
@unlink($file_name);

function sendTg($chat_id, $message)
{
    global $config;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $config['tg_key'] . '/sendMessage');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'chat_id' => $chat_id,
        'text' => $message,
    ]);
    $response_message = curl_exec($ch);
    curl_close($ch);
    return $response_message;
}

function sendVideoTg($chat_id, $file_name)
{
    global $config;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $config['tg_key'] . '/sendVideo');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'chat_id' => $chat_id,
        'video' => new CURLFile($file_name),
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function deleteMsgTg($chat_id, $message_id)
{
    global $config;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $config['tg_key'] . '/deleteMessage');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function getVideoInst($url)
{

    $jsonData = json_encode([
        'url' => $url
    ]);

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, 'https://savein.io/api/fetchv2');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36   (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.  36');
    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

function saveVideo($video_url, $file_name)
{
    $fp = fopen($file_name, 'w');
    $ch = curl_init($video_url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}
