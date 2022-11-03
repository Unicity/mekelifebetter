<?
//bankValidation에 사용
function global_SendSocketToC($Host, $Port, $Msg, $ReadLength = 1024)
{
	// 소켓 생성
		if ( false == ($sock = socket_create (AF_INET, SOCK_STREAM, 0)) )
		{
			$resultFromC[sock] = false;
			$resultFromC[code] = socket_last_error();
			$resultFromC[msg] = "소켓 생성 에러 (". socket_last_error() .") : ". socket_strerror(socket_last_error());
		}
		$time = time();
		$timeout = "5";
		
	// 소켓 연결
		if( !socket_connect ($sock, $Host, $Port) )
		{
			$resultFromC[sock] = false;
			$resultFromC[code] = socket_last_error($sock);
			$resultFromC[msg] = "소켓 연결 에러 (". socket_last_error() .") : " . socket_strerror(socket_last_error());
			
			if($resultFromC[code] == 115 || $resultFromC[code] == 114){
				if ((time() - $time) >= $timeout) {
					socket_close($sock);
					die("Connection timed out.\n");
				}
				sleep(1);
				return false;
			}
			
			die(socket_strerror($resultFromC[code]) . "\n");
		}
		else
		{
			// 소켓 발송
				socket_write($sock, $Msg, strlen($Msg));
				if ((time() - $time) >= $timeout)
			       {
			         socket_close($sock);
			         die("Connection timed out.\n");
			       }
			// 소켓 수신
				$resultFromC[msg] = socket_read($sock, $ReadLength);
				
			// 소켓 닫기
			socket_close ($sock);
		}
	// 결과 반환
		return $resultFromC;
}

?>