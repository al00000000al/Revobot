#!/usr/bin/env bash
mv /var/lib/engine/pmemcached.bin /var/lib/engine/pmemcached.bin.bak
/usr/local/src/kphp-kdb/scripts/create_binlog.sh 0x37450101 1 0 > /var/lib/engine/pmemcached.bin
chown -R kitten:kitten /var/lib/engine/
