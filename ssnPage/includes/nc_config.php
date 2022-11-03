<?php
	// 실명인증 사이트 아이디 비번
	$Nc_SiteID			= "Q662";  				// 나신평에서 부여받은 사이트아이디(사이트코드)를 수정한다.
	$Nc_SitePW			= "14622139";			// 나신평에서 부여받은 비밀번호 수정한다.
	$Nc_encode_path = "/home/httpd/unicity/cb_namecheck/cb_namecheck";			// cb_namecheck 모듈이 설치된 위치의 절대경로와 cb_namecheck 모듈명까지 입력한다.

	// 휴대폰 인증 사이트 코드 비번
	$Cb_SiteID			= "G4021";				// NICE로부터 부여받은 사이트 코드
	$Cb_SitePW			= "OGUOHRYMMD3M";	// NICE로부터 부여받은 사이트 패스워드
	$Cb_encode_path = "/home/httpd/unicity/cb_namecheck/CPClient";		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)

	// IPIN 사이트 코드 비번
	$Ip_SiteID			= "G591";					// IPIN 서비스 사이트 코드		(NICE신용평가정보에서 발급한 사이트코드)
	$Ip_SitePW			= "Bios1804";			// IPIN 서비스 사이트 패스워드	(NICE신용평가정보에서 발급한 사이트패스워드)
	$Ip_encode_path	= "/home/httpd/unicity/cb_namecheck/IPIN2Client";			// 하단내용 참조
	//$Ip_decode_path	= "/home/httpd/unicity/cb_namecheck/KisinfoIPINInterop";			// 하단내용 참조
?>