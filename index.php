<?php
// kphp -F --composer-root=$(pwd) --composer-no-dev index.php
//  ./build/server -H 8088 --use-utf8 --workers-num 5  -q  &

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';


$url = $_SERVER['PHP_SELF'];
if($url === '/tg_bot' || $url === '/vk_bot'){
    $data = file_get_contents('php://input');

    $data_arr = json_decode($data, true);
    if(!$data_arr) {
        return;
    }


    $pmc = new Memcache;
    $pmc->addServer('127.0.0.1', 11209);

    if($url === '/tg_bot' && isset($data_arr['message']['chat']['id'])) {
        $chat_id = $data_arr['message']['chat']['id'];
        $bot = new Revobot\Revobot('tg');
        $bot->setChatId((int)$chat_id);
        $bot->setTgKey(TG_KEY);
        $bot->setPmc($pmc);

        if(isset($data_arr['message']['text'])) {
            $bot->setMessage((string)$data_arr['message']['text']);
            $bot->setRawData($data_arr['message']);
            $bot->run();
        }
    }
}


