#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/types.h> 
#include <sys/socket.h>
#include <netinet/in.h>
#include <signal.h>
#include <errno.h>
 
#define	true	1
#define	false	0

#define	EXEC_PROCESS	"ipgExec"

/*#define CHAR_SIZE 1024*10*2*/
#define CHAR_SIZE 4096 

int  ListenSocketOpen(int);
void makeDaemon();

main(int argc, char *argv[])
{
	int					pid;
	int  				socketfd, newsockfd, len, listen_Port;
	char 				destination_Addr1[16], destination_Addr2[16];
	char				destination_Port1[8],  destination_Port2[8];
	char				service_Name[6];
	struct sockaddr_in	cli_addr;
	
	if ( argc != 4 && argc != 5 && argc != 7 )
	{
		printf("argc = 4\n");
		printf("	Usage : ipgClient Listen_Port KSNet_Addr1 KSNet_Port1\n");
		printf("argc = 5\n");
		printf("	Usage : ipgClient Listen_Port KSNet_Addr1 KSNet_Port1 KTF/KSPAY\n");
		printf("argc = 7\n");
		printf("	Usage : ipgClient Listen_Port KSNet_Addr1 KSNet_Port1 KTF/KSPAY KSNet_Addr2 KSNet_Port2\n\n\n");

		printf("        KSNet_Addr1|KSNet_Port1 -> Primary   KSNet Address & Port\n");
		printf("        KSNet_Addr2|KSNet_Port2 -> Secondary KSNet Address & Port\n");
		exit(0);
	}

	if( getenv("IPGCLIENT_PATH") == NULL )
	{
		printf("ERROR: Cannot search IPGCLIENT_PATH in environment list!!\n\n");
		exit(1);
	}

	memset(destination_Addr1, 0x00, sizeof(destination_Addr1)); memset(destination_Port1, 0x00, sizeof(destination_Port1));
	memset(destination_Addr2, 0x00, sizeof(destination_Addr2)); memset(destination_Port2, 0x00, sizeof(destination_Port2));
	memset(service_Name,0X00,sizeof(service_Name));
	
	listen_Port = atoi(argv[1]);

	strcpy(destination_Addr1, argv[2]); strcpy(destination_Port1, argv[3]);

	if(argc == 4){
		strcpy(service_Name,"KSPAY");	
	}else if(argc == 5){
		strcpy(service_Name,argv[4]);	
	}else if ( argc == 7 )
	{
		strcpy(service_Name,argv[4]);	
		strcpy(destination_Addr2, argv[5]); 
		strcpy(destination_Port2, argv[6]);
	}

	if( !(socketfd = ListenSocketOpen(listen_Port)) ) exit(1);

	makeDaemon();
	
	listen(socketfd, 20);

	signal(SIGCHLD, SIG_IGN);  
	
	printf("[ipgClient] Start IPG Client...\n");

	len = sizeof(cli_addr);
	 
	while(true)
	{
		if( (newsockfd = accept(socketfd, (struct sockaddr *)&cli_addr, &len)) < 0 )
		{
			fprintf(stderr, "[ipgClient] Cannot accept: %s\n", strerror(errno)); 
			continue;
		}
	
		if( (pid = fork()) == 0 )
		{
			char	execbuf[1024], sockbuf[5];
 
			close(socketfd);

			memset(execbuf, 0x00, sizeof(execbuf)); sprintf(execbuf, "%s/%s", getenv("IPGCLIENT_PATH"), EXEC_PROCESS);
			memset(sockbuf, 0x00, sizeof(sockbuf)); sprintf(sockbuf, "%d", newsockfd);

			if( execl(execbuf, EXEC_PROCESS, sockbuf, destination_Addr1, destination_Port1, service_Name,destination_Addr2, destination_Port2, (char *)0) < 0 )
			{
				fprintf(stderr, "[ipgClient] execl fail: %s\n", strerror(errno));
				fprintf(stderr, "execbuf = [%s]\n", execbuf);
				exit(1);
			}
		}
		else
		if( pid < 0 )
		{
			fprintf(stderr, "[ipgClient] fork error: %s\n", strerror(errno));
			exit(1);
		}
		else
			close(newsockfd);
	}
}
	
int ListenSocketOpen(int Port_Num)
{
	int					sockfd;
	struct linger		ling;
	struct sockaddr_in	addr;

	if( (sockfd = socket(AF_INET, SOCK_STREAM, 0)) < 0 )
	{
		printf("Socket Open Error : %s\n", strerror(errno));
		return false;
	}

	ling.l_onoff  = 1;
	ling.l_linger = 1;
	setsockopt(sockfd, SOL_SOCKET, SO_LINGER, (char*)&ling, sizeof(ling));
	setsockopt(sockfd, SOL_SOCKET, SO_REUSEADDR, (char*)&Port_Num, sizeof(Port_Num));

	bzero(&addr, sizeof(addr));
	addr.sin_family      = AF_INET;
	addr.sin_addr.s_addr = INADDR_ANY;
	addr.sin_port        = htons(Port_Num);

	if( bind(sockfd, (struct sockaddr *)&addr, sizeof(addr)) < 0 )
	{
		printf("bind Error : %s\n", strerror(errno));
		return false;
	}

	listen(sockfd, 5);

	return sockfd;
}

void makeDaemon()
{
	pid_t   pid;

	if( (pid = fork()) < 0 )
	{
		printf("MakeDaemon Error : %s\n", strerror(errno));
		exit(1);
	}
	else if( pid != 0 ) exit(0);

	setsid();

	return;
}
