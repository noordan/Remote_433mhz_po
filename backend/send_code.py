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

def current_time():
    from datetime import datetime, timedelta
    gmt = datetime.now() + timedelta(hours=0) # here can you change your time zone
    # Save Hour and Minute to variable
    gmt_hour = '{:%H}'.format(gmt)
    gmt_minute = '{:%M}'.format(gmt)
    return gmt_hour, gmt_minute

def fetch_csv():
    import csv
    sockets = []
    codes_csv = '/home/noordan/codes.csv' # The csv file, default ../codes.csv
    with open(codes_csv, newline='',) as csvfile:
        codereader = csv.DictReader(csvfile, delimiter=',')
        for row in codereader:
            sockets.append(row)
    return sockets

if __name__ == "__main__":
    import sys
    if (sys.argv[1] == "cron"):
        hour, minute = current_time()
        sockets = fetch_csv()
        print(hour)
        print(minute)
        for socket in sockets:
            print(socket['name'], socket['on_time'])

    else:
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
