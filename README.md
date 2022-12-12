Revolucia bot

https://t.me/Therevoluciabot

---

Для работы нужно иметь kphp и ПМС (движок)

Сначала ставим движки, buid/pmc_new_binlog.sh, build/pmc_start.sh
https://habr.com/ru/sandbox/86669/

Потом это
https://github.com/VKCOM/kphp

---

Сборка:

```
kphp  --composer-root=$(pwd) index.php
```

или

```
cd build && ./compile.sh
```

Запуск:

```
 nohup ./kphp_out/server -H 8088 --use-utf8 --workers-num 5 -q  >/dev/null 2>&1 &

```

или

```
cd build && ./restart.sh
```

---

Микросервис для чата

```
cd microservice/chat && python3 app.py -p 5001 -H 127.0.0.1 &
```
