#!/usr/bin/python3
import socket
def close(clientsocket):
    clientsocket.close()
def bind():
    # create a socket object and bind the port
    serversocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    serversocket.bind(('192.168.0.121', 9999))
    # queue up to 5 requests
    serversocket.listen(5)
    return serversocket
def send_code(code):
    #run RPi_utils to transmit 433mhz signal
    cmd = "sudo -u pi /home/pi/433Utils/RPi_utils/codesend " + code
    process = subprocess.Popen([cmd], shell=True, stdout=subprocess.PIPE)
    out, err = process.communicate()

if __name__ == "__main__":
    import re, subprocess
    serversocket = bind()
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
                    for x in range(3):
                        send_code(code)
                    r = "Message recived"
                    clientsocket.send(r.encode('ascii'))
            except Exception as e:
                print(e)           # debug
                pass
            finally:
                clientsocket.close()
