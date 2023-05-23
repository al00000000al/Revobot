<?php

namespace Revobot\Services;

use Revobot\Config;

class CrystalPay {

    public static function createInvoice() {
        $params = json_encode([
            "auth_login" => Config::get('crystalpay_login'),
            "auth_secret" => Config::get('crystalpay_secret_key'),
            "amount" => "1",
            "amount_currency" => "USD",
            "type" => "topup",
            "description" => "Пополнение счета в боте",
            "redirect_url" => "https://t.me/Therevoluciabot",
            "callback_url" => Config::get('crystalpay_callback'),
            "lifetime" => 120
        ]);
    }

    public static function callbackCheck(array $content) {
        $id = $content["id"];
        $signature = $content["signature"];
        $salt = Config::get('crystalpay_salt');
        $hash = sha1($id.":".$salt);

        if (!hash_equals($hash, $signature)) { //Безопасное сравнение подписи callback
            exit("Invalid signature!");
        }


    }
}
