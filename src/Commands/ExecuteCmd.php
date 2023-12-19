<?php

namespace Revobot\Commands;

use KLua\KLua;
use KLua\KLuaConfig;
use KLua\KLuaException;
use Revobot\CommandsManager;
use Revobot\Revobot;

class ExecuteCmd extends BaseCmd
{
    const KEYS = ['execute'];
    const IS_ENABLED = true;
    private Revobot $bot;
    const HELP_DESCRIPTION = 'lua script';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/execute run lua script');
    }

    private function process($command_name, $input = '')
    {
        if ($command_name === 'execute') {
            return 'null';
        }

        $bot = clone $this->bot;
        $bot->setMessage('/' . (string)$command_name . ' ' . $input);
        $result =  CommandsManager::process($bot);
        // unset($bot);
        return (string)$result;
    }

    public function exec(): string
    {
        if (empty($this->input)) {

            return $this->description;
        }

        $code = $this->input;

        KLua::registerFunction1('r_process_func_without_args', function ($command_name) {
            return self::process($command_name);
        });

        KLua::registerFunction2('r_process_func_with_args', function ($command_name, $input) {
            return self::process($command_name, $input);
        });


        try {
            KLua::eval('
    local Api = {}
    local Api_mt = {
        __index = function(table, key)
            return function(...)
                local numArgs = select("#", ...)
                local result
                if numArgs > 1 then
                    return "Error"
                end
                if numArgs == 1 then
                    result = r_process_func_with_args(key, ...)
                else
                    result = r_process_func_without_args(key)
                end
                print("Вызов метода:", key)
                return result
            end
        end
    }

setmetatable(Api, Api_mt)

local function run()
    ' . $code . '
end

execute_result = run()
');
        } catch (KLuaException $e) {
            return  "eval error: " . $e->getMessage() . "\n";
        }

        $result = KLua::getVar('execute_result');

        if (is_array($result)) {
            return (string)print_r($result, true);
        }
        return (string)$result;
    }
}
