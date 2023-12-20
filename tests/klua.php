<?php

use Revobot\Config;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lang.php';

use KLua\KLua;
use KLua\KLuaConfig;
use KLua\KLuaException;
use Revobot\Commands\Custom\Types;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

if (KPHP_COMPILER_VERSION) {
    KLua::loadFFI();
}


// Note: it's not advised to set the memory limit that is too low.
// Lua may not be prepared to get a null pointer during it's
// code parsing and other low-level routines.
// The limit we're using here is really low, you should
// use something bigger in the real-world use cases.
const LUA_MAX_MEM_BYTES = 1024 * 30;

$lua_config = new KLuaConfig();
$lua_config->preload_stdlib = ['base', 'string', 'math', 'utf8'];
$lua_config->alloc_hook = function ($alloc_size) {
    // To learn how much memory Lua is using right now
    // we need to use KLua::getStats().
    $stats = KLua::getStats();
    $mem_free = LUA_MAX_MEM_BYTES - $stats->mem_usage;
    return $mem_free >= $alloc_size;
};
KLua::init($lua_config);

KLua::registerFunction1('r_process_func', function ($command_name) {
    $custom_cmd = PMC::get('custom_cmd_' . sha1($command_name));
    if ($custom_cmd && isset($custom_cmd['command_type'])) {
        if ((int)($custom_cmd['command_type']) == Types::TYPE_TEXT) {
            $string = (string)$custom_cmd['args'][0];
            // Tg::sendMessage(-1001457620038, $string);
            print($string);
            return $string;
        }
    }
    return '';
});

KLua::registerFunction1('tg_print', 'tg_print');

/** @kphp-required */
function tg_print($s)
{
    return Tg::sendMessage(6012754858, (string) $s);
}

try {
    // This eval fails during the execution.
    // Registered PHP functions are now accessible via Lua code.
    KLua::eval('
    -- Глобальная переменная для накопления результатов
accumulatedResults = ""
    local Api = {}
    local Api_mt = {
        __index = function(table, key)
            -- Функция, которая будет вызываться, если метод не найден
            return function(...)
                local result = r_process_func(key)
                if result and result ~= "" and result ~= nil then
                    -- Добавление результата в глобальную переменную с новой строки
                    accumulatedResults = accumulatedResults .. result .. "\n"
                end
                print("Вызов метода:", key)
            end
        end
    }
    function Api.funcName1()
    print("Function 1 called")
end

function Api.dec()
    print(loadfile("/etc/passwd"))
end

function Api.funcName2()
    print("Function 2 called")
end
setmetatable(Api, Api_mt)

Api.funcName1()  -- Вызовет существующий метод
Api.dec()  -- Вызовет функцию из __index метатаблицы
Api[\'создать\']()
Api.an()

if accumulatedResults and accumulatedResults ~= "" and accumulatedResults ~= nil then
    tg_print(accumulatedResults)
end
');
} catch (KLuaException $e) {
    // We can handle the error and continue normally.
    Tg::sendMessage(6012754858, "eval error: " . $e->getMessage() . "\n");
}
