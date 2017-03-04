#!/usr/bin/python3           # This is client.py file
import socket
def send(connection, msg):
    #print(msg)
    connection.sendall(msg.encode('ascii'))
def connect():
    # create a socket object ad connect to server
    connection = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    connection.connect(('192.168.0.121', 9999))

    recive = connection.recv(1024) #print message if connected
    #print(recive.decode('ascii'))           # debug
    return connection, recive

if __name__ == "__main__":
    import sys
    try:
        msg = sys.argv[1]
        connection, recive = connect()
        if recive.decode('ascii'):
            send(connection, msg) #Send status
            recive = connection.recv(1024)#Message recived
            #print(recive.decode('ascii'))           # debug
            connection.close()
    except Exception as e:
        #print(e)           # debug
        pass
