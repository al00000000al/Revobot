#!/bin/bash
cd ../
php generation.php
/usr/share/vkontakte/bin/kphp2cpp  --composer-root=$(pwd) -T /home/opc/scheme.tlo  -s /usr/share/vkontakte/kphp_source/ --rt-path /usr/share/vkontakte/kphp_source/ -F index.php
strip kphp_out/server
