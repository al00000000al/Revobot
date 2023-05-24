<?php

namespace Revobot\JobWorkers\Requests;

use Revobot\JobWorkers\JobWorkerNoReply;

class Test extends JobWorkerNoReply {

  /** @var mixed */
  public $arg_to_print;

  /**
   * @param mixed $arg_to_print
   */
  public function __construct($arg_to_print) {
    $this->arg_to_print = $arg_to_print;
  }


  function handleRequest(): void {
    sleep(4);
    echo('sleep');
    dbg_echo('sleep');
  }



}
