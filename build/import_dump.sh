#!/usr/bin/env bash
/etc/init.d/engine restart pmc
cd ../
php import_dump.php
