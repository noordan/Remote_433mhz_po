# Remote 433mhz Poweroutlet
Controll your 433mhz power outlets via raspberry pi/homepage.

A guide for "how to" decode your 433mhz sensor will be published later.

- codes.php - Write your codes thatyou have decoded from your 433mhz controller
- index.php - Is the very basic web interface
- send_code.py - Is the script which will send the code to your own light-control-sevrver
- light_control.py - This is the server, which can run in the background on your raspberry pi. The server will power on/off your remote outlets
- lightcontrol.service - A systemd file foryou, can be enabled to start with your system, and other systemd things
