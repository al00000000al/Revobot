<?php

namespace Revobot;

use KLua\KLua;
use Revobot\Commands\FuckYouCmd;
use Revobot\Games\AI\Gpt;
use Revobot\Games\AI\GptPMC;
use Revobot\Money\Revocoin;
use Revobot\Neural\Answers;
use Revobot\Services\InstagramDownloader;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;
use Revobot\Util\PMC;

class Revobot
{


    public int $chat_id;

    public string $provider;
    public string $message;

    /** @var $raw_data mixed */
    public $raw_data;

    private string $tg_key = '';

    private string $parse_mode;

    private const PMC_TALK_LIMIT_KEY = 'talk_limit_'; // $provider.$chat
    private const PMC_MSG_HISTORY_KEY = 'msg_history_'; // $provider.$chat
    private const PMC_USERNAMES_CHAT_KEY = 'usernames_chat_'; // $provider.$chat
    private const PMC_CHAT_KEY = 'chat_'; // $provider.$chat
    private const DEFAULT_TALK_LIMIT = 90;

    /**
     * @param string $parse_mode
     */
    public function setParseMode(string $parse_mode): void
    {
        $this->parse_mode = $parse_mode;
    }

    /**
     * @param string $tg_key
     */
    public function setTgKey(string $tg_key): void
    {
        $this->tg_key = $tg_key;
    }

    /**
     * @param int $chat_id
     */
    public function setChatId(int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }


