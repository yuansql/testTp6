#!/bin/bash

source /etc/profile

time=$(date +%Y-%m-%d-%H:%M:%S)

cd /Users/wumm/Sites/thinkphp/testTp/testTp6/public/shell/ || exit

if [ 0 -eq $? ] ; then
         echo "$time INFO 进入目录成功" >> build_log.log
else
         echo "$time ERROR 进入目录失败" >> build_log.log
                exit 1
fi

echo "$time"
