#!/bin/bash
cd ../
php generate.php
kphp  --composer-root=$(pwd) index.php
