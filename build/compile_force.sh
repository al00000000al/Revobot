#!/bin/bash
cd ../
kphp  --composer-root=$(pwd) -F --composer-no-dev index.php
