<?php

function global_SendSocketToC($Host, $Port, $Msg, $ReadLength = 1024)
{
	// 소켓 생성
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("can't create socket");
		

		socket_connect($socket, $Host, $Port);

		socket_close ($sock);
	
	// 결과 반환
		return $resultFromC;
}

		$Host = "129.200.9.11"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
		//$Host = "129.200.9.18";

		//$Host = "127.0.0.1"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1

		//$Port = "11372";

		$Host = "129.200.9.11"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
		//$Host = "129.200.9.18"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1

		$Port = "9237"; //Real

		$space = "";
		$inc_code = "UPCHE214";
		$bank_code = "26";
		$no = "000001";
		$trans_date = date(Ymd);
		$trans_time = date(His);

		$msg_code = "0800";
		$gubun = "100";
		$no = "000001";

		$openMsg = sprintf("%9s%8s%02s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%31s%200s",$space,$inc_code,$bank_code,$msg_code,$gubun,1,$no,$trans_date,$trans_time,$space,$space,$trans_date,$space,$space,$space);
			
		$ss = global_SendSocketToC($Host, $Port, $openMsg);


?> 