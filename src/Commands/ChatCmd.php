<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;

class ChatCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['chat', 'чат'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Случайный чат';

    /**
     * @param string $input
     * @param Revobot $bot
     */
    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        $chat = '';
        if ($this->bot->provider === 'tg') {
            $this->bot->sendTypeStatusTg();
            $tries = 0;

            while ($tries < 10) {
                $chat = (string)$this->getChat();
                if (!empty($chat)) {
                    $result = $this->verifyLink($chat);
                    if ($result) {
                        list($link, $chatTitle, $chatDescription, $chatImage) = $result;
                        if (isset($chatImage)) {
                            Tg::sendPhoto(chatId(), (string)$chatImage, implode("\n", [$link, $chatTitle, $chatDescription]));
                            return "";
                        } else {
                            return implode("\n", [$link, $chatTitle, $chatDescription]);
                        }
                    }
                }
                $tries++;
            }
        }
        return $chat;
    }

    private function getChat()
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
            return null;
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
            return [$link, $chatTitle, $chatDescription, $chatImage];
        }
    }
}
