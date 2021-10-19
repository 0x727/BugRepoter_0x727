#!/bin/bash
#chkconfig:2345 80 05
ps -ef | grep  "python_socket.py"  |grep -v grep |awk '{print $2}' | xargs kill
path="$(cd `dirname $0`; pwd)"
cd $path
python3 "$path/python_socket.py 0.0.0.0:5671"
