<?php

namespace Revobot;

use KLua\KLua;
use Revobot\Commands\DefecatorCmd;
use Revobot\Commands\HuebotCmd;
use Revobot\Commands\StorageSetCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Money\Revocoin;
use Revobot\Neural\Answers;
use Revobot\Services\Providers\Tg;
use Revobot\Services\Providers\Vk;
use Revobot\Util\Curl;
use Revobot\Util\PMC;
use Revobot\Util\Strings;
use Revobot\Util\Throttler;

class Revobot
{


    public int $chat_id;
    public int $message_thread_id = -1;

    public string $provider;
    public string $message;

    /** @var $raw_data mixed */
    public $raw_data;

    private string $tg_key = '';

    private string $vk_key = '';

    private const PMC_TALK_LIMIT_KEY = 'talk_limit_'; // $provider.$chat
    private const PMC_MSG_HISTORY_KEY = 'msg_history_'; // $provider.$chat
    private const PMC_USERNAMES_CHAT_KEY = 'usernames_chat_'; // $provider.$chat
    private const PMC_CHAT_KEY = 'chat_'; // $provider.$chat
    private const DEFAULT_TALK_LIMIT = 90;

    /**
     * @param string $tg_key
     */
    public function setTgKey(string $tg_key): void
    {
        $this->tg_key = $tg_key;
    }

    /**
     * @param string $vk_key
     */
    public function setVkKey(string $vk_key): void
    {
        $this->vk_key = $vk_key;
    }

    /**
     * @param int $chat_id
     */
    public function setChatId(int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }

     /**
     * @param int $message_thread_id
     */
    public function setMessageThreadId(int $message_thread_id): void
    {
        $this->message_thread_id = $message_thread_id;
    }


    /**
     * @param string $provider
     */
    public function __construct(string $provider)
    {
        /** @var Revobot $Bot */
        global $Bot;
        $this->provider = $provider;
        $Bot = $this;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
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

        if ($this->provider === 'vk') {
            $response = Vk::getUsers([$this->raw_data['from_id']]);
            if (empty($response)) {
                return 'null';
            } else {
                return $response[0]['first_name'] . ' ' . $response[0]['last_name'];
            }
        }

        return '';
    }

    /**
     * @return int
     */
    public function getTalkLimit(): int
    {
        $response = (int) PMC::get(self::PMC_TALK_LIMIT_KEY . $this->provider . chatId());
        if (!$response) {
            return self::DEFAULT_TALK_LIMIT;
        }
        return $response;
    }

    public function setTalkLimit(int $talk_limit)
    {
        PMC::set(self::PMC_TALK_LIMIT_KEY . $this->provider . chatId(), $talk_limit);
    }

