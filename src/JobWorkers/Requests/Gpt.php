<?php

namespace Revobot\JobWorkers\Requests;

use Revobot\Config;
use Revobot\Games\AI\Gpt as AIGpt;
use Revobot\JobWorkers\JobWorkerNoReply;
use Revobot\Util\Curl;

class Gpt extends JobWorkerNoReply {

  /** @var mixed */
  public $options;

  /**
   * @param mixed $arg_to_print
   */
  public function __construct($arg_to_print) {
    $this->options = $arg_to_print;
  }


  function handleRequest(): void {
    $response = AIGpt::generate((string)$this->options['input'], (int) $this->options['user_id'], (string) $this->options['provider'], (bool)$this->options['clear_all'] ?? false, (string)$this->options['model'] ?? 'gpt-3.5-turbo');
    self::sendMessageTg($response);
  }



  public function sendMessageTg(string $response_text)
  {
      $url = 'https://api.telegram.org/bot' . Config::get('tg_key') . '/sendMessage';

      if ($response_text[0] == '@') {
          $response_text = str_replace('@', '', $response_text);
      }

      Curl::post($url, [
          'chat_id' => (int) $this->options['chat_id'],
          'text' => $response_text,
      ]);

  }


}
