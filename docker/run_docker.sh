#!/bin/bash
sed -i "s|127.0.0.1|192.168.5.103|" ../config/system.php
docker_compose="/usr/local/bin/docker-compose"
if grep -Eqii "CentOS" /etc/issue || grep -Eq "CentOS" /etc/*-release; then
	yum install wget yum-utils device-mapper-persistent-data lvm2 -y
	if [ ! -f "$docker_compose" ]; then
	    curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
	    chmod +x /usr/local/bin/docker-compose
	    ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
	fi
    yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
    yum install docker-ce-17.12.0.ce -y
    systemctl start docker
    docker-compose up --build -d && rm -rf ../html 
elif grep -Eqi "Ubuntu" /etc/issue || grep -Eq "Ubuntu" /etc/*-release; then
    apt-get install docker && docker-compose up --build -d && rm -rf ../html 
fi
