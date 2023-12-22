<?php

use Revobot\Config;

require_once('../config.php');

$botToken = Config::get('tg_key');
while (true) {
    $telegramUserId = readline('Enter user id:');
    if (empty($telegramUserId)) {
        exit;
    }
    $apiUrl = "https://api.telegram.org/bot$botToken/getChatMember?chat_id=$telegramUserId&user_id=$telegramUserId";

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data["ok"]) {
        $username = $data["result"]["user"]["username"];
        echo "Username: $username";
    } else {
        echo "Error: " . $data["description"];
    }
}
