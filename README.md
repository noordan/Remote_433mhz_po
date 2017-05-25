# Remote 433mhz Poweroutlet
Control your 433mhz power outlets via raspberry pi/homepage.

Support self learning nexa controllers and standard non-self learning controllers.

A guide for "how to" decode your 433mhz sensor and install this project will be published later.


### Installation guide

### Add sockets

### Settings
In the settings page you can do some basic settings for your system, for example scheduling, login, raspberry pi settings.

- The login feature can be used to authenticate against a Microsoft Active directory. When you access to the interface from home, you can simply turn on or turn off your lights. But from a remote location you have to login to control your power outlets.


- The c++ libraries which has been used i this project is the following ones:
[Basic 433mhz support, 433Utils](https://github.com/ninjablocks/433Utils)
[Nexa support, Homewatch - NewRemoteSwitch](https://github.com/hjgode/homewatch/tree/master/arduino/libraries/NewRemoteSwitch)


#### Contributers
Thanks to d3vilb0y who has implemented the nexa support
