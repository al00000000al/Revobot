#!/bin/bash

sudo pkill -f kphp_out
cd ../
sudo nohup ./kphp_out/server -H 8088 --user kitten --use-utf8 --workers-num 5 -f 2 --job-workers-ratio 0.5  -v   >/dev/null 2>&1 &
