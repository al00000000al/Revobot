#!/bin/bash
cd ../
php generation.php
kphp  --composer-root=$(pwd) -F index.php
