#!/bin/bash
tail -f /var/log/log.out | telnet $LOGSTASH_HOST $LOGSTASH_PORT

