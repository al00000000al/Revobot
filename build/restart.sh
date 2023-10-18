#!/bin/bash

pkill -f revobot
nohup ../kphp_out/server -H 8088 --user kitten --use-utf8 --workers-num 5 -q   >/dev/null 2>&1 &
