<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Who;
use Revobot\Money\StatStoyak;
use Revobot\Revobot;
use Revobot\Util\PMC;

class StoyakCmd extends BaseCmd
{
    const KEYS = ['stoyak', 'стояк'];
    const IS_ENABLED = true;
    const IS_HIDDEN = true;
    const HELP_DESCRIPTION = 'Узнать';

    const PSINKA_ID = 176165416;

    private Revobot $bot;

    private int $chat_id;

    public const DAY = 24 * 60 * 60;

    private $resultMessages = [
        'Сегодня стояк у ',
        'Поздравляем счастливчика ',
        'Сегодня был обнаружен со стояком ',
    ];
    private $messages = [
        'Считаем количество членов на планете',
        'Измеряем длину члена',
        'Запускаем ядерные рокеты',
        'Ищем счастливчика в этом чате',
        'Считаем количество звезд на небе',
        'Назначаем нового президента',
        'Обсуждаем последние новости в мире',
        'Приготовляем вкусный обед для всех участников',
        'Проводим выборы в мэрии',
        'Открываем новый музей в центре города',
        'Обсуждаем планы на летний отпуск',
        'Собираемся на концерт любимой группы',
        'Готовимся к сезону охоты и рыбалки',
        'Решаем, какие фильмы посмотреть на вечеринке',
        'Планируем поездку на море в этом году',
        'Обсуждаем последние книги, которые мы прочитали',
        'Приготовляемся к празднику, который скоро наступит',
        'Организуем встречу со старыми друзьями.',
        'Отправляемся в путешествие по Европе',
        'Обсуждаем последние тенденции в моде',
        'Планируем участие в спортивных соревнованиях',
        'Готовимся к новому учебному году',
        'Собираемся на фестиваль музыки и искусства',
        'Организуем вечеринку в честь дня рождения друга',
        'Решаем, какую экскурсию отправиться в этом году',
        'Обсуждаем последние новинки в мире технологий',
        'Подготавливаемся к новому сезону театра и оперы',
        'Планируем поездку на море в этом году.',
        'Проверяем качество члена',
        'Изучаем динамику роста члена',
        'Анализируем физиологические свойства члена',
        'Сравниваем эффективность различных методов ухода за членом',
        'Изучаем влияние факторов на рост члена',
        'Собираем информацию о самых больших членах в мире',
        'Оцениваем влияние члена на качество жизни человека',
        'Изучаем структуру клеток члена',
        'Определяем средний вес члена',
        'Сравниваем функциональность члена у разных видов',
        'Исследуем эволюцию члена в разных экологических условиях',
        'Определяем разновидности члена у разных организмов',
        'Определяем разницу между членом и членом комитета',
        'Изучаем рост члена со временем',
        'Сравниваем количество членов у разных видов животных',
        'Изучаем роль члена в социальном поведении животных',
        'Создаем модель члена для симуляции его работы',
        'Какова погода сегодня?',
        'Какие записи есть в моем календаре?',
        'Какова курс доллара к рублю?',
        'Какова популярность моей компании в социальных сетях?',
        'Какова цена на нефть на мировых рынках?',
        'Какова стоимость моего инвестиционного портфеля?',
        "Проверяем качество члена на удовлетворительность",
        "Создаем карту мира членов",
        "Изучаем разновидности членов разных видов",
        "Собираем данные о количестве и динамике членов",
        "Изучаем влияние членов на окружающую среду",
        'Считаем количество жителей на планете',
        'Измеряем рост населения',
        'Проверяем уровень загрязнения воздуха',
        'Изучаем влияние климатических изменений на экосистему',
        'Определяем уровень жизни населения',
        'Анализируем рынок труда и прогнозируем тенденции развития',
        'Изучаем поведение людей в социальных сетях',
        'Собираем данные о потребительских привычках населения',
        'Оцениваем возможности развития туризма на планете',
        'Считаем количество животных на планете',
        'Измеряем высоту небоскреба',
        'Определяем вес гигантского медведя',
        'Вычисляем скорость кометы',
        'Изучаем время затухания звезды',
        'Определяем размер волны в океане',
        'Считаем количество листьев на дереве',
        'Изучаем поведение медузы в океане',
        'Определяем температуру солнечного ядра',
        'Вычисляем объем вулкана перед извержением',
        'Сравниваем средний вес населения на разных континентах',
        'Определяем уровень доходов населения в разных странах',
        'Считаем количество больниц на планете',
        'Изучаем уровень образования в разных странах',
        'Определяем количество пищевой продукции, произведенной на планете',
        'Изучаем уровень водоснабжения в разных странах',
        'Определяем количество энергии, потребляемой на планете',
        'Изучаем уровень загрязнения окружающей среды в разных странах',
        "Изучаем влияние различных факторов на функционирование члена",
        "Создаем новые методы для улучшения качества члена",
        "Разрабатываем инновационные технологии для увеличения члена",
        "Изучаем возможности использования члена в медицинских целях",
        "Анализируем статистические данные о членах разных культур и этнических групп",
    ];


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->chat_id = $this->bot->chat_id;
        $this->setDescription('Введите /stoyak');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if ($this->input === 'stat') {
            return (new StatStoyak($this->bot))->get();
        } else {
            list($time, $user_id) = $this->getLastStoyak($this->chat_id);
            $user_name = '';
            if (!self::isTodayStoyak((int)$time)) {
                list($user_id, $user_name) = $this->doCalc();
            }
            if (empty($user_name)) {
                $user_name = $this->getUsername((int)$user_id);
            } else {
                $user_name = '@' . $user_name;
            }
            return $this->getRandomMessage($this->resultMessages) . ' ' . $user_name;
        }
    }

    private function doCalc()
    {
        $users = $this->bot->loadUsernamesChat();
        if (in_array(self::PSINKA_ID, $users)) {
            $user_id = self::PSINKA_ID;
            $user_name = '';
        } else {
            list($user_id, $user_name) = (new Who("У кого сегодня стояк?", $this->bot))->calcUserId();
            $user_id = (int) $user_id;
        }
        $this->writeCalcText();
        $this->updateLastStoyak($this->chat_id, $user_id);
        $this->incUserStoyak($this->chat_id, $user_id);
        instance_cache_delete(StatStoyak::getStatCacheKey($this->bot->chat_id));
        return [$user_id, $user_name];
    }
    private function writeCalcText()
    {
        $this->bot->sendTypeStatusTg();
        $this->bot->sendMessageTg($this->getRandomMessage($this->messages));
        //sleep(3);
        $this->bot->sendTypeStatusTg();
        $this->bot->sendMessageTg($this->getRandomMessage($this->messages));
        //sleep(3);
        $this->bot->sendTypeStatusTg();
        $this->bot->sendMessageTg("3");
        //sleep(3);
        $this->bot->sendTypeStatusTg();
        $this->bot->sendMessageTg("2");
        //sleep(4);
        $this->bot->sendTypeStatusTg();
        $this->bot->sendMessageTg("1...");
        // sleep(3);
        $this->bot->sendTypeStatusTg();
    }

    private function getLastStoyak($chat_id)
    {
        $result = PMC::get(self::getChatKey($chat_id));
        if (!$result) {
            return [0, 0];
        }
        return $result;
    }

    private static function isTodayStoyak(int $last_time): bool
    {
        return $last_time + self::DAY >= time();
    }


    private function incUserStoyak($chat_id, $user_id)
    {
        $key = self::getUserChatKey($chat_id, $user_id);
        $old = (int) PMC::get($key);
        PMC::set($key, $old + 1);
    }

    private function updateLastStoyak($chat_id, $user_id)
    {
        PMC::set(self::getChatKey($chat_id), [time(), $user_id]);
    }

    private function getRandomMessage(array $messages)
    {
        $selected = mt_rand(0, count($messages) - 1);
        return $messages[$selected];
    }


    private static function getChatKey($chat_id)
    {
        return 'stoyak_' . $chat_id;
    }

    public static function getUserChatKey($chat_id, $user_id)
    {
        return 'stoyak_stat_' . $chat_id . '_' . $user_id;
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function getUsername(int $user_id): string
    {
        $chat_member = $this->bot->getChatMemberTg($user_id);

        if (!isset($chat_member['result'])) {
            return '';
        }

        if (isset($chat_member['result']['user']['username'])) {
            $username = '@' . $chat_member['result']['user']['username'];
        } else {
            $username = (string)$user_id;
        }
        return $username;
    }
}
