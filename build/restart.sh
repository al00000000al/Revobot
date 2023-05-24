#!/bin/bash

pkill revobot
nohup ./../../revobot/kphp_out/server -H 8088 --use-utf8 --workers-num 5 -q  --job-workers-ratio 0.5  >/dev/null 2>&1 &
