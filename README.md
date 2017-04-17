# Remote 433mhz Poweroutlet
Control your 433mhz power outlets via raspberry pi/homepage.

Use a central python script to control the 433mhz transmitter for not having to provide the web server root permissions. An other advantage is that you can run your web server on another device.

A guide for "how to" decode your 433mhz sensor and install this project will be published later.

- codes.php - Write your codes that you have decoded from your 433mhz controller
- lights.php - Is the very basic web interface using bootstrap
- send_code.py - Is the script which will send the code to your own light-control-sevrver
- light_control.py - This is the server, which can run in the background on your raspberry pi. The server will power on/off your remote power outlets
- lightcontrol.service - A systemd file for you, can be enabled to start with your system, and other systemd things

The login feature can be used to authenticate against a Microsoft Active directory. When you access to the interface from home, you can simply turn on or turn off your lights. But from a remote location you have to login to control your power outlets.

[Basic 433mhz support, 433Utils](https://github.com/ninjablocks/433Utils)
[Nexa support, Homewatch - NewRemoteSwitch](https://github.com/hjgode/homewatch/tree/master/arduino/libraries/NewRemoteSwitch)
