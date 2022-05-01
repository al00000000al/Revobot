#!/bin/bash

pkill revobot
nohup ./../../revobot/kphp_out/server -H 8088 --use-utf8 --workers-num 5 -q  >/dev/null 2>&1 &
