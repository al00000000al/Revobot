#!/bin/bash

# Функция для добавления или обновления задания в crontab
update_cron_job() {
    local script_path=$1
    local schedule=$2
    local cron_job="$schedule /usr/bin/php $script_path"

    # Проверяем, существует ли уже такая запись в crontab
    local cron_exists=$(crontab -l | grep -F "$script_path" | grep -v grep)

    if [ -z "$cron_exists" ]; then
        # Записи нет, добавляем ее
        (crontab -l 2>/dev/null; echo "$cron_job") | crontab -
        echo "Cron job added for $script_path."
    else
        # Запись существует, заменяем ее
        (crontab -l 2>/dev/null | grep -v "$script_path"; echo "$cron_job") | crontab -
        echo "Cron job updated for $script_path."
    fi
}

update_cron_job "/home/opc/www/revobot/scripts/cron/timer.php" "* * * * *"
