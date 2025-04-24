<?php

namespace Revobot\JobWorkers\Requests;

use Revobot\JobWorkers\JobWorkerNoReply;
use Revobot\Services\Providers\Tg;

class Test extends JobWorkerNoReply
{

    /** @var mixed */
    public string $arg_to_print;
    public int $chat_id;

    /**
     * @param string $arg_to_print
     * @param int $chat_id
     */
    public function __construct(string $arg_to_print, int $chat_id)
    {
        $this->arg_to_print = $arg_to_print;
        $this->chat_id = $chat_id;
    }

    protected function restoreGlobalsContext(array $untyped_context)
    {
        require_once  __DIR__ . '/../../../config.php';
    }

    function handleRequest(): void
    {
        Tg::send((string)$this->arg_to_print, (int)$this->chat_id);
    }
}
