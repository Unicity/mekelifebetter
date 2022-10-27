#include <stdio.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <sys/types.h>
#include <errno.h>

int ConnectServer(char *, int);
int Read_Line(int, char *, int);
int Write_Line(int, char *, int);

/*
**	argv[1] : ipgclient IPAddress
**	argv[2] : ipgclient Listen Port
**	argv[3] : 암호화 구분(이프로그램은 '2'로만 한다.);
**	argv[3] : 전문
*/
main(int argc, char **argv)
{
	int		Port, len;
	int		sockfd;
	char	MsgBuf[1024*10], IPAddr[16], RecvBuf[1024*10];

	if( argc != 4 )
	{
		fprintf(stdout, "Usage : 아래바라!!\n");
		fprintf(stdout, "**	argv[1] : ipgclient IPAddress\n");
		fprintf(stdout, "**	argv[2] : ipgclient Listen Port\n");
		fprintf(stdout, "**	argv[3] : 전문(전문 = 암호화구분('0' or '2') + 전문)\n");

		exit(1);
	}

	memset(IPAddr, 0x00, sizeof(IPAddr));
	memset(MsgBuf, 0x00, sizeof(MsgBuf));

	sprintf(IPAddr, "%s", argv[1]);
	Port = atoi(argv[2]);
	len = sprintf(MsgBuf, "%04d%s", strlen(argv[3]), argv[3]);

	if( !(sockfd = ConnectServer(IPAddr, Port)) ) exit(1);

	if( !Write_Line(sockfd, MsgBuf, len) ) exit(1);

	fprintf(stdout, "보낸전문 = [%s]\n", MsgBuf);
		
	memset(RecvBuf, 0x00, sizeof(RecvBuf));	

	if( !Read_Line(sockfd, RecvBuf, 4) ) exit(1);

	len = atoi(RecvBuf);

	memset(RecvBuf, 0x00, sizeof(RecvBuf));	
	
	if( !Read_Line(sockfd, RecvBuf, len) ) exit(1);

	close(sockfd);

	fprintf(stdout, "받은전문 = [%s]\n", RecvBuf);
}

int ConnectServer(char *IpAddr, int Port)
{
	int					sockfd;
	struct sockaddr_in	addr;

	if( (sockfd = socket(AF_INET, SOCK_STREAM, 0)) < 0 )
	{
		fprintf(stderr, "[util] - ConnectSever >> Socket Open Error : %s\n", strerror(errno));
		return 0;
	}

	bzero(&addr, sizeof(addr));
	addr.sin_family      = AF_INET;
	addr.sin_addr.s_addr = inet_addr(IpAddr);;
	addr.sin_port        = htons(Port);

	if( connect(sockfd, (struct sockaddr *)&addr, sizeof(addr)) < 0 )
	{
		fprintf(stderr, "[util] - ConnectServer >> connect Error : %s\n", strerror(errno));
		fprintf(stderr, "   >> IP = %s, Port = %d\n", IpAddr, Port);
		close(sockfd);
		return 0;
	}
/*
	fprintf(stdout, "Connect Port Num = %d\n", ntohs(addr.sin_port));
*/
	return sockfd;
}

int Write_Line(int fd, char *sendbuf, int len)
{
	int		slen, wlen, spt = 0;

	slen = len;

	while(1)
	{
		if( (wlen = write(fd, &sendbuf[spt], slen)) < 0 )
		{
			fprintf(stderr, ">>> write(send) error : %s\n", strerror(errno));
			return 0;
		}

		slen = slen - wlen;
		spt = spt + wlen;

		if( slen == 0 ) break;
	}
/*
	fprintf(stdout, "Send_Data -> [%s]\n", sendbuf); fflush(stdout);
*/
	return 1;
}

int Read_Line(int fd, char *recvbuf, int len)
{
	int     restlen, readlen, rtnlen;

	restlen = len;
	readlen = 0;

	while(1)
	{
		if( (rtnlen = read(fd, &recvbuf[readlen], restlen)) < 0 )
		{
			fprintf(stderr, ">>> read(recv) error : %s\n", strerror(errno));
			return 0;
		}

		if( rtnlen == 0 )
		{
			fprintf(stderr, "[util] Error -- read(recv) error : %s\n", strerror(errno));
			return 0;
		}

		/*
		fprintf(stdout, "Recv_Data_tmp -> [%s]\n", recvbuf); fflush(stdout);
		*/
		readlen = readlen + rtnlen;
		restlen = restlen - rtnlen;

		if( restlen == 0 ) break;
	}
/*
	fprintf(stdout, "Recv_Data -> [%s]\n", recvbuf); fflush(stdout);
*/
	return 1;
}

