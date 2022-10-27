IPGADMIN_HOME=.
OPENSSL_PATH=openssl_linux
CC=gcc

all: ipgClient ipgExec

ipgExec: ipgExec.c
	${CC} -o ipgExec ipgExec.c -L. -lipgExeclib -lRSA_Encrypt -lrsaref -lseedlib -lsecom -lcrypto -lssl -lnsl

ipgClient: ipgClient.c
	${CC} -o ipgClient ipgClient.c -lnsl
clean:
	rm -f ipgExec *.o
