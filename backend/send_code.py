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

def fetch_csv(codes_csv):
    import csv
    outlets = []
    with open(codes_csv, newline='',) as csvfile:
        codereader = csv.DictReader(csvfile, delimiter=',')
        for row in codereader:
            outlets.append(row)
    return outlets

def update_status(codes_csv, name, status):
    import csv
    outlets = fetch_csv(codes_csv)
    for outlet in outlets:
        if outlet['name'] == name:
            outlet['status'] = status
    print(outlets)
    with open(codes_csv, 'w', newline='') as csvfile:
        fieldnames = ['name', 'on', 'off', 'place', 'on_time', 'off_time', 'status']
        writer = csv.DictWriter(csvfile, delimiter=',', fieldnames=fieldnames)
        writer.writeheader()
        for outlet in outlets:
            writer.writerow(outlet)



if __name__ == "__main__":
    import sys
    codes_csv = '/var/www/html/Remote_po_gui/codes.csv' # Csv file in absolute search path
    # scheduled turn on and turn off
    if (sys.argv[1] == "cron"):
        # Get current time and fetch information from csv file
        hour, minute = current_time()
        outlets = fetch_csv(codes_csv)
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
                update_status(codes_csv, s['name'], "on")
            # Turn off
            off_time = str(s['off_time']).split(';')
            if time in off_time:
                msg = str(s['off'])
                connection, recive = connect()
                if recive.decode('ascii'):
                    send(connection, msg) #Send status
                    recive = connection.recv(1024) # Message recived
                    connection.close()
                update_status(codes_csv, s['name'], "off")
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
