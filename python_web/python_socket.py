#!/usr/bin/python3
import threading
import socket
import sys
import time
import json,os
import XmlTransformationDoc

encoding = 'utf-8'
BUFSIZE = 1024


# 读取线程，从远程读取数据
class Reader(threading.Thread):
    def __init__(self, client):
        threading.Thread.__init__(self)
        self.client = client

    def run(self):
        # while True:
        data = self.client.recv(BUFSIZE)
        if (data):
            string = bytes.decode(data, encoding)
            print("from client::", string, "")
            info = XmlTransformationDoc.XmlTransformationDoc(json.loads(string)['path'],json.loads(string)['name'],json.loads(string)['template_path'])
            path = info.start()
            msg = {
                'path': path
            }
            msg = json.dumps(msg)
            self.client.sendall(msg.encode('utf-8'))
        print("close:", self.client.getpeername())

    def readline(self):
        rec = self.inputs.readline()
        if rec:
            string = bytes.decode(rec, encoding)
            if len(string) > 2:
                string = string[0:-2]
            else:
                string = ' '
        else:
            string = False
        return string


# 监听线程，监听远程连接
# 当远程计算机请求连接时，它将创建一个要处理的读取线程
class Listener(threading.Thread):
    def __init__(self, ip, port):
        threading.Thread.__init__(self)
        self.ip = ip
        self.port = port
        self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        self.sock.bind((ip, port))
        self.sock.listen(0)

    def run(self):
        print("listener started")
        while True:
            client, cltadd = self.sock.accept()
            print("accept a connect...")
            Reader(client).start()
            cltadd = cltadd
            print("accept a connect(new reader..)")
ip = sys.argv[1].split(":")[0]
port = sys.argv[1].split(":")[1]
lst = Listener(ip,int(port))
lst.start()