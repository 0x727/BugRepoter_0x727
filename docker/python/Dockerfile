FROM yankovg/python3.8.2-ubuntu18.04

ADD . /app

WORKDIR /app

RUN apt-get update \
&& rm /usr/bin/python3 \
&& ln -s /usr/local/bin/python3.8 /usr/bin/python3 \
&& ln -s /usr/local/bin/pip3.8 /usr/bin/pip3

RUN /usr/bin/pip3 install docxtpl==0.12.0 \
	&& /usr/bin/pip3 install --upgrade pip