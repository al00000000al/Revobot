<?php

namespace Revobot;

use Revobot\Commands\Custom\CustomCmd;

class CommandsManagerBase
{
    public const CMD_REGEX = '/^\/([A-Za-zа-яА-ЯёЁ\._\-]+?)(\s|$|@Therevoluciabot\s?)(.{0,3000})/sum';

    public const COMMANDS = [];


    /**
     * @param Revobot $bot
     * @return string
     */
    public static function process(Revobot $bot): string
    {
        $message = $bot->message;

        list($command, $input) = static::extract($message);

        if(0) {
        dbg_echo($command . "\n");
       }

        $result = CommandsManager::run($bot, $command, $input);
        if (empty($result)) {
            return (new CustomCmd($bot))->run();
        } else {
            return $result;
        }
    }

    /**
     * @param Revobot $bot
     * @param string $command
     * @param string $input
     * @return string
     */
    public static function run(Revobot $bot, string $command, string $input): string
    {
        return '';
    }

    /**
     * @param string $message
     * @return array
     */
    public static function extract(string $message): array
    {
        preg_match(self::CMD_REGEX, $message, $matches, PREG_OFFSET_CAPTURE);
        $command = mb_strtolower($matches[1][0], 'UTF-8');
        $text = $matches[3][0] . "";
        return [$command, $text];
    }
}
