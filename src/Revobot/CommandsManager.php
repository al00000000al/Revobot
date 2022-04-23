<?php

namespace Revobot;

class CommandsManager
{
    public const CMD_REGEX = '/^\/([\wА-я_]+?)(\s|$)(.{0,3000})/sum';

    private static function commands($input): array
    {
        return [
            'alive' => static fn() => (new \Revobot\Commands\AliveCmd($input))->exec(),
            'bash' => static fn() => (new \Revobot\Commands\BashCmd($input))->exec(),
            'calc' => static fn() => (new \Revobot\Commands\CalcCmd($input))->exec(),
            'help' => static fn() => (new \Revobot\Commands\HelpCmd($input))->exec(),
            'infa' => static fn() => (new \Revobot\Commands\InfaCmd($input))->exec(),
            'pukvy' => static fn() => (new \Revobot\Commands\PukvyCmd($input))->exec(),
            'rand' => static fn() => (new \Revobot\Commands\RandCmd($input))->exec(),
            'when' => static fn() => (new \Revobot\Commands\WhenCmd($input))->exec(),
            'yn' => static fn() => (new \Revobot\Commands\YnCmd($input))->exec(),
        ];
    }


    public static function process($message)
    {
        list($command, $input) = self::extract($message);
        $commands = self::commands($input);
        if(in_array($command, $commands, true)){
            return $commands[$command]();
        }
        return null;
    }

    private static function extract($message): array
    {
        preg_match_all(self::CMD_REGEX, $message, $matches, PREG_SET_ORDER, 0);
        $command = mb_strtolower($matches[0][1]);
        $text = $matches[0][3];
        return ['cmd' => $command, 'text' => $text];
    }
}
