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
                $response = (new \Revobot\Commands\AliveCmd($input, $bot))->exec();
                break;
            case 'bash' :
                $response = (new \Revobot\Commands\BashCmd($input))->exec();
                break;
            case 'calc':
                $response = (new \Revobot\Commands\CalcCmd($input))->exec();
                break;
            case 'help' :
                $response = (new \Revobot\Commands\HelpCmd($input))->exec();
                break;
            case 'infa' :
                $response = (new \Revobot\Commands\InfaCmd($input))->exec();
                break;
            case 'pukvy' :
                $response = (new \Revobot\Commands\PukvyCmd($input))->exec();
                break;
            case 'rand' :
                $response = (new \Revobot\Commands\RandCmd($input))->exec();
                break;
            case 'when' :
                $response = (new \Revobot\Commands\WhenCmd($input))->exec();
                break;
            case 'yn' :
                $response = (new \Revobot\Commands\YnCmd($input))->exec();
                break;
            case 'chat':
                $response = (new \Revobot\Commands\ChatCmd($input, $bot))->exec();
                break;
            case 'time':
                $response = (new \Revobot\Commands\TimeCmd($input))->exec();
                break;
            case 'balance':
                $response = (new \Revobot\Commands\BalanceCmd($input, $bot))->exec();
                break;
            case 'stat':
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
