#!/bin/bash
cd ../
php generation.php
kphp  --composer-root=$(pwd) -T /home/opc/scheme.tlo -F index.php
strip kphp_out/server
