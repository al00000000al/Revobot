<?php
// kphp -F --composer-root=$(pwd) --composer-no-dev index.php
//  ./build/server -H 8088 --use-utf8 --workers-num 5  -q  &

use KLua\KLua;
use KLua\KLuaConfig;
use Revobot\Handlers\JobWorkerHandler;
use Revobot\Router;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lang.php';

if (KPHP_COMPILER_VERSION) {
    KLua::loadFFI();
}

const LUA_MAX_MEM_BYTES = 1024 * 1024;

$lua_config = new KLuaConfig();
$lua_config->preload_stdlib = ['base', 'string', 'math', 'utf8'];
$lua_config->alloc_hook = function ($alloc_size) {
    $stats = KLua::getStats();
    $mem_free = LUA_MAX_MEM_BYTES - $stats->mem_usage;
    return $mem_free >= $alloc_size;
};
KLua::init($lua_config);

if (PHP_SAPI !== 'cli' && isset($_SERVER["JOB_ID"])) {
    (new JobWorkerHandler)->handle('');
} else {
    $router = new Router();
    $url = $_SERVER['PHP_SELF'];
    $router->handleRequest((string)$url);
}