    /**
     * @param string $provider
     */
    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        if ($this->provider === 'tg') {
            if (isset($this->raw_data['from']['id'])) {
                return (int)$this->raw_data['from']['id'];
            }
            return 0;
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getUserNick(): string
    {
        if ($this->provider === 'tg') {
            if (isset($this->raw_data['from']['username'])) {
                return (string)$this->raw_data['from']['username'];
            }
            return 'null';
        }
        return '';
    }

    /**
     * @return int
     */
    public function getTalkLimit(): int
    {
        $response = (int) PMC::get(self::PMC_TALK_LIMIT_KEY . $this->provider . $this->chat_id);
        if (!$response) {
            return self::DEFAULT_TALK_LIMIT;
        }
        return $response;
    }

    public function setTalkLimit(int $talk_limit)
    {
        PMC::set(self::PMC_TALK_LIMIT_KEY . $this->provider . $this->chat_id, $talk_limit);
    }

    /**
     *
     */
    public function run()
    {
        if ($this->provider === 'tg') {

            $need_reply = (bool)(PMC::get('fk_' . $this->provider . $this->getUserId()));

            if (!empty($need_reply)) {
                return;
            }

            $mining_future = fork((new Revocoin($this))->mining($this->getUserId(), 0, $this->message));
            $talk_limit = $this->getTalkLimit();

            $has_bot_response = (time() % $talk_limit) === 0;

            global $parse_mode;
            $parse_mode = null;

            KLua::registerFunction1('tg_send', function ($string) {
                return Tg::sendMessage($this->chat_id, (string) $string);
            });

            $response = CommandsManager::process($this);

            if ($response && !empty($response)) {
                $this->sendMessageTg($response, $parse_mode);
                $this->addUserChat();
            }
            $mining_result = wait($mining_future);

            if (!empty($mining_result)) {
                $this->sendMessageTg('+' . $mining_result['amount'] . ' R у @' . $this->getUserNick() . "\nBlock #" . $mining_result['id']);
            }
            // Mining bot
            if ($response) {
                $mining_future_bot = fork((new Revocoin($this))->mining($this->getTgBotId(), 0, (string)$response));
                $mining_result_bot = wait($mining_future_bot);

                if (!empty($mining_result_bot)) {
                    $this->sendMessageTg('+' . $mining_result_bot['amount'] . ' R у @Therevoluciabot' . "\nBlock #" . $mining_result_bot['id']);
                }
            }

            if ($has_bot_response) {

                $bot_answer = Answers::getAnswer('- ' . $this->message . "\n - ");
                if (!empty($bot_answer)) {
                    $this->sendMessageTg((string)$bot_answer);
                    $mining_future_ans_bot = fork((new Revocoin($this))->mining($this->getTgBotId(), 0, (string)$bot_answer));
                    $mining_result_ans_bot = wait($mining_future_ans_bot);
                    if (!empty($mining_result_ans_bot)) {
                        $this->sendMessageTg('+' . $mining_result_ans_bot['amount'] . ' R у @Therevoluciabot' . "\nBlock #" . $mining_result_ans_bot['id']);
                    }
                }
            }

            // if(InstagramDownloader::is_instagram_reels_url($this->message)){
            //     InstagramDownloader::get($this->message, $this->chat_id);
            // }

            // ответ на сообщение бота
            $user_id = $this->getUserId();
            $chat_id = $this->chat_id;

            if (isset($this->raw_data['reply_to_message'])) {
                $source_text = (string)$this->raw_data['reply_to_message']['text'];
                $from_id = (int)$this->raw_data['reply_to_message']['from']['id'];
                if ($from_id === Config::getInt('tg_bot_id') && !empty($source_text)) {
                    $save_history = 1;
                    PMC::set(GptPMC::getInputKey($user_id, $this->provider), $this->message);
                    $base_path = Config::get('base_path');
                    exec("php {$base_path}gptd.php $user_id $save_history $chat_id > /dev/null 2>&1 &");
                }
            }

            if ($user_id === $chat_id && strlen($this->message) > 0 && $this->message[0] !== '/') {
                $response = (new \Revobot\Commands\Gpt\AiCmd($this->message, $this))->exec();
                $this->sendMessageTg($response, $parse_mode);
            }
        }
    }



    /**
     * @return string
     */
    public function getHistoryMsg(): string
    {
        return (string)PMC::get(self::PMC_MSG_HISTORY_KEY . $this->provider . $this->chat_id);
    }

    /**
     * @param string $msg
     * @return string
     */
    public function setHistoryMsg(string $msg): string
    {
        return (string)PMC::set(self::PMC_MSG_HISTORY_KEY . $this->provider . $this->chat_id, $msg);
    }


    /**
     * @param string $response_text
     */
    public function sendMessageTg(string $response_text, string $parse_mode = null)
    {
        if ($response_text[0] == '@') {
            $response_text = str_replace('@', '', $response_text);
        }
        Tg::sendMessage($this->chat_id, $response_text, $parse_mode);
    }


    /**
     * @param int $user_id
     * @return mixed
     */
    public function getChatMemberTg(int $user_id)
    {
        return Tg::getChatMember($this->chat_id, (string) $user_id);
    }


    /**
     * @param string $username
     * @return mixed
     */
    public function getChatMemberByUsernameTg(string $username)
    {
        return Tg::getChatMember($this->chat_id, $username);
    }

    public function sendPollTg(string $question, array $options)
    {
        return Tg::sendPoll($this->chat_id, $question, $options);
    }


    /**
     * @param mixed $raw_data
     */
    public function setRawData(array $raw_data): void
    {
        $this->raw_data = $raw_data;
    }

    /**
     * @return mixed
     */
    public function loadChat(): array
    {
        return PMC::get(self::PMC_CHAT_KEY . $this->provider . $this->chat_id);
    }

    /**
     * @return mixed
     */
    public function loadUsernamesChat(): array
    {
        return PMC::get(self::PMC_USERNAMES_CHAT_KEY . $this->provider . $this->chat_id);
    }

    public function addUserChat()
    {
        $user = $this->getUserId();
        $chat = $this->loadChat();
        $usernames_chat = $this->loadUsernamesChat();
        if (!in_array($user, $chat)) {
            $chat[] = $user;
            PMC::set(self::PMC_CHAT_KEY . $this->provider . $this->chat_id, $chat);
        }

        $user_nick = $this->getUserNick();
        if (!array_key_exists($user, $usernames_chat) || $usernames_chat[$user_nick] !== $user) {
            $usernames_chat[$user_nick] = $user;
            PMC::set(self::PMC_USERNAMES_CHAT_KEY . $this->provider . $this->chat_id, $usernames_chat);
        }
    }

    public function getTgBotId()
    {
        return -Config::getInt('tg_bot_id');
    }

    public function sendTypeStatusTg()
    {
        Tg::sendChatAction($this->chat_id, 'typing');
    }
}
