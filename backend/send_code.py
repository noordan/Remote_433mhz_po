#!/usr/bin/python3           # This is client.py file
import socket
def send(connection, msg):
    connection.sendall(msg.encode('ascii'))

def connect():
    # create a socket object ad connect to server
    connection = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    connection.connect(('192.168.0.121', 9999))

    recive = connection.recv(1024)
    return connection, recive

def current_time():
    from datetime import datetime, timedelta
    gmt = datetime.now() + timedelta(hours=0) # Change hours=0 if you are in diffrent time zone
    # Save Hour and Minute to variable
    gmt_hour = '{:%H}'.format(gmt)
    gmt_minute = '{:%M}'.format(gmt)
    return gmt_hour, gmt_minute

def fetch_csv():
    import csv
    outlets = []
    codes_csv = '/var/www/html/Remote_po_gui/codes.csv' # Csv file in absolute search path
    with open(codes_csv, newline='',) as csvfile:
        codereader = csv.DictReader(csvfile, delimiter=',')
        for row in codereader:
            outlets.append(row)
    return outlets

if __name__ == "__main__":
    import sys
    # scheduled turn on and turn off
    if (sys.argv[1] == "cron"):
        # Get current time and fetch information from csv file
        hour, minute = current_time()
        outlets = fetch_csv()
        time = hour + ":" + minute
        # Loop thru csv file and turn on/off remote outlets
        for s in outlets:
            # Turn on
            on_time = str(s['on_time']).split(';')
            if time in on_time:
                msg = str(s['on'])
                connection, recive = connect()
                if recive.decode('ascii'):
                    send(connection, msg) #Send status
                    recive = connection.recv(1024) # Message recived
                    connection.close()
            # Turn off
            off_time = str(s['off_time']).split(';')
            if time in off_time:
                msg = str(s['off'])
                connection, recive = connect()
                if recive.decode('ascii'):
                    send(connection, msg) #Send status
                    recive = connection.recv(1024) # Message recived
                    connection.close()
    # Code from the web interface
    else:
        try:
            msg = sys.argv[1]
            connection, recive = connect()
            if recive.decode('ascii'):
                send(connection, msg) # Send status
                recive = connection.recv(1024) # Message recived
                connection.close()
        except Exception as e:
            #print(e)           # debug
            pass
