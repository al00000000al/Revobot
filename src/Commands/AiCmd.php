<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\OpenAI;

class AiCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        self::setDescription("Введите /ai текст\n\nДля сброса темы напишите /ai reset");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            if ($this->input === 'reset') {
                return self::reset();
            }
            return self::process();
        }
        return $this->description;
    }

    private function getConversationKey(int $chat_id): string
    {
        return 'conversation_chat'.$chat_id;
        //$this->bot->pmc->get($key)
    }

    /**
     * last conversation_id
     * @param int $chat_id
     * @return string
     */
    private function getLastConversation(int $chat_id): string
    {
        $conversation_id = $this->bot->pmc->get(self::getConversationKey($chat_id));
        if (!$conversation_id) {
            return '';
        } else {
            return $conversation_id;
        }
    }

    private function setLastConversation(int $chat_id, string $conversation_id): bool
    {
        $this->bot->pmc->set(self::getConversationKey($chat_id), $conversation_id);
        return true;
    }

    private function process()
    {
        $conversation_id = self::getLastConversation(self::getChatId());
        $response = (new OpenAI())->generate($this->input, $conversation_id);
        if ($response) {
            $res_json = json_decode($response);
        } else {
            return "";
        }
    }

    private function reset()
    {
    }

    private function getChatId()
    {
        return $this->bot->chat_id;
    }
}
