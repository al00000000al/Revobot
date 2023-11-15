<?php
// kphp -F --composer-root=$(pwd) --composer-no-dev index.php
//  ./build/server -H 8088 --use-utf8 --workers-num 5  -q  &

use Revobot\Config;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lang.php';

global $pmc;

if (PHP_SAPI !== 'cli' && isset($_SERVER["JOB_ID"])) {
    handleKphpJobWorkerRequest();
} else {
    $url = $_SERVER['PHP_SELF'];
    if($url === '/tg_bot' || $url === '/vk_bot') {
        $data = file_get_contents('php://input');

        dbg_echo($data);

        $data_arr = json_decode($data, true);
        if(!$data_arr) {
            return;
        }



        $pmc = new Memcache();
        $pmc->addServer('127.0.0.1', 11209);

        if($url === '/tg_bot' && isset($data_arr['message']['chat']['id'])) {
            $chat_id = $data_arr['message']['chat']['id'];
            $bot = new Revobot\Revobot('tg');
            $bot->setChatId((int)$chat_id);
            $bot->setTgKey(Config::get('tg_key'));
            $bot->setPmc($pmc);

            if(isset($data_arr['message']['text'])) {
                $bot->setMessage((string)$data_arr['message']['text']);
                $bot->setRawData($data_arr['message']);
                $bot->run();
            }
            if(isset($data_arr['message']['photo'])) {
                if(isset($data_arr['message']['caption'])){
                    $bot->setMessage((string)$data_arr['message']['caption']);
                } else {
                    $bot->setMessage('');
                }
                $bot->setRawData($data_arr['message']);
                $bot->run();
            }
        }
    }
}

function handleKphpJobWorkerRequest() {
    global $pmc;

    $pmc = new Memcache();
    $pmc->addServer('127.0.0.1', 11209);
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
