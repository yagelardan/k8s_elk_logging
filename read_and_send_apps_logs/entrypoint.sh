#!/bin/bash
docker logs -f $LOGS_CONTAINER_ID >> log.out & # write a file of the logs
tail -f log.out | telnet logstash $LOGSTASH_PORT # send the log using tcp. start each line with hostname (for GROK).

