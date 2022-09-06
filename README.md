Revolucia bot

https://t.me/Therevoluciabot

----------

Для работы нужно иметь kphp и ПМС (движок)

Сначала ставим движки
https://habr.com/ru/sandbox/86669/

Потом это
https://github.com/VKCOM/kphp

------
Сборка:
````
kphp  --composer-root=$(pwd) index.php
````

Запуск:
````
 nohup ./kphp_out/server -H 8088 --use-utf8 --workers-num 5 -q  >/dev/null 2>&1 &

````

