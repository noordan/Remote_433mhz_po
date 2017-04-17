#include "NewRemoteTransmitter.h"
#include <stdlib.h>
#include <stdio.h>

NewRemoteTransmitter transmitter(67234623, 2, 263, 4);
NewRemoteTransmitter transmitter(72234621, 2, 160, 4);
int main(int argc, char *argv[]) 
{   
    int unitCode = atoi(argv[1]);
    int command  = atoi(argv[2]);
    if(unitCode > 16 || unitCode < 1 || command > 1 || command < 0)
    	return 1;
 
    if (wiringPiSetup () == -1)
    	 return 1;
	if(command)
		printf("Turning unit %i on\n", unitCode);
	else
		printf("Turning unit %i off\n", unitCode);
		
    transmitter.sendUnit(unitCode, command);
    
    printf("Done!\n");
	return 0;
}
