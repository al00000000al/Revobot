<?php

namespace Revobot\Commands;

use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;

class RedditCmd extends BaseCmd
{
    const KEYS = ['reddit'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = '/reddit';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/reddit Получить случайный мем из reddit');
    }

    public function exec(): string
    {
        $memeUrl = $this->getMeme();
        if (!$memeUrl) {
            return 'Не удалось найти мем.';
        }

        if (provider() === 'tg') {
            Tg::sendPhoto(chatId(), $memeUrl);
        } else {
            return $memeUrl;
        }
        return '';
    }

    private function getMeme(): string
    {
        $redditApiUrl = "https://www.reddit.com/r/memes/random/.json";

        $result = Curl::get($redditApiUrl);
        if ($result === FALSE) {
            return '';
        }
        $response = (array)json_decode($result, true);
        return $response[0]['data']['children'][0]['data']['url'] ?? '';
    }
}
