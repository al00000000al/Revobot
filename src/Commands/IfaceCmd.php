<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;

class IfaceCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['iface', 'aiface'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'определить по фото лица';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/iface опледелить по фото лица');
    }

    public function exec(): string
    {
        $data = Config::getArr('ai_service');
        if (!(int)$data['is_enabled']) {
            return 'Не работает';
        }
        if (isset($this->bot->raw_data['photo'])) {
            $photo = array_last_value($this->bot->raw_data['photo']);
        } elseif (isset($this->bot->raw_data['reply_to_message']['photo'])) {
            $photo = array_last_value($this->bot->raw_data['reply_to_message']['photo']);
        } else {
            return $this->description;
        }
        $file_id = (string)$photo['file_id'];
        $file_info = Tg::getFile($file_id);
        if (isset($file_info['result']['file_path'])) {
            $file_path = (string)$file_info['result']['file_path'];
            $file_url = Tg::API_FILE_URL . Config::get('tg_key') . '/' . $file_path;
            $response = (array)Curl::post('http://127.0.0.1:5001/predict', json_encode(['url' => $file_url]), ['headers' => ['Content-Type:application/json'], 'need_json_decode' => true, 'no_check_local' => true]);
            if (isset($response['message'])) {
                return (string)$response['message'];
            }
            if (isset($response[0]['message'])) {
                return (string)$response[0]['message'];
            }
            return 'Результат: ' . $response['prediction'] . ', ' . round($response['probability'] * 100) . '%';
        }
        return 'Непонятная ошибка';
    }
}
