#!/bin/bash
sed -i "s|127.0.0.1|192.168.5.103|" ../config/system.php
docker_compose="/usr/local/bin/docker-compose"
docker_compose1="/usr/bin/docker-compose"
if grep -Eqii "CentOS" /etc/issue || grep -Eq "CentOS" /etc/*-release; then
	yum install wget yum-utils device-mapper-persistent-data lvm2 -y
	if [ ! -f "$docker_compose" ]; then
	    curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
	    if [ ! -f "$docker_compose" ]; then
	    	echo "安装docker-compose,请手动安装"
	    else
			chmod +x /usr/local/bin/docker-compose
			chmod +x /usr/bin/docker-compose
			ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
			yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
			yum install docker-ce-17.12.0.ce -y
			systemctl start docker
			docker-compose up --build -d
	    fi
	else
		if [ ! -f "$docker_compose1" ]; then
			ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
		fi
		chmod +x /usr/local/bin/docker-compose
		chmod +x /usr/bin/docker-compose
		yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
		yum install docker-ce-17.12.0.ce -y
		systemctl start docker
		docker-compose up --build -d && rm -rf ../html 
	fi
elif grep -Eqi "Ubuntu" /etc/issue || grep -Eq "Ubuntu" /etc/*-release; then
    apt-get install -y docker docker.io docker-compose && docker-compose up --build -d
fi
sleep 5
rm -rf $(dirname $(pwd))"/html"
#docker_onlyoffice=`docker ps -a|grep docker_onlyoffice|awk '{print $1}'`
#if [ -n "$docker_onlyoffice" ]; then
#    jwt_code=`docker exec ${docker_onlyoffice} /var/www/onlyoffice/documentserver/npm/json -f /etc/onlyoffice/documentserver/local.json 'services.CoAuthoring.secret.session.string'`
#	cur_dir=$(dirname $(pwd))"/example/config.php"
#	sed -i "s/'DOC_SERV_JWT_SECRET'] = \"\"/'DOC_SERV_JWT_SECRET'] = \"${jwt_code}\"/g" ${cur_dir}
#fi
