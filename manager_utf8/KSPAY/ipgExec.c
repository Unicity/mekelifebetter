#include <stdio.h> 
#include <string.h> 

#include "ipgExeclib.h"

/*#define CHAR_SIZE 1024*10*2*/
#define CHAR_SIZE 1024*10 

int main(int argc, char **argv)
{
	char	RequestBuf[CHAR_SIZE], ReplyBuf[CHAR_SIZE];
    
	/* ----------------------------------------------------------------- */
    /* 수정가능 시작 */
	/* 프로그램  시작시 원하는 처리를 할 수 있다. */

 



  
	/* 수정가능 끝 */
	/* ----------------------------------------------------------------- */
	/* 수정불가 시작 */
	InitialEnv(argc, argv);  
	/* 수정불가 끝 */

	/* ----------------------------------------------------------------- */
    /* 수정가능 시작 */







	/* 수정가능 끝 */
	/* ----------------------------------------------------------------- */


    /* 수정불가 시작 */
	/* 승인요청 전문을 받아, KSNET승인 처리 서버에 보낼 준비 */
	memset(RequestBuf, 0x00, sizeof(RequestBuf));
	if( RecvMsg(RequestBuf) < 0 )
	{
		fprintf(stderr, "RecvMsg Error!!\n");
		WriteLog("RecvMsg Error!!\n");
		exit(1);
	}
	

    /* 수정불가 끝 */


	/* ----------------------------------------------------------------- */
    /* 수정가능 시작 */
	/* RecvMsg를 Logging하거나, 원하는 처리를 할수 있다. */

	WriteLog(RequestBuf);
 



	/* 수정가능 끝 */
	/* ----------------------------------------------------------------- */


    /* 수정불가 시작 */
	/* KSNET에 승인요청 전문을 보내고, 그 결과를 받는다. */
	/* 승인 결과는 ReplyBuf에 담겨있다. */
	memset(ReplyBuf, 0x00, sizeof(ReplyBuf));
	if( RequestApproval(RequestBuf, ReplyBuf) < 0 )
	{
		SendErrorMsg(RequestBuf);

		fprintf(stderr, "RequestApproval error!!\n");
		WriteLog("RequestApproval error!!\n");
		exit(1);
	}
    /* 수정불가 끝 */

	/* ----------------------------------------------------------------- */
    /* 수정가능 시작 */
	/* ReplyBuf를 Logging하거나, 원하는 처리를 할수 있다. */

	WriteLog(ReplyBuf);






	/* 수정가능 끝 */
	/* ----------------------------------------------------------------- */


    /* 수정불가 시작 */
	/* 승인결과를 최종 요청지에 보낸다. */
	if( WriteMsg(ReplyBuf) < 0 )
	{
		fprintf(stderr, "WriteMsg Error!!\n");
		exit(1);
	}
    /* 수정불가 끝 */



	/* ----------------------------------------------------------------- */
    /* 수정가능 시작 */







	/* 수정가능 끝 */
	/* ----------------------------------------------------------------- */

	exit(0);
}


