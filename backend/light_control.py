#!/usr/bin/python3
import socket
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
def get_ip():
    import re, subprocess
    # Some usual interface name
    eth_interfaces = ["eth0", "eth1", "ens160"]

    # test the interfaces after an ip
    for eth_interface in eth_interfaces:
        grep_ip = "/sbin/ifconfig " + eth_interface + "| grep 'inet ' | cut -d\\t -f2 | cut -d\: -f2 | awk '{print $1}' 2> /dev/null"
        proc = subprocess.Popen([grep_ip], stdout=subprocess.PIPE, shell=True)
        (ip_address, err) = proc.communicate()
        ip_address = ip_address.rstrip().decode('ascii')
        # Regex ip
        if re.match("\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}", ip_address):
            return ip_address
def close(clientsocket):
    clientsocket.close()
def bind(config):
    # create a socket object and bind the port
    ip_address = get_ip()
    serversocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    serversocket.bind((ip_address, int(config['port'])))
    # queue up to 5 requests
    serversocket.listen(5)
    return serversocket
def send_code(code, config):
    #run RPi_utils to transmit 433mhz signal
    cmd = "sudo -u pi " + config['default_path'] + "/433Utils/RPi_utils/codesend " + code
    process = subprocess.Popen([cmd], shell=True, stdout=subprocess.PIPE)
    out, err = process.communicate()
def send_nexa(nexa, config):
    cmd = "sudo -u pi " + config['default_path'] + "/backend/Nexa/outlet " + nexa
    process = subprocess.Popen([cmd], shell=True, stdout=subprocess.PIPE)
    out, err = process.communicate()

if __name__ == "__main__":
    import re, subprocess
    serversocket = bind(config)
    config = {}
    config = parse_config
    while True:
        # establish a connection
        clientsocket,addr = serversocket.accept()
        print("Got a connection from %s" % str(addr))            # debug
        if clientsocket:
            try:
                #send thanks for connection to client
                msg='Thank you for connecting'+ "\r\n"
                clientsocket.send(msg.encode('ascii'))

                #recive codefrom client
                code = clientsocket.recv(1024)
                code = code.decode('ascii')
                #if code is recived send to the power outlets
                if code:
                    print(code)           # debug
                    pattern = re.search("^(\d)[_](\d)", code)
                    if pattern:
                       nexa = ((pattern.group(1)) + " " + (pattern.group(2)))
                       for x in range(3):
                        print(nexa)
                        send_nexa(nexa, config)
                    else:
                       for x in range(3):
                        send_code(code, config)
                    r = "Message recived"
                    clientsocket.send(r.encode('ascii'))
            except Exception as e:
                print(e)           # debug
                pass
            finally:
                clientsocket.close()