    /**
     *
     */
    public function run()
    {
        if ($this->provider === 'tg' || $this->provider === 'vk') {

            $startTime = microtime(true);
            $need_reply = (bool)(PMC::get('fk_' . $this->provider . userId()));

            if (!empty($need_reply)) {
                return;
            }

            $mining_future = fork((new Revocoin($this))->mining(userId(), 0, $this->message));
            $talk_limit = $this->getTalkLimit();

            $has_bot_response = (time() % $talk_limit) === 0;

            global $parse_mode;
            $parse_mode = null;

            #region API
            KLua::registerFunction2('sendMessage', function ($string, $options = []) {
                if ($this->provider === 'tg') {
                    if  ($this->message_thread_id !== -1) {
                        $options['message_thread_id'] = $this->message_thread_id;
                    }
                    return Tg::sendMessage(chatId(), (string) $string, '', (array)$options);
                }
                if ($this->provider === 'vk') {
                    return Vk::sendMessage(chatId(), (string) $string, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('editMessageText', function ($message_id, $text, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::editMessageText(chatId(), (int)$message_id, (string) $text, '', (array)$options);
                }
                return '';
            });

            KLua::registerFunction2('editMessageReplyMarkup', function ($message_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::editMessageReplyMarkup(chatId(), (int)$message_id, (array) $options);
                }
                return '';
            });

            KLua::registerFunction3('sendPoll', function ($question, $options = [], $opts = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendPoll(chatId(), (string) $question, (array)$options, (array)$opts);
                }
                return '';
            });

            KLua::registerFunction3('sendPhoto', function ($photo, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendPhoto(chatId(), (string) $photo, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendAnimation', function ($animation, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendAnimation(chatId(), (string) $animation, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendVideo', function ($video, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendVideo(chatId(), (string) $video, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendDocument', function ($document, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendDocument(chatId(), (string) $document, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendAudio', function ($audio, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendAudio(chatId(), (string) $audio, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendVoice', function ($voice, $caption = '', $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendVoice(chatId(), (string) $voice, (string)$caption, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('sendLocation', function ($latitude, $longitude, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendLocation(chatId(), (float) $latitude, (float)$longitude, (array)$options);
                }
                return '';
            });

            KLua::registerFunction4('sendVenue', function ($latitude, $longitude, $title, $address) {
                if ($this->provider === 'tg') {
                    return Tg::sendVenue(chatId(), (float) $latitude, (float)$longitude, (string)$title, (string)$address);
                }
                return '';
            });

            KLua::registerFunction3('sendContact', function ($phone_number, $first_name, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendContact(chatId(), (string) $phone_number, (string)$first_name, (array)$options);
                }
                return '';
            });

            KLua::registerFunction1('sendDice', function ($options = []) {
                if ($this->provider === 'tg') {
                    return Tg::sendDice(chatId(), (array)$options);
                }
                return '';
            });


            KLua::registerFunction1('sendChatAction', function ($action) {
                if ($this->provider === 'tg') {
                    return Tg::sendChatAction(chatId(), (string) $action);
                }
                return '';
            });

            KLua::registerFunction2('getUserProfilePhotos', function ($user_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::getUserProfilePhotos((int)$user_id, (array)$options);
                }
                return '';
            });

            KLua::registerFunction1('getChatMember', function ($user_id) {
                if ($this->provider === 'tg') {
                    return Tg::getChatMember(chatId(), (string)$user_id);
                }
                return '';
            });

            KLua::registerFunction1('getMyCommands', function ($options = []) {
                if ($this->provider === 'tg') {
                    return Tg::getMyCommands((array)$options);
                }
                return '';
            });

            KLua::registerFunction1('setChatMenuButton', function ($options = []) {
                if ($this->provider === 'tg') {
                    return Tg::setChatMenuButton((array)$options);
                }
                return '';
            });

            KLua::registerFunction1('getChatMenuButton', function ($options = []) {
                if ($this->provider === 'tg') {
                    return Tg::getChatMenuButton((array)$options);
                }
                return '';
            });

            KLua::registerFunction1('getMyDefaultAdministratorRights', function ($options = []) {
                if ($this->provider === 'tg') {
                    return Tg::getMyDefaultAdministratorRights((array)$options);
                }
                return '';
            });

            KLua::registerFunction2('stopPoll', function ($message_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::stopPoll(chatId(), (int)$message_id, (array)$options);
                }
                return '';
            });

            KLua::registerFunction1('deleteMessage', function ($message_id) {
                if ($this->provider === 'tg') {
                    return Tg::deleteMessage(chatId(), (int)$message_id);
                }
                if ($this->provider === 'vk') {
                    return Vk::deleteMessage(chatId(), (int)$message_id);
                }
                return '';
            });

            KLua::registerFunction1('getFile', function ($file_id) {
                if ($this->provider === 'tg') {
                    return Tg::getFile((string)$file_id);
                }
                return '';
            });

            KLua::registerFunction2('banChatMember', function ($user_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::banChatMember(chatId(), (int)$user_id, (array)$options);
                }
                return '';
            });

            KLua::registerFunction2('unbanChatMember', function ($user_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::unbanChatMember(chatId(), (int)$user_id, (array)$options);
                }
                return '';
            });

            KLua::registerFunction3('restrictChatMember', function ($user_id, $permissions, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::restrictChatMember(chatId(), (int)$user_id, (string)$permissions, (array)$options);
                }
                return '';
            });

            KLua::registerFunction0('getChatMemberCount', function () {
                if ($this->provider === 'tg') {
                    return Tg::getChatMemberCount(chatId());
                }
                return '';
            });

            KLua::registerFunction2('answerCallbackQuery', function ($callback_query_id, $options = []) {
                if ($this->provider === 'tg') {
                    return Tg::answerCallbackQuery((int)$callback_query_id, (array)$options);
                }
                return '';
            });

            KLua::registerFunction1('getChatAdministrators', function ($chat_id = 0) {
                if ((int)$chat_id === 0) {
                    $chatId = chatId();
                } else {
                    $chatId = (int)$chat_id;
                }
                if ($this->provider === 'tg') {
                    return Tg::getChatAdministrators((int)$chatId);
                }
                return '';
            });

            KLua::registerFunction1('getChat', function ($chat_id = 0) {
                if ((int)$chat_id === 0) {
                    $chatId = chatId();
                } else {
                    $chatId = (int)$chat_id;
                }
                if ($this->provider === 'tg') {
                    return Tg::getChat((int)$chatId);
                }
                return '';
            });

            KLua::registerFunction1('httpGet', function ($string) {
                return (string)Curl::get((string)$string);
            });

            KLua::registerFunction3('httpPost', function ($string, $data, $headers  = []) {
                return (string)Curl::post((string)$string, (string)$data, ['headers' => (array)$headers]);
            });

            KLua::registerFunction1('jsonEncode', function ($data) {
                return (string)json_encode((array)$data);
            });

            KLua::registerFunction1('jsonDecode', function ($string) {
                return (array)json_decode((string)$string, true);
            });

            KLua::registerFunction2('explode', function ($string, $delimiter = ' ') {
                return (array)explode($delimiter, $string);
            });

            KLua::registerFunction2('random', function ($min, $max) {
                return mt_rand((int)$min, (int)$max);
            });

            KLua::registerFunction1('randomStr', function ($array) {
                $arr = (array)$array;
                return (string)$arr[mt_rand(0, count($arr) - 1)];
            });

            KLua::registerFunction4('storageSet', function ($key, $value, $exp = 0, $global = 0) {
                global $ComandCreator;

                $user_id = (string)userId();
                if ((int)$global > 0 && !empty($ComandCreator)) {
                    $user_id = (string)$ComandCreator;
                }
                PMC::set(StorageSetCmd::getKey($this->provider, $user_id, (string)$key), $value, 0, (int)$exp);
                return true;
            });

            KLua::registerFunction2('storageGet', function ($key, $global = 0) {
                global $ComandCreator;

                $user_id = (string)userId();
                if ((int)$global > 0 && !empty($ComandCreator)) {
                    $user_id = (string)$ComandCreator;
                }

                $result = PMC::get(StorageSetCmd::getKey($this->provider, $user_id, (string)$key));
                if (is_array($result)) {
                    return $result;
                }
                return (string)$result;
            });

            KLua::registerFunction0('loadChat', function () {
                return (array)$this->loadChat();
            });

            #endregion

            $response = CommandsManager::process($this);

            if ($response && !empty($response)) {
                if ($this->provider === 'tg') {
                    if (!$this->checkAgreement()) {
                        return;
                    }
                }
                if (isAdmin(userId())) {
                    $is_debug = (bool) PMC::get('debug');
                    if ($is_debug) {
                        global $Debug;
                        $response .= "\n--------------\n\n" . $Debug;
                        $response .= "\n" . round(microtime(true) - $startTime, 4) . ' сек.';
                    }
                }

                $this->sendMessage($response);

                $this->addUserChat();
            }
            $mining_result = wait($mining_future);

            if (!empty($mining_result)) {
                $this->sendMessage(self::renderMiningMessage($mining_result['amount'], $this->getUserNick(), $mining_result['id'], $mining_result['hash']), 'html');
            }
            // Mining bot
            if ($response) {
                $mining_future_bot = fork((new Revocoin($this))->mining($this->getBotId(), 0, (string)$response));
                $mining_result_bot = wait($mining_future_bot);

                if (!empty($mining_result_bot)) {
                    $this->sendMessage(self::renderMiningMessage($mining_result_bot['amount'], 'Therevoluciabot', $mining_result_bot['id'], $mining_result_bot['hash']), 'html');
                }
            }

            // if(InstagramDownloader::is_instagram_reels_url($this->message)){
            //     InstagramDownloader::get($this->message, chatId());
            // }

            // ответ на сообщение бота
            $user_id = userId();
            $chat_id = chatId();

            if ($this->provider === 'tg') {
                if (isset($this->raw_data['reply_to_message'])) {
                    $from_id = (int)$this->raw_data['reply_to_message']['from']['id'];
                    $tg_bot_id = Config::getInt('tg_bot_id');
                    if ($this->provider === 'tg' && $from_id === $tg_bot_id) {
                        if (!$this->checkAgreement()) {
                            return;
                        }
                    }
                    $source_text = (string)$this->raw_data['reply_to_message']['text'];

                    if ($from_id === $tg_bot_id && !empty($source_text) && $this->message[0] !== '/') {
                        if (Throttler::check($user_id, 'aicmd', 50)) {
                            $this->sendMessage('Больше нельзя сегодня');
                        } else {
                            $save_history = 1;
                            PMC::set(GptPMC::getInputKey($user_id, $this->provider), $this->message);
                            $base_path = Config::get('base_path');
                            $thread_id = $this->message_thread_id;
                            exec("cd {$base_path}/scripts && php gptd.php $user_id $save_history $chat_id 0 $thread_id > /dev/null 2>&1 &");
                        }
                    }
                }
            }

            if ($this->provider === 'vk') {
                if (isset($this->raw_data['reply_message'])) {
                    $source_text = (string)$this->raw_data['reply_message']['text'];
                    $from_id = (int)$this->raw_data['reply_message']['from_id'];
                    if ($from_id === Config::getInt('vk_bot_id') && !empty($source_text) && $this->message[0] !== '/') {
                        if (Throttler::check($user_id, 'aicmd', 50)) {
                            $this->sendMessage('Больше нельзя сегодня');
                        } else {
                            $save_history = 1;
                            PMC::set(GptPMC::getInputKey($user_id, $this->provider), $this->message);
                            $base_path = Config::get('base_path');
                            $thread_id = $this->message_thread_id;
                            exec("cd {$base_path}/scripts && php gptd.php $user_id $save_history $chat_id 0 $thread_id > /dev/null 2>&1 &");
                        }
                    }
                }
            }


            // if ($user_id === $chat_id && strlen($this->message) > 0 && $this->message[0] !== '/') {
            //     (new \Revobot\Commands\Gpt\AiCmd($this->message, $this))->exec();
            // }

            if ($has_bot_response) {
                $chat_commands = PMC::get(DefecatorCmd::getKey());
                if (empty($chat_commands)) {
                    $bot_answer = Answers::getAnswer('Вопрос: ' . $this->message . "\nОтвет:");
                } else {
                    $count = count($chat_commands) - 1;
                    $message = $this->message;
                    $this->setMessage('/' . $chat_commands[mt_rand(0, $count)] . ' ' . $message);
                    $bot_answer = CommandsManager::process($this);
                }
                if (!empty($bot_answer)) {
                    $this->sendMessage((string)$bot_answer);
                    $mining_future_ans_bot = fork((new Revocoin($this))->mining($this->getBotId(), 0, (string)$bot_answer));
                    $mining_result_ans_bot = wait($mining_future_ans_bot);
                    if (!empty($mining_result_ans_bot)) {
                        $this->sendMessage(self::renderMiningMessage($mining_result_ans_bot['amount'], 'Therevoluciabot', $mining_result_ans_bot['id'], $mining_result_ans_bot['hash']), 'html');
                    }
                }
            }
        }
    }


    /**
     * @deprecated
     */
    public function checkAgreement(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getHistoryMsg(): string
    {
        return (string)PMC::get(self::PMC_MSG_HISTORY_KEY . $this->provider . chatId());
    }

    /**
     * @param string $msg
     * @return string
     */
    public function setHistoryMsg(string $msg): string
    {
        return (string)PMC::set(self::PMC_MSG_HISTORY_KEY . $this->provider . chatId(), $msg);
    }


    /**
     * @param string $response_text
     */
    public function sendMessage(string $response_text, string $parse_mode = null)
    {
        if ($response_text[0] == '@') {
            $response_text = str_replace('@', '', $response_text);
        }
        if ($this->provider === 'tg') {
            $options = [];
            if ($this->message_thread_id !== -1) {
                $options['message_thread_id'] = $this->message_thread_id;
            }
            Tg::sendMessage(chatId(), $response_text, $parse_mode, $options);
        } elseif ($this->provider === 'vk') {
            Vk::sendMessage(chatId(), $response_text);
        }
    }


    /**
     * @param int $user_id
     * @return mixed
     */
    public function getChatMemberTg(int $user_id)
    {
        return Tg::getChatMember(chatId(), (string) $user_id);
    }


    /**
     * @param string $username
     * @return mixed
     */
    public function getChatMemberByUsernameTg(string $username)
    {
        return Tg::getChatMember(chatId(), $username);
    }

    public function sendPollTg(string $question, array $options)
    {
        return Tg::sendPoll(chatId(), $question, $options);
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
        return PMC::get(self::PMC_CHAT_KEY . $this->provider . chatId());
    }

    /**
     * @return mixed
     */
    public function loadUsernamesChat(): array
    {
        return PMC::get(self::PMC_USERNAMES_CHAT_KEY . $this->provider . chatId());
    }

    public function addUserChat()
    {
        $user = userId();
        $chat = $this->loadChat();
        $usernames_chat = $this->loadUsernamesChat();
        if (!in_array($user, $chat)) {
            $chat[] = $user;
            PMC::set(self::PMC_CHAT_KEY . $this->provider . chatId(), $chat);
        }

        $user_nick = $this->getUserNick();
        if (!array_key_exists($user, $usernames_chat) || $usernames_chat[$user_nick] !== $user) {
            $usernames_chat[$user_nick] = $user;
            PMC::set(self::PMC_USERNAMES_CHAT_KEY . $this->provider . chatId(), $usernames_chat);
        }
    }

    public function getBotId()
    {
        if ($this->provider === 'tg') {
            return -Config::getInt('tg_bot_id');
        }

        if ($this->provider === 'vk') {
            return -Config::getInt('vk_bot_id');
        }
        return 0;
    }

    public function sendTypeStatus()
    {
        if ($this->provider === 'tg') {
            Tg::sendChatAction(chatId(), 'typing');
        }
        if ($this->provider === 'vk') {
            Vk::setActivity(chatId(), 'typing');
        }
    }


    public static function verifyTgRequest()
    {
        if (isset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'])) {
            if (hash_equals((string)$_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'], Config::get('tg_secret_token'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @kphp-inline
     */
    public static function renderMiningMessage($amount, $username, $block_id, $hash)
    {
        $domain = Config::get('public_domain');
        return "+{$amount} R у @{$username}\n<a href=\"{$domain}/blocks/{$block_id}_{$hash}\">Block #{$block_id}</a>";
    }
}
