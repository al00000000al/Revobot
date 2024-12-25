
Revolucia bot



https://t.me/Therevoluciabot



---



Для работы нужно иметь kphp и ПМС (движок)





Потом это

https://github.com/VKCOM/kphp



---

Установка:



1. Настраиваем .env и config.php

2. Компилируем и ставим движки по инструкции https://habr.com/ru/sandbox/86669/,

3. Запускаем движки

```

cd build

./pmc_new_binlog.sh

./pmc_start.sh



```



4. Загружаем на сервера (микросервисы на не ру сервере должны быть)

```

./deploy_service.sh

./deploy.sh

```



5. Сборка (compile_force.sh если нужно пересобрать с нуля):



```

./compile.sh

```



6. Крон задачи добавим

```

./update_cronjob.sh

```



7. Запуск:



```

./restart.sh

```



---



Возможные проблемы:

1. PMC сдох

Так бывает. Можно попробовать перезапустить через build/pmc_start.sh

2. Все равно он сдох

Ну тогда сделаем дамп и новый бинлог ему:

```

cd build

./dump.sh

./pmc_new_binlog.sh

./import_dump.sh

```



---

Что писать в .env

SSH_* - основной хост

SSH_DO_* - хост с микросервисами

LOCAL_FOLDER - локальный путь к этой папке с исходниками



---

Что писать в config.php

tg_key = ключ бота telegram от @BotFather

tg_secret_token - https://core.telegram.org/bots/api#setwebhook

vk_key = ключ группы вк, в которой бот

secret_key = Случайная строка, (openssl rand -base64 30)

open_weather_map_api_key = https://openweathermap.org/api

openai_api_key = https://platform.openai.com/

tg_bot_admins = id админов бота тг

tg_bot_id = id бота (без минуса)

vk_bot_admins = id админов бота вк

vk_bot_id = id бота (с минусом)

vk_bot_secret = секретный ключ из настроек группы (случайная строка)

vk_bot_confirmation = тоже из настроек группы

use_ai_cmd = использовать ли openai для команд бота (true/false)

openai_api_host = путь к скрипту /openai_api.php на сайте (http://site/openai_api.php)

tmdb_api_host = путь к скрипту /tmdb_api.php на сайте (http://site/tmdb_api.php)

tmdb_api_key = API ключ https://www.themoviedb.org/

telegram_webhook_url = путь к /tg_bot (http://site/tg_bot)

base_path = путь на сервере к этой папке

public_domain = домен веб ссылок бота
