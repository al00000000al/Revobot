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
kphp  --composer-root=$(pwd) -T /home/opc/scheme.tlo -F index.php
```

или

```
cd build && ./compile.sh
```

Запуск:

```
 nohup ./kphp_out/server -H 8088 --use-utf8 --workers-num 5 -q --job-workers-ratio 0.5 -t 120  >/dev/null 2>&1 &

```

или

```
cd build && ./restart.sh
```

---
