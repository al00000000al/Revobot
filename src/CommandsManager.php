<?php

namespace Revobot;

class CommandsManager
{
    public const CMD_REGEX = '/^\/([A-Za-z]+?)(\s|$)(.{0,3000})/sum';


    /**
     * @param Revobot $bot
     * @return string
     */
    public static function process(Revobot $bot): string
    {
        $message = $bot->message;
        $message = str_replace('@Therevoluciabot','', $message);

        list($command, $input) = self::extract($message);


        switch ($command) {
            case 'alive' :
            case 'алив':
                $response = (new \Revobot\Commands\AliveCmd($input, $bot))->exec();
                break;
            case 'bash' :
            case 'баш':
                $response = (new \Revobot\Commands\BashCmd($input))->exec();
                break;
            case 'calc':
                $response = (new \Revobot\Commands\CalcCmd($input))->exec();
                break;
            case 'help' :
            case 'хэлп':
            case 'хлеп':
            case 'помощь':
                $response = (new \Revobot\Commands\HelpCmd($input))->exec();
                break;
            case 'infa' :
            case 'инфа' :
                $response = (new \Revobot\Commands\InfaCmd($input))->exec();
                break;
            case 'pukvy' :
            case 'пуквы' :
                $response = (new \Revobot\Commands\PukvyCmd($input))->exec();
                break;
            case 'rand' :
            case 'ранд' :
                $response = (new \Revobot\Commands\RandCmd($input))->exec();
                break;
            case 'when' :
            case 'когда' :
                $response = (new \Revobot\Commands\WhenCmd($input))->exec();
                break;
            case 'yn' :
            case 'дн' :
                $response = (new \Revobot\Commands\YnCmd($input))->exec();
                break;
            case 'chat':
            case 'чат':
            case 'чят':
                $response = (new \Revobot\Commands\ChatCmd($input, $bot))->exec();
                break;
            case 'time':
            case 'время':
                $response = (new \Revobot\Commands\TimeCmd($input))->exec();
                break;
            case 'balance':
            case 'баланс':
                $response = (new \Revobot\Commands\BalanceCmd($input, $bot))->exec();
                break;
            case 'stat':
            case 'стат':
                $response = (new \Revobot\Commands\StatCmd($input, $bot))->exec();
                break;
            default:
                $response = '';
        }

        dbg_echo('cmd:'.$command.',inp:'.$input.',response: ' . $response."\n");

        return $response;
    }

    /**
     * @param string $message
     * @return array
     */
    private static function extract(string $message): array
    {
        preg_match(self::CMD_REGEX, $message, $matches, PREG_OFFSET_CAPTURE);
        $command = mb_strtolower($matches[1][0]);
        $text = (string)$matches[3][0];
       // dbg_echo('cmd='.$command.' text='.$text."\n");
        return [$command, $text];
    }
}
