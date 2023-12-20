#!/bin/bash

pkill -f kphp_out
cd ../
nohup ./kphp_out/server -H 8088 --user kitten --use-utf8 --workers-num 5 -q   >/dev/null 2>&1 &
