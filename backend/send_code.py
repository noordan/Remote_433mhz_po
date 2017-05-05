#!/usr/bin/python3           # This is client.py file
import socket
def get_suntime(config, hour, minute, sun_addition):
    import urllib.request, json
    from datetime import datetime, timedelta
    time_differ = int(config['utc']) + int(sun_addition) # time delta variable
    url1 = "https://api.sunrise-sunset.org/json?lat=" + config['latitude'] + "&lng=" + config['longitude'] + "&date=today"
    with urllib.request.urlopen(url1) as url:
        data = json.loads(url.read().decode())
        # Convert sunrise to 24 hour clock
        sunrise = datetime.strftime(datetime.strptime(data['results']['sunrise'], "%I:%M:%S %p") + timedelta(hours=int(time_differ)), "%H:%M")
        # Convert sunset to 24 hour clock
        sunset = datetime.strftime(datetime.strptime(data['results']['sunset'], "%I:%M:%S %p") + timedelta(hours=int(time_differ)), "%H:%M")
    sunrise = sunrise.split(':')
    sunset = sunset.split(':')
    # Compare current time with sunrise
    if sunrise[0] == hour:
        if sunrise[1] == minute:
            return True
        elif int(sunrise[1])+2 == int(minute) or int(sunrise[1])+1 == int(minute):
            return True
        elif int(sunrise[1])-2 == int(minute) or int(sunrise[1])-1 == int(minute):
            return True
        else:
            return False
    # Compare current time with sunset
    elif sunset[0] == hour:
        if sunset[1] == minute:
            return True
        elif int(sunset[1])+2 == int(minute) or int(sunset[1])+1 == int(minute):
            return True
        elif int(sunset[1])-2 == int(minute) or int(sunset[1])-1 == int(minute):
            return True
        else:
            return False
def suntime_adder():
    pass
def parse_config():
    import re, os
    # open config file from parent folder
    config = {}
    path = os.path.dirname(os.path.realpath(__file__))
    config_file = path + '/../config.php'
    with open(config_file, 'r') as f:
        for line in f:
            # Scan for config settings
            if re.search('\'(.+)\' => \'(.*)\'', line):
                config[re.search('\'(.+)\' => \'(.*)\'', line).group(1)] = re.search('\'(.+)\' => \'(.*)\'', line).group(2)
    return config
# send message to light_control.py
def send(msg, config, codes_csv, status):
    # Connect to light_control
    connection, recive = connect(config)
    # Connection established
    if recive.decode('ascii'):
        # Send code to light_control
        connection.sendall(msg.encode('ascii'))
        # Code recived by light_control, terminate connection
        recive = connection.recv(1024)
        connection.close()
        # Update status in csv file
        if status != "web":
            update_status(codes_csv, s['name'], status)

def connect(config):
    # create a socket object and connect to server
    connection = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    connection.connect((config['ip'], int(config['port'])))
    # Get a recive message if connected
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
    with open(codes_csv, 'w', newline='') as csvfile:
        fieldnames = ['name', 'on', 'off', 'place', 'on_time', 'off_time', 'status']
        writer = csv.DictWriter(csvfile, delimiter=',', fieldnames=fieldnames)
        writer.writeheader()
        for outlet in outlets:
            writer.writerow(outlet)



if __name__ == "__main__":
    import sys, os, re
    # Fetch information from config file
    config = {}
    config = parse_config()

    codes_csv = config['default_path'] + '/codes.csv' # Csv file in absolute search path
    # scheduled turn on and turn off via cronjob
    if (sys.argv[1] == "cron"):
        # Get current time and fetch information from csv file
        hour, minute = current_time()
        outlets = fetch_csv(codes_csv)
        time = hour + ":" + minute
        # Loop thru csv file and turn on/off remote outlets
        for s in outlets:
            # Turn on socket
            on_time = str(s['on_time']).split(';')
            off_time = str(s['off_time']).split(';')
            if time in on_time:
                msg = str(s['on'])
                send(msg, config, codes_csv, "on") #Send on code
            else:
                if re.search('sunrise', str(on_time)) or re.search('sunset', str(on_time)):
                #elif "sunrise" in on_time or "sunset" in on_time:
                    for on in on_time:
                        # Check if +/-h on sunrise/sunset in ontime
                        if re.search('(\+\d|-\d)', on):
                            sun_addition = re.search('(\+\d|-\d)', on).group(0)
                        else:
                            sun_addition = 0
                        if get_suntime(config, hour, minute, sun_addition):
                            msg = str(s['on'])
                            send(msg, config, codes_csv, "on") #Send on code
            # Turn off socket
            if time in on_time:
                msg = str(s['off'])
                send(msg, config, codes_csv, "off") #Send on code
            else:
                #if "sunrise" in off_time:
                if re.search('sunrise', str(off_time)) or re.search('sunset', str(off_time)):
                    for off in off_time:
                        # Check if +/-h on sunrise/sunset in offtime
                        if re.search('(\+\d|-\d)', off):
                            sun_addition = re.search('(\+\d|-\d)', off).group(0)
                        else:
                            sun_addition = 0
                        if get_suntime(config, hour, minute, sun_addition):
                            msg = str(s['on'])
                            send(msg, config, codes_csv, "off") #Send on code
    # Code from the web interface or via cli argument
    else:
        try:
            msg = sys.argv[1]
            send(msg, config, codes_csv, "web")
        except Exception as e:
            #print(e)           # debug
            pass
