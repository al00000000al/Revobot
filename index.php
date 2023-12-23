<?php
// kphp -F --composer-root=$(pwd) --composer-no-dev index.php
//  ./build/server -H 8088 --use-utf8 --workers-num 5  -q  &

use KLua\KLua;
use KLua\KLuaConfig;
use Revobot\Config;
use Revobot\Util\PMC;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lang.php';

if (KPHP_COMPILER_VERSION) {
    KLua::loadFFI();
}

global $NeedProxy;

$NeedProxy = false;

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


if (PHP_SAPI !== 'cli' && isset($_SERVER["JOB_ID"])) {
    handleKphpJobWorkerRequest();
} else {
    $url = $_SERVER['PHP_SELF'];
    $route = substr($url, 1);
    list($route, $query) = explode('?', $route);
    switch ($route) {
        case 'tg_bot':
            return tgBot();
        case 'vk_bot':
            return vkBot();
        case 'sd_task':
            return sdTask();
        default:
            echo '404';
            break;
    }
    if ($url === '/tg_bot' || $url === '/vk_bot') {
    }
}


function tgBot()
{
    $data = file_get_contents('php://input');
    $data_arr = json_decode($data, true);
    if (!$data_arr) {
        return;
    }

    if (isset($data_arr['message']['chat']['id'])) {
        $chat_id = $data_arr['message']['chat']['id'];
        $bot = new Revobot\Revobot('tg');
        $bot->setChatId((int)$chat_id);
        $bot->setTgKey(Config::get('tg_key'));

        if (isset($data_arr['message']['text'])) {
            $bot->setMessage((string)$data_arr['message']['text']);
        }
        if (isset($data_arr['message']['photo'])) {
            if (isset($data_arr['message']['caption'])) {
                $bot->setMessage((string)$data_arr['message']['caption']);
            } else {
                $bot->setMessage('');
            }
        }
        if (isset($data_arr['message'])) {
            $bot->setRawData($data_arr['message']);
            $bot->run();
        }
    }
}

function vkBot()
{
    echo 'todo';
    exit;
}

function sdTask()
{
    if (isset($_GET['key'])) {
        if ($_GET['key'] === Config::get('stable_diffusion_task_key')) {
            header('Content-Type: application/json');
            $items = PMC::get('stable_diffusion_#');
            if (!empty($items)) {
                $key = array_key_first($items);
                PMC::delete('stable_diffusion_' . $key);
                echo json_encode($items[$key]);
            } else {
                echo '[]';
            }
            exit;
        } else {
            echo 'no access';
            exit;
        }
    }
}

function handleKphpJobWorkerRequest()
{
    $kphp_job_request = kphp_job_worker_fetch_request();
    if (!$kphp_job_request) {
        warning("Couldn't fetch a job worker request");
        return;
    }
    if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerSimple) {
        // simple jobs: they start, finish, and return the result
        $kphp_job_request->beforeHandle();
        $response = $kphp_job_request->handleRequest();
        if ($response === null) {
            warning("Job request handler returned null for " . get_class($kphp_job_request));
            return;
        }
        kphp_job_worker_store_response($response);
    } else if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerManualRespond) {
        // more complicated jobs: they start, send a result in the middle (here get get it) — and continue working
        $kphp_job_request->beforeHandle();
        $kphp_job_request->handleRequest();
        if (!$kphp_job_request->wasResponded()) {
            warning("Job request handler didn't call respondAndContinueExecution() manually " . get_class($kphp_job_request));
        }
    } else if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerNoReply) {
        // background jobs: they start and never send any result, just continue in the background and finish somewhen
        $kphp_job_request->beforeHandle();
        $kphp_job_request->handleRequest();
    } else {
        warning("Got unexpected job request class: " . get_class($kphp_job_request));
    }
}
