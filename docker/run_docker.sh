#!/bin/bash
#chkconfig:2345 80 05
sed -i "s|127.0.0.1|192.168.5.103|" ../config/system.php
apt install docker && docker-compose up --build -d && rm -rf ../html