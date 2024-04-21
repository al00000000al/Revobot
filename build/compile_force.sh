#!/bin/bash
cd ../
php generation.php
kphp  --composer-root=$(pwd) -T /var/www/vkontakte/data/www/vkontakte.com/tl/scheme.tlo -F index.php
strip kphp_out/server
